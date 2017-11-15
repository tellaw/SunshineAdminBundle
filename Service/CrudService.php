<?php

namespace Tellaw\SunshineAdminBundle\Service;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Tellaw\SunshineAdminBundle\Form\Type\AttachmentType;
use Tellaw\SunshineAdminBundle\Form\Type\DefaultType;
use Tellaw\SunshineAdminBundle\Form\Type\Select2Type;

class CrudService
{
    /**
     *
     * Alias used for QueryBuilder
     *
     * @var string
     */
    protected $alias = 'l';

    /**
     * Entity manager
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * Entity Service
     * @var EntityService
     */
    protected $entityService;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * CrudService constructor.
     * @param EntityManagerInterface $em
     * @param EntityService $entityService
     * @param ContainerInterface $container
     */
    public function __construct(
        EntityManagerInterface $em,
        EntityService $entityService,
        ContainerInterface $container
    ) {
        $this->em = $em;
        $this->entityService = $entityService;
        $this->container = $container;
    }

    public function getTotalElementsInTable($entityName, array $filters = null)
    {

        $baseConfiguration = $this->entityService->getConfiguration($entityName);

        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(l)')->from($baseConfiguration["configuration"]["class"], 'l');
        if ($filters !== null) {
            $qb = $this->addFilters($qb, $baseConfiguration["configuration"]["class"], $filters);
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
     * Remove an entity from DB
     * @param $entityName
     * @param $entityId
     * @return bool
     * @throws \Exception
     */
    public function deleteEntity ($entityName, $entityId) {

        $baseConfiguration = $this->entityService->getConfiguration($entityName);
        $repository = $this->em->getRepository($baseConfiguration["configuration"]["class"]);

        $object = $repository->findOneById($entityId);

        if ($object === null) {
            throw new \Exception("Entity is null : ".$entityName." / ".$entityId);
        }

        $this->em->remove($object);

        // On teste si l'objet n'a pas été détaché via un événement preRemove
        if ($this->em->getUnitOfWork()->isScheduledForDelete($object)) {
            $this->em->flush();
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * Method used to find data for the SELECT2 Field in AJAX
     *
     * @param $entityClass
     * @param $toString
     * @param $query
     * @param \Doctrine\ORM\Mapping\ClassMetadata $metadata
     * @return mixed
     */
    public function getEntityListByClassMetadata($entityClass, $toString, $query, ClassMetadata $metadata, $page, $itemPerPage, $callbackFunction = null)
    {

        $identifier = $metadata->identifier;
        if ($callbackFunction !== null && $callbackFunction != '' )
        {
            $qb =$this->em->getRepository($entityClass)->$callbackFunction($identifier, $toString, $query);
        } else {

            $qb = $this->em->createQueryBuilder();
            $qb->select(array('l.' . $identifier[0], 'l.' . $toString . " AS text"));
            $qb->from($entityClass, 'l');
            $qb->orWhere('l.' . $toString . ' LIKE :search');
            $qb->setParameter('search', "%{$query}%");
        }

        $qb->setFirstResult(($page - 1) * $itemPerPage);
        $qb->setMaxResults($itemPerPage);

        return $qb->getQuery()->getResult();
    }

    /**
     *
     * Method used to find data for the SELECT2 Field in AJAX
     *
     * @param $entityClass
     * @param $toString
     * @param $query
     * @param \Doctrine\ORM\Mapping\ClassMetadata $metadata
     * @return mixed
     */
    public function getCountEntityListByClassMetadata($entityClass, $toString, $query, ClassMetadata $metadata, $callbackFunction)
    {
        $identifier = $metadata->identifier;
        if ($callbackFunction !== null  && $callbackFunction != '')
        {
            /** @var QueryBuilder $qb */
            $qb = $this->em->getRepository($entityClass)->$callbackFunction($identifier, $toString, $query);
            $alias = $qb->getRootAliases()[0];
            return $qb->select("COUNT($alias)")->getQuery()->getSingleScalarResult();
        } else {


            $qb = $this->em->createQueryBuilder();
            $qb->select('COUNT(l)');
            $qb->from($entityClass, 'l');
            $qb->orWhere('l.' . $toString . ' LIKE :search');
            $qb->setParameter('search', "%{$query}%");
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Get an entity list
     * @param $entityName
     * @param $orderCol
     * @param $orderDir
     * @param $start
     * @param $length
     * @param $searchValue
     * @param bool $enablePagination
     * @param array $filters
     * @return array
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
        $filtersConfiguration = $this->entityService->getFiltersConfiguration($entityName);

        $qb = $this->em->createQueryBuilder();

        $qb = $this->addSelectAndJoin($qb, $listConfiguration, $baseConfiguration);

        if ($filters !== null) {
            $qb = $this->addFilters($qb, $baseConfiguration["configuration"]["class"], $filters, $filtersConfiguration);
        }
        $qb = $this->addPagination($qb, $start, $length, $enablePagination);
        if (!empty($orderCol) && !empty($orderDir)) {
            $qb = $this->addOrderBy($qb, $listConfiguration, $orderCol, $orderDir);
        }

        $qb = $this->addSearch($qb, $searchValue, $listConfiguration, $baseConfiguration);

        $data = $qb->getQuery()->getResult(Query::HYDRATE_OBJECT);

        return $this->flattenObjects($entityName, $data );
    }

    /**
     *
     * Method used to filter the query
     *
     * @param QueryBuilder $qb
     * @param $className
     * @param array|null $filters
     * @param $filterConfiguration
     * @return QueryBuilder
     */
    protected function addFilters(QueryBuilder $qb, $className, array $filters = null, $filterConfiguration = null)
    {
        if (empty($filters) || empty($filterConfiguration)) {
            return $qb;
        }

        $qbf = (clone $qb);

        $i = 0;
        foreach ($filters as $filter) {
            if ( array_key_exists('property', $filter) && array_key_exists('value', $filter)) {
                if (in_array($filterConfiguration[$filter['property']]["type"], [ "object",  "object-multiple"]) ) {
                    if ($filterConfiguration[$filter['property']]["type"] === 'object') {
                        $field = $this->alias . "." . $filter['property'];
                    } else {
                        $field = $this->getAliasForEntity($filter['property']);
                    }
                    $qbf
                        ->andWhere($field . " IN (:value$i) ")
                        ->setParameter("value$i", $filter["value"]);
                } else {
                    $qbf->andWhere($this->alias . "." . $filter['property'] . " LIKE :value$i ")
                        ->setParameter("value$i", "%".$filter["value"]."%");
                }
                $i++;

            }
        }

        $identifier = $this->em->getClassMetadata($className)->getSingleIdentifierFieldName();
        $identifierPath = $this->alias . '.' . $identifier;
        $ids = $qbf->select($identifierPath)->getQuery()->getResult();
        $ids = array_map(function($item) use ($identifier){
            return $item[$identifier];
        }, $ids);

        $qb
            ->andWhere($identifierPath. " IN (:value$i)")
            ->setParameter("value$i", $ids);

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
    protected function flattenObjects($entityName, $results)
    {
        $listConfiguration = $this->entityService->getListConfiguration($entityName);
        $baseConfiguration = $this->entityService->getConfiguration($entityName);
        $class = $baseConfiguration["configuration"]["class"];

        /** @var ClassMetadata $classMetadata */
        $classMetadata = $this->em->getClassMetadata($class);

        $fieldMappings = $classMetadata->fieldMappings;
        $associationMappings = $classMetadata->associationMappings;

        $fieldMappings = array_merge($fieldMappings, $listConfiguration);

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
    protected function getValuesForAttributes($fieldMappings, $object)
    {
        $flattenObject = array();

        // Loop over attributes
        foreach ($fieldMappings as $fieldName => $fieldMapping) {

            $getter = "get" . ucfirst($fieldName);
            $value = null;

            if (method_exists($object, $getter)) {
                $value = call_user_func_array([$object, $getter], []);
            }

            if ($value instanceof \DateTime) {
                if ($fieldMapping['type'] === 'date') {
                    $value = $value->format('d-m-Y');
                } else {
                    $value = $value->format('d-m-Y H:i');
                }
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
    protected function getValuesForRealtedObjects($associationMappings, $object)
    {
        $flattenObject = array();

        // Loop over associations
        foreach ($associationMappings as $associationKey => $associationMapping) {

            $getter = "get" . ucfirst($associationKey);

            if (method_exists($object, $getter)) {
                $linkedObject = call_user_func_array([$object, $getter], []);
            } else {
                $linkedObject = null;
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
    protected function getToString ( $element )
    {
        if ($element !== null) {
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
    protected function getAliasForEntity($property)
    {
        return strtolower($property . "_");
    }

    /**
     * Add Select and Join elements to the query
     *
     * @param QueryBuilder $qb
     * @param $listConfiguration
     * @param $baseConfiguration
     * @return mixed
     */
    protected function addSelectAndJoin(QueryBuilder $qb, $listConfiguration, $baseConfiguration)
    {
        $qb->select($this->alias);
        $qb->from($baseConfiguration["configuration"]["class"], $this->alias);

        // GET COLUMNS AS FIELDS
        foreach ($listConfiguration as $key => $item) {

            if (isset($item["type"]) && $item["type"] != "custom" || !isset($item["type"])) {

                if (array_key_exists('relatedClass', $item) && $item['relatedClass'] !== false) {

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
     * @param QueryBuilder $qb
     * @param $start
     * @param $length
     * @param $enablePagination
     * @return mixed
     */
    protected function addPagination(QueryBuilder $qb, $start, $length, $enablePagination)
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
     * @param QueryBuilder $qb
     * @param $listConfiguration
     * @param $orderCol
     * @param $orderDir
     * @return mixed
     */
    protected function addOrderBy(QueryBuilder $qb, $listConfiguration, $orderCol, $orderDir)
    {
        if (isset($listConfiguration[$orderCol]) && $this->isRelatedObject($listConfiguration[$orderCol])) {
            $joinAlias = $this->getAliasForEntity($orderCol);
            $qb->orderBy($joinAlias . "." . $listConfiguration[$orderCol]["filterAttribute"], $orderDir);
        } else {
            $qb->orderBy($this->alias . "." . $orderCol, $orderDir);
        }

        return $qb;
    }

    protected function isRelatedObject($item)
    {
        if (array_key_exists('relatedClass', $item) && $item["relatedClass"] !== false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Add search option to the query
     *
     * @param QueryBuilder $qb
     * @param $searchValue
     * @param $baseConfiguration
     * @return mixed
     */
    protected function addSearch(QueryBuilder $qb, $searchValue, $listConfiguration, $baseConfiguration)
    {
        // PREPARE QUERY FOR PARAM SEARCH
        if ($searchValue != "" && !empty($baseConfiguration["list"]["search"])) {

            $searchConfig = $baseConfiguration["list"]["search"];
            $searchQuery = [];

            foreach ($searchConfig as $key => $item) {
                if ($this->isRelatedObject($listConfiguration[$key])) {
                    $joinAlias = $this->getAliasForEntity($key);
                    $searchQuery[] = ' ' . $joinAlias . '.' . $listConfiguration[$key]["filterAttribute"] . ' LIKE :search';
                } else {
                    $searchQuery[] = $this->alias . '.' . $key . ' LIKE :search';
                }
            }

            if (!empty($searchQuery)) {
                $qb->andWhere(implode(' OR ', $searchQuery));
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
     */
    public function buildFormFields($form, $formConfiguration, $forcedClass = "")
    {
        foreach ($formConfiguration as $fieldName => $field) {
            $fieldAttributes = array();

            if (isset($field['label'])) {
                $fieldAttributes['label'] = $field['label'];
            }

            if (isset($field['value'])) {
                $fieldAttributes['data'] = $this->buildDefaultValueOfField($fieldName, $field, $formConfiguration);
            }

            $fieldAttributes["attr"] = array('class' => $forcedClass);

            // Default, let the framework decide
            $type = null;

            switch ($field["type"]) {
                case "date":
                    $fieldAttributes["widget"] = 'single_text';
                    $fieldAttributes["input"] = 'datetime';
                    $fieldAttributes["format"] = 'dd/MM/yyyy';
                    $fieldAttributes["attr"] = array('class' => 'date-picker '.$forcedClass);
                    break;

                case "datetime":
                    $fieldAttributes["widget"] = 'single_text';
                    $fieldAttributes["input"] = 'datetime';
                    $fieldAttributes["format"] = 'dd/MM/yyyy hh:mm';
                    $fieldAttributes["attr"] = array('class' => 'datetime-picker '.$forcedClass);
                    break;

                case "file":
                    $fieldAttributes["file_property"] = $field["webPath"];
                    $type = AttachmentType::class;
                    break;

                case "embedded":

                    $type = CollectionType::class;

                    $prototypeConfiguration = $this->entityService->getFormConfiguration($field["configuration"]);
                    $fieldAttributes['entry_options'] = array(
                        "fields_configuration" => $prototypeConfiguration,
                        "data_class" => $field["relatedClass"],
                    );

                    $fieldAttributes['entry_type'] = DefaultType::class;

                    $fieldAttributes['allow_add'] =  $field["allow_add"];
                    $fieldAttributes['allow_delete'] =  $field["allow_delete"];
                    $fieldAttributes['by_reference'] =  false; // By Reference to false ensure setter of parent must be called in all case
                    $fieldAttributes['prototype'] =  true;

                    $fieldAttributes['attr'] =  array(
                        'class' => 'dynamic-collection '.$forcedClass,
                        'data-for' => $fieldName
                    );

                    break;

                case "object":
                case "object-multiple":
                    if (!isset($field["relatedClass"])) {
                        throw new \Exception(
                            "Object must define its related class, using relatedClass attribute or Doctrine relation on Annotation"
                        );
                    }

                    if (!isset($field["expanded"]) || $field["expanded"] === false) {
                        $fieldAttributes["attr"] = array(
                            'class' => $fieldName . '-select2 '.$forcedClass,
                            'filterAttribute' => $field["filterAttribute"],
                            'callbackFunction' => (array_key_exists('callbackFunction', $field))? $field["callbackFunction"]: "",
                            'relatedClass' => str_replace("\\", "\\\\", $field["relatedClass"]),
                        );
                        $fieldAttributes["class"] = $field["relatedClass"];
                        $fieldAttributes['placeholder'] = !empty($field["placeholder"]) ? $field["placeholder"] : '';
                        $type = Select2Type::class;
                    } else {
                        $fieldAttributes["expanded"] = "true";
                    }

                    $fieldAttributes["multiple"] = (isset($field['multiple']) && $field['multiple']) || $field["type"] === 'object-multiple';
                    $fieldAttributes["required"] = $field["required"];
                    break;
            }

            $form->add($fieldName, $type, $fieldAttributes);
        }

        return $form;
    }

    /**
     * Crée la valeur par défaut d'un champs.
     *
     * @param $fieldName
     * @param array $field
     * @param array $formConfiguration
     * @return mixed
     */
    private function buildDefaultValueOfField($fieldName, $field, $formConfiguration)
    {
        if (isset($field['value']['provider'])) {
            $piece = explode("@", $field['value']['provider']);

            if (isset($piece[1])) {
                $callable = [$piece[0], $piece[1]];
            } else {
                $callable = $piece[0];
            }
        } else {
            $that = $this;
            $callable = function ($item) use ($that, $field, $fieldName, $formConfiguration) {
                if (isset($field['type']) && in_array($field['type'], ['object', 'object-multiple']) &&  null !== $item) {
                    $rep = $that->em->getRepository($field['relatedClass']);
                    $multiple =((isset($field['multiple']) && $field['multiple']) || ($field['type'] === 'object-multiple'));
                    if ($multiple) {
                        return $rep->findBy([$formConfiguration[$fieldName]['filterAttribute'] => func_get_args()]);
                    } else {
                        return $rep->findOneBy([$formConfiguration[$fieldName]['filterAttribute'] => $item]);
                    }
                } else {
                    return $item;
                }
            };
        }

        return call_user_func_array($callable, isset($field['value']['arguments']) ? (array) $field['value']['arguments'] : []);
    }
}
