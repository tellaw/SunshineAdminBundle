<?php

namespace Tellaw\SunshineAdminBundle\Service;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Form;
use Tellaw\SunshineAdminBundle\Form\Type\AttachmentType;
use Tellaw\SunshineAdminBundle\Form\Type\Select2Type;
use Tellaw\SunshineAdminBundle\Interfaces\ConfigurationReaderServiceInterface;

class CrudService
{

    /**
     *
     * Alias used for QueryBuilder
     *
     * @var string
     */
    private $alias = 'l';

    /**
     * Entity manager
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * Entity Service
     * @var EntityService
     */
    private $entityService;

    /**
     * CrudService constructor.
     * @param EntityManagerInterface $em
     * @param EntityService $entityService
     */
    public function __construct(
        EntityManagerInterface $em,
        EntityService $entityService
    ) {
        $this->em = $em;
        $this->entityService = $entityService;
    }

    public function getTotalElementsInTable($entityName, array $filters = null)
    {

        $baseConfiguration = $this->entityService->getConfiguration($entityName);

        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(l)')->from($baseConfiguration["configuration"]["class"], 'l');
        if ($filters !== null) {
            $qb = $this->addFilters($qb, $filters);
        }

        return $qb->getQuery()->getSingleScalarResult();

    }

    /**
     * Return the total count of an entity
     * @param $entityName
     */
    public function getCountEntityElements(
        $entityName,
        $orderCol,
        $orderDir,
        $start,
        $length,
        $searchValue,
        array $filters = null
    ) {
        $result = $this->getEntityList(
            $entityName,
            $orderCol,
            $orderDir,
            $start,
            $length,
            $searchValue,
            false,
            $filters
        );

        return count($result);
    }

    /**
     * Get single Entity
     *
     * @param $entityName
     * @param $entityId
     * @return array
     */
    public function getEntity($entityName, $entityId)
    {
        $baseConfiguration = $this->entityService->getConfiguration($entityName);
        $repository = $this->em->getRepository($baseConfiguration["configuration"]["class"]);

        $result = $repository->findOneById($entityId);

        return $result;
    }


    /**
     *
     * Method used to find data for the SELECT2 Field in AJAX
     *
     * @param $entityClass
     * @param $toString
     * @param $query
     * @param $metadata
     * @return mixed
     */
    public function getEntityListByClassMetadata($entityClass, $toString, $query, $metadata, $page, $itemPerPage)
    {

        $identifier = $metadata->identifier;

        $qb = $this->em->createQueryBuilder();
        $qb->select(array('l.' . $identifier[0], 'l.' . $toString . " AS text"));
        $qb->from($entityClass, 'l');
        $qb->orWhere('l.' . $toString . ' LIKE :search');
        $qb->setFirstResult(($page - 1) * $itemPerPage);
        $qb->setMaxResults($itemPerPage);
        $qb->setParameter('search', "%{$query}%");

        return $qb->getQuery()->getResult();
    }

    /**
     *
     * Method used to find data for the SELECT2 Field in AJAX
     *
     * @param $entityClass
     * @param $toString
     * @param $query
     * @param $metadata
     * @return mixed
     */
    public function getCountEntityListByClassMetadata($entityClass, $toString, $query, $metadata)
    {

        $identifier = $metadata->identifier;

        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(l)');
        $qb->from($entityClass, 'l');
        $qb->orWhere('l.' . $toString . ' LIKE :search');
        $qb->setParameter('search', "%{$query}%");

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Get an entity list
     * @param $entityName
     * @return array
     * @internal param Context $context
     * @internal param $configuration
     */
    public function getEntityList(
        $entityName,
        $orderCol,
        $orderDir,
        $start,
        $length,
        $searchValue,
        $enablePagination = true,
        array $filters = null
    ) {
        $listConfiguration = $this->entityService->getListConfiguration($entityName);
        $baseConfiguration = $this->entityService->getConfiguration($entityName);

        $qb = $this->em->createQueryBuilder();

        $qb = $this->addSelectAndJoin($qb, $listConfiguration, $baseConfiguration, $filters);

        if ($filters !== null) {
            $qb = $this->addFilters($qb, $filters);
        }
        $qb = $this->addPagination($qb, $start, $length, $enablePagination);
        if (!empty($orderCol) && !empty($orderDir)) {
            $qb = $this->addOrderBy($qb, $listConfiguration, $orderCol, $orderDir);
        }

        $qb = $this->addSearch($qb, $searchValue, $listConfiguration, $baseConfiguration);

        $data = $qb->getQuery()->getResult(Query::HYDRATE_OBJECT);

        return $this->flattenObjects($entityName, $data );
    }


    private function addFilters(QueryBuilder $qb, array $filters = null)
    {
        if ($filters[0] === null)
        {
            return $qb;
        }

        foreach ($filters as $filter) {
            if (
                array_key_exists('property', $filter) &&
                array_key_exists('value', $filter)
            ) {
                $qb->andWhere($this->alias . "." . $filter['property'] . " =:value ")
                    ->setParameter("value", $filter["value"]);

            }
        }

        return $qb;
    }

    /**
     *
     * Method used to flatten Objects into array
     *
     * @param $entityName
     * @param $results
     * @return array
     */
    private function flattenObjects($entityName, $results)
    {

        $listConfiguration = $this->entityService->getListConfiguration($entityName);
        $baseConfiguration = $this->entityService->getConfiguration($entityName);

        $class = $baseConfiguration["configuration"]["class"];

        /** @var ClassMetadata $classMetadata */
        $classMetadata = $this->em->getClassMetadata($class);

        $fieldMappings = $classMetadata->fieldMappings;
        $associationMappings = $classMetadata->associationMappings;

        $flattenDatas = array();

        // Loop over objects
        foreach ($results as $result) {

            // Get values for attributes of the object
            $flattenDatasValues = $this->getValuesForAttributes($fieldMappings, $result);

            // Get values for related objects
            $flattenDatasObject = $this->getValuesForRealtedObjects($associationMappings, $result);

            // Merge simple attributes and related objects values into one result
            $flattenDatas[] = array_merge($flattenDatasValues, $flattenDatasObject);

        }

        return $flattenDatas;
    }

    /**
     *
     * Method used to load values for simple attributes
     *
     * @param $fieldMappings
     * @param $object
     * @return array
     */
    private function getValuesForAttributes($fieldMappings, $object)
    {

        $flattenObject = array();

        // Loop over attributes
        foreach ($fieldMappings as $fieldName => $fieldMapping) {

            $getter = "get" . ucfirst($fieldName);
            $value = null;

            if (method_exists($object, $getter)) {
                $value = $object->$getter();
            }
            if ($value instanceof \DateTime)
            {
                $value = $value->format('d-m-Y H:i');
            }
            $flattenObject[$fieldName] = $value;

        }

        return $flattenObject;
    }

    /**
     * Method used to find toString values of related objects
     *
     * @param $associationMappings
     * @param $object
     * @return array
     *
     */
    private function getValuesForRealtedObjects($associationMappings, $object)
    {

        $flattenObject = array();

        // Loop over associations
        foreach ($associationMappings as $associationKey => $associationMapping) {

            $stringValue = null;
            $getter = "get" . ucfirst($associationKey);

            if (method_exists($object, $getter)) {
                $linkedObject = $object->$getter();
            }

            $flattenObject[$associationKey] = $this->getToString($linkedObject);

        }

        return $flattenObject;

    }

    /**
     *
     * Method used to extract the toString of the target Entity
     *
     * @param $element
     * @return string
     */
    private function getToString ( $element )
    {
        dump($element);
        if ($element != null) {
            if (  $element instanceof \iterable  || $element instanceof Collection) {

                $results = array();
                foreach ( $element as $collectionObject ) {
                    $results[] = $this->getToString( $collectionObject );
                }
                return implode( ",", $results );

            } else if (method_exists($element, "__toString")) {

                return $element->__toString();

            } else {
                return get_class($element). " has no toString method";
            }
        } else {
            return "";
        }
    }

    /**
     * This method defines the doctrine alias for an entity
     *
     * @param $property
     * @return string
     */
    private function getAliasForEntity($property)
    {
        return strtolower($property . "_");
    }

    /**
     * Add Select and Join elements to the query
     *
     * @param $qb
     * @param $listConfiguration
     * @param $baseConfiguration
     * @return mixed
     */
    private function addSelectAndJoin($qb, $listConfiguration, $baseConfiguration, array $filters = null)
    {
        $qb->select($this->alias);
        $qb->from($baseConfiguration["configuration"]["class"], $this->alias);

        // GET COLUMNS AS FIELDS
        foreach ($listConfiguration as $key => $item) {

            if (isset($item["type"]) && $item["type"] != "custom" || !isset($item["type"])) {

                if (key_exists('relatedClass', $item) && $item['relatedClass'] != false) {

                    $join = ['class' => $item['relatedClass'], 'name' => $key];
                    $joinAlias = $this->getAliasForEntity($join['name']);
                    $qb->leftJoin($this->alias . '.' . $join['name'], $joinAlias);
                    $qb->addSelect($joinAlias);

                }

            }
        }

        return $qb;

    }

    /**
     * Add the pagination to the query
     *
     * @param $qb
     * @param $start
     * @param $length
     * @param $enablePagination
     * @return mixed
     */
    private function addPagination($qb, $start, $length, $enablePagination)
    {

        // PREPARE QUERY FOR PAGINATION AND ORDER
        if ($enablePagination) {
            $qb->setFirstResult($start);
            $qb->setMaxResults($length);
        }

        return $qb;
    }

    /**
     * Add orderBy to the query
     *
     * @param $qb
     * @param $listConfiguration
     * @param $orderCol
     * @param $orderDir
     * @return mixed
     */
    private function addOrderBy($qb, $listConfiguration, $orderCol, $orderDir)
    {
        $keys = array_keys($listConfiguration);

        if ($this->isRelatedObject($listConfiguration[$keys[$orderCol]])) {
            $joinAlias = $this->getAliasForEntity($keys[$orderCol]);
            $qb->orderBy($joinAlias . "." . $listConfiguration[$keys[$orderCol]]["filterAttribute"], $orderDir);
        } else {
            $qb->orderBy($this->alias . "." . $keys[$orderCol], $orderDir);
        }

        return $qb;
    }

    private function isRelatedObject($item)
    {
        if (key_exists('relatedClass', $item) && $item["relatedClass"] != false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Add search option to the query
     *
     * @param $qb
     * @param $searchValue
     * @param $baseConfiguration
     * @return mixed
     */
    private function addSearch($qb, $searchValue, $listConfiguration, $baseConfiguration)
    {
        // PREPARE QUERY FOR PARAM SEARCH
        if ($searchValue != "" && isset($baseConfiguration["list"]["search"])) {

            $searchConfig = $baseConfiguration["list"]["search"];

            $searchParams = [];
            foreach ($searchConfig as $key => $item) {

                if ($this->isRelatedObject($listConfiguration[$key])) {
                    $joinAlias = $this->getAliasForEntity($key);
                    $qb->orWhere(
                        ' ' . $joinAlias . '.' . $listConfiguration[$key]["filterAttribute"] . ' LIKE :search'
                    );
                    $searchParams[] = " " . $joinAlias . "." . $listConfiguration[$key]["filterAttribute"] . " LIKE :searchParam";
                } else {
                    $qb->orWhere($this->alias . '.' . $key . ' LIKE :search');
                    $searchParams[] = " l." . $key . " LIKE :searchParam";
                }

            }

            $qb->setParameter('search', "%{$searchValue}%");
        }

        return $qb;
    }

    /**
     * Method used to generate fields in form
     *
     * @param Form|FormBuilder $form
     * @param array $formConfiguration
     * @return mixed
     * @throws \Exception
     *
     */
    public function buildFormFields($form, $formConfiguration)
    {
        foreach ($formConfiguration as $fieldName => $field) {

            $fieldAttributes = array();

            if (isset($field['label'])) {
                $fieldAttributes['label'] = $field['label'];
            }

            // Default, let the framework decide
            $type = null;

            switch ($field["type"]) {
                case "date":
                    $fieldAttributes["widget"] = 'single_text';
                    $fieldAttributes["input"] = 'datetime';
                    $fieldAttributes["format"] = 'dd/MM/yyyy';
                    $fieldAttributes["attr"] = array('class' => 'datetime-picker');
                    break;

                case "datetime":
                    $fieldAttributes["widget"] = 'single_text';
                    $fieldAttributes["input"] = 'datetime';
                    $fieldAttributes["format"] = 'dd/MM/yyyy hh:mm';
                    $fieldAttributes["attr"] = array('class' => 'datetime-picker');
                    break;

                case "file":
                    $fieldAttributes["file_property"] = $field["webPath"];
                    $type = AttachmentType::class;
                    break;

                case "object":

                    if (!isset($field["relatedClass"])) {
                        throw new \Exception(
                            "Object must define its related class, using relatedClass attribute or Doctrine relation on Annotation"
                        );
                    }

                    if (!isset($field["expanded"]) || $field["expanded"] == false) {
                        $fieldAttributes["attr"] = array(
                            'class' => $fieldName . '-select2',
                            'filterAttribute' => $field["filterAttribute"],
                            'relatedClass' => str_replace("\\", "\\\\", $field["relatedClass"]),
                            'ajaxCallback' => $field["ajaxCallback"],
                        );
                        $fieldAttributes["class"] = $field["relatedClass"];
                        $fieldAttributes['placeholder'] = !empty($field["placeholder"]) ? $field["placeholder"] : '';
                        $type = Select2Type::class;
                    } else {
                        $fieldAttributes["expanded"] = "true";
                    }
                    break;

                case "object-multiple":

                    if (!isset($field["relatedClass"])) {
                        throw new \Exception(
                            "Object must define its related class, using relatedClass attribute or Doctrine relation on Annotation"
                        );
                    }

                    if (!isset($field["expanded"]) || $field["expanded"] == false) {
                        $fieldAttributes["attr"] = array(
                            'class' => $fieldName . '-select2',
                            'filterAttribute' => $field["filterAttribute"],
                            'relatedClass' => str_replace("\\", "\\\\", $field["relatedClass"]),
                            'ajaxCallback' => $field["ajaxCallback"],
                        );
                        $fieldAttributes["class"] = $field["relatedClass"];
                        $fieldAttributes['placeholder'] = !empty($field["placeholder"]) ? $field["placeholder"] : '';
                        $type = Select2Type::class;
                    } else {
                        $fieldAttributes["expanded"] = "true";
                    }

                    $fieldAttributes["multiple"] = "true";
                    break;
            }

            $form->add($fieldName, $type, $fieldAttributes);
        }

        return $form;
    }

}
