<?php

namespace Tellaw\SunshineAdminBundle\Service;

use App\Entity\Societe;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormRegistryInterface;
use Tellaw\SunshineAdminBundle\Form\Type\DefaultType;
use Tellaw\SunshineAdminBundle\Form\Type\FieldsetType;
use Tellaw\SunshineAdminBundle\Form\Type\Select2FilterType;
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
     * CrudService constructor.
     * @param EntityManagerInterface $em
     * @param EntityService $entityService
     */
    public function __construct(
        EntityManagerInterface $em,
        EntityService $entityService,
        protected FormRegistryInterface $formRegistry,
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
            $qb = $this->addFilters($qb, $baseConfiguration["configuration"]["class"], $filters);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Return the total count of an entity
     *
     * @param $entityName
     * @param $searchValue
     * @param array|null $filters
     */
    public function getCountEntityElements($entityName, $searchValue, array $filters = null)
    {
        $listConfiguration = $this->entityService->getListConfiguration($entityName);
        $baseConfiguration = $this->entityService->getConfiguration($entityName);
        $filtersConfiguration = $this->entityService->getFiltersConfiguration($entityName);

        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(l)')->from($baseConfiguration["configuration"]["class"], 'l');
        $qb = $this->addSelectAndJoin($qb, $listConfiguration, $baseConfiguration, true);
        if ($filters !== null) {
            $qb = $this->addFilters($qb, $baseConfiguration["configuration"]["class"], $filters, $filtersConfiguration);
        }
        $qb = $this->addSearch($qb, $searchValue, $listConfiguration, $baseConfiguration);

        return $qb->getQuery()->getSingleScalarResult();
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
    public function deleteEntity($entityName, $entityId)
    {

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
     * @param $page
     * @param $itemPerPage
     * @param null $callbackFunction
     * @param array $callbackParams
     * @return mixed
     */
    public function getEntityListByClassMetadata(
        $entityClass,
        $toString,
        $query,
        ClassMetadata $metadata,
        $page,
        $itemPerPage,
        $callbackFunction = null,
        array $callbackParams = []
    ) {
        $identifier = $metadata->identifier;
        if ($callbackFunction !== null && $callbackFunction != '') {
            $qb = $this->em->getRepository($entityClass)->$callbackFunction(
                $identifier,
                $toString,
                $query,
                $callbackParams
            );
        } else {

            $qb = $this->em->createQueryBuilder();
            $qb->select(array('l.'.$identifier[0], 'l.'.$toString." AS text"));
            $qb->from($entityClass, 'l');
            $qb->orWhere('l.'.$toString.' LIKE :search');
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
     * @param $callbackFunction
     * @param array $callbackParams
     * @return mixed
     */
    public function getCountEntityListByClassMetadata(
        $entityClass,
        $toString,
        $query,
        ClassMetadata $metadata,
        $callbackFunction,
        array $callbackParams = []
    ) {
        $identifier = $metadata->identifier;
        if ($callbackFunction !== null && $callbackFunction != '') {
            /** @var QueryBuilder $qb */
            $qb = $this->em->getRepository($entityClass)->$callbackFunction(
                $identifier,
                $toString,
                $query,
                $callbackParams
            );
            $alias = $qb->getRootAliases()[0];

            return $qb->select("COUNT($alias)")->getQuery()->getSingleScalarResult();
        } else {


            $qb = $this->em->createQueryBuilder();
            $qb->select('COUNT(l)');

            $qb->from($entityClass, 'l');
            $qb->orWhere('l.'.$toString.' LIKE :search');
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
        $qb->select($this->alias);
        $qb->from($baseConfiguration["configuration"]["class"], $this->alias);
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

        return $this->flattenObjects($entityName, $data);
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
        // If no filters in the configuration, then, return QB
        if (empty($filters) || empty($filterConfiguration)) {
            return $qb;
        }

        $qbf = (clone $qb);

        $i = 0;
        foreach ($filters as $filter) {
            // Valid that filter has a property and value attributes
            if (is_array($filter) && array_key_exists('property', $filter) && array_key_exists('value', $filter)) {
                $fieldConfig = $filterConfiguration[$filter['property']];
                $field = $this->alias.".".$filter['property'];
                $isObject = in_array($fieldConfig["type"], ["object", "object-multiple"]);

                // Check for manyToMany or filtering on relatedAttribute
                if ($fieldConfig["type"] === 'object-multiple' || null !== $fieldConfig['filterField']) {
                    $field = $this->getAliasForEntity($filter['property']);

                    if (!in_array($field, $qbf->getAllAliases())) {
                        $qbf->leftJoin($this->alias.'.'.$filter['property'], $field);
                    }

                    if (null !== $fieldConfig['filterField']) {
                        $field .= '.'.$fieldConfig['filterField'];
                    }
                }

                if (true === $fieldConfig["multipleOrLike"]) {
                    $values = array_map(fn($value) => trim($value), explode(',', $filter["value"]));

                    if (1 === count($values)) {
                        $qbf->andWhere($field." LIKE :value$i ")
                            ->setParameter("value$i", "%".$filter["value"]."%");
                    } else {
                        $qbf->andWhere($field." IN (:value$i) ")
                            ->setParameter("value$i", $values);
                    }
                } elseif (true === $fieldConfig["like"] || false === $isObject) {
                    $qbf->andWhere($field." LIKE :value$i ")
                        ->setParameter("value$i", "%".$filter["value"]."%");
                } else {
                    $qbf->andWhere($field." IN (:value$i) ")
                        ->setParameter("value$i", $filter["value"]);
                }
                $i++;
            }
        }

        // Find the identifier of the current class object
        $identifier = $this->em->getClassMetadata($className)->getSingleIdentifierFieldName();

        // Find the identifier name in the request
        $identifierPath = $this->alias.'.'.$identifier;

        // Load all identifiers from the database
        $ids = $qbf->select($identifierPath)->getQuery()->getResult();

        // Add each data to an array
        $ids = array_map(function ($item) use ($identifier) {
            return $item[$identifier];
        }, $ids);

        // Find objects from the first QB wich match the second one id's ????
        $qb->andWhere($identifierPath." IN (:value$i)")
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

            $getter = "get".ucfirst($fieldName);
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

            $getter = "get".ucfirst($associationKey);

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
    protected function getToString($element)
    {
        if ($element !== null) {
            if ($element instanceof \iterable || $element instanceof Collection) {

                $results = array();
                foreach ($element as $collectionObject) {
                    $results[] = $this->getToString($collectionObject);
                }

                return implode(", ", $results);

            } else {
                if (method_exists($element, "__toString")) {

                    return $element->__toString();

                } else {
                    return get_class($element)." has no toString method";
                }
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
        return strtolower($property."_");
    }

    /**
     * Add Select and Join elements to the query
     *
     * @param QueryBuilder $qb
     * @param $listConfiguration
     * @param $baseConfiguration
     * @param bool $isCount Si requête de type COUNT
     * @return mixed
     */
    protected function addSelectAndJoin(QueryBuilder $qb, $listConfiguration, $baseConfiguration, $isCount = false)
    {
        // GET COLUMNS AS FIELDS
        foreach ($listConfiguration as $key => $item) {

            if (isset($item["type"]) && $item["type"] != "custom" || !isset($item["type"])) {

                if (array_key_exists('relatedClass', $item) && $item['relatedClass'] !== false) {

                    $join = ['class' => $item['relatedClass'], 'name' => $key];
                    $joinAlias = $this->getAliasForEntity($join['name']);
                    $qb->leftJoin($this->alias.'.'.$join['name'], $joinAlias);
                    if (!$isCount) {
                        $qb->addSelect($joinAlias);
                    }
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
            $qb->orderBy($joinAlias.".".$listConfiguration[$orderCol]["filterAttribute"], $orderDir);
        } else {
            $qb->orderBy($this->alias.".".$orderCol, $orderDir);
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
                    $searchQuery[] = ' '.$joinAlias.'.'.$listConfiguration[$key]["filterAttribute"].' LIKE :search';
                } else {
                    $searchQuery[] = $this->alias.'.'.$key.' LIKE :search';
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
     * @param string $forcedClass CSS class
     * @param boolean $loadChoices
     * @return mixed
     * @throws \Exception
     */
    public function buildFormFields($form, $formConfiguration, $forcedClass = '', $loadChoices = true)
    {
        $groups = [];
        foreach ($formConfiguration as $fieldName => $field) {
            if (isset($field['group'])) {
                $groups[$field['group']][$fieldName] = $field;
            }
        }
        if (empty($groups)) {
            $this->_buildFormFields($form, $formConfiguration, $forcedClass, $loadChoices);
        } else {
            foreach ($formConfiguration as $fieldName => $field) {
                if (!isset($field['group'])) {
                    $groups['none'][$fieldName] = $field;
                }
            }

            $i = 0;
            foreach ($groups as $groupName => $fields) {
                if ($groupName != 'none') {
                    $i++;
                }
                $child = ($groupName == 'none') ? 'none' : 'group_'.$i;
                $form->add($child, FieldsetType::class, [
                    'label' => $groupName,
                    'fields' => function (FormBuilder $form) use ($fields, $forcedClass, $loadChoices) {
                        $this->_buildFormFields($form, $fields, $forcedClass, $loadChoices);
                    },
                ]);
            }
        }

        return $form;
    }

    /**
     * @param $form
     * @param $formConfiguration
     * @param string $forcedClass
     * @param bool $loadChoices
     * @return Form
     * @throws \Exception
     */
    public function _buildFormFields($form, $formConfiguration, $forcedClass = '', $loadChoices = true)
    {
        foreach ($formConfiguration as $fieldName => $field) {

            if ($fieldName == "disableLoadingValues") {
                continue;
            }

            $fieldAttributes = array();

            if (isset($field['label'])) {
                $fieldAttributes['label'] = $field['label'];
            }

            if (isset($field['required'])) {
                $fieldAttributes["required"] = $field["required"];
            }

            if (isset($field['readonly']) && $field['readonly'] === true) {
                $fieldAttributes["disabled"] = $field["readonly"];
            }

            /** @var Form $form */

            if (isset($field['value']) && !isset($formConfiguration['disableLoadingValues'])) {
                $fieldAttributes['data'] = $this->buildDefaultValueOfField($fieldName, $field, $formConfiguration);
            } elseif (isset($field['value'])) {

                if (isset($field["multiple"]) && $field["multiple"] == true) {

                    $data = $field['value']['arguments'];
                    $fieldAttributes['data'] = $data;
                } else {
                    /* filed[‘value’]
                    * array:1 [▼
                         “arguments” => array:1 [▼
                           “Terminé” => 32
                         ]
                       ]
                    */
                    $data = $field['value']['arguments'];
                    $values = array_values($field['value']['arguments']);
                    if (count($values) > 0) {
                        $fieldAttributes['data'] = $values[0];
                    }
                }

            }

            $fieldAttributes["attr"] = array('class' => $forcedClass);

            if (isset($field['optional']) && true === $field['optional']) {
                $fieldAttributes["attr"]['data-filters-optional'] = true;
            }

            if (isset($field['scope'])) {
                $fieldAttributes["attr"]['data-scope'] = $field['scope'];
            }

            // Default, let the framework decide
            $type = null;

            switch ($field["type"]) {
                case 'string':
                    $type = TextType::class;
                    break;

                case 'text':
                    $type = TextareaType::class;
                    break;

                case 'boolean':
                    $type = CheckboxType::class;
                    break;

                case 'integer':
                    $type = IntegerType::class;
                    break;

                case 'float':
                    $type = NumberType::class;
                    break;

                case "date":
                    $type = DateType::class;
                    $fieldAttributes["widget"] = 'single_text';
                    $fieldAttributes['html5'] = false;
                    $fieldAttributes["input"] = 'datetime';
                    $fieldAttributes["format"] = 'dd/MM/yyyy';
                    $fieldAttributes["attr"]['class'] = 'date-picker '.$forcedClass;
                    break;

                case "datetime":
                    $type = DateTimeType::class;
                    $fieldAttributes["widget"] = 'single_text';
                    $fieldAttributes['html5'] = false;
                    $fieldAttributes["input"] = 'datetime';
                    $fieldAttributes["format"] = 'dd/MM/yyyy HH:mm';
                    $fieldAttributes["attr"]['class'] = 'date-picker '.$forcedClass;
                    break;

                case 'file':
                    $type = FileType::class;
                    break;

                case 'embedded':

                    $type = CollectionType::class;

                    $prototypeConfiguration = $this->entityService->getFormConfiguration($field['configuration']);
                    $fieldAttributes['entry_options'] = array(
                        'fields_configuration' => $prototypeConfiguration,
                        'data_class' => $field['relatedClass'],
                    );

                    $fieldAttributes['entry_type'] = DefaultType::class;

                    $fieldAttributes['allow_add'] = $field['allow_add'];
                    $fieldAttributes['allow_delete'] = $field['allow_delete'];
                    $fieldAttributes['by_reference'] = false; // By Reference to false ensure setter of parent must be called in all case
                    $fieldAttributes['prototype'] = true;
                    $fieldAttributes["attr"]['class'] = 'dynamic-collection '.$forcedClass;
                    $fieldAttributes["attr"]['data-for'] = $fieldName;

                    break;

                case "object":
                case "object-multiple":

                    if (!isset($field["relatedClass"])) {
                        throw new \Exception(
                            "Object must define its related class, using relatedClass attribute or Doctrine relation on Annotation"
                        );
                    }

                    if (!isset($field["expanded"]) || $field["expanded"] === false) {

                        $fieldAttributes["attr"]['class'] = $fieldName.'-select2 '.$forcedClass;
                        $fieldAttributes["attr"]['filterAttribute'] = $field["filterAttribute"];
                        $fieldAttributes["attr"]['callbackFunction'] = (array_key_exists(
                            'callbackFunction',
                            $field
                        )) ? $field["callbackFunction"] : "";
                        $fieldAttributes["attr"]['callbackParams'] = !empty($field['callbackParams']) ? json_encode(
                            $field['callbackParams']
                        ) : '{}';
                        $fieldAttributes["attr"]['relatedClass'] = str_replace("\\", "\\\\", $field["relatedClass"]);

                        $fieldAttributes['placeholder'] = !empty($field["placeholder"]) ? $field["placeholder"] : '';

                        if ($forcedClass == 'filterElement') {
                            $type = Select2FilterType::class;
                        } else {
                            $fieldAttributes["class"] = $field["relatedClass"];
                            $type = Select2Type::class;
                        }

                        // Used for debug options
                        //$type = ChoiceType::class;

                    } else {

                        $fieldAttributes["expanded"] = "true";

                    }

                    if (!$loadChoices && isset($field['value'])) {
                        $fieldAttributes['choices'] = $data;
                    } else {
                        if (!$loadChoices) {
                            $fieldAttributes['choices'] = [];
                        }
                    }

                    $fieldAttributes["multiple"] = (isset($field['multiple']) && $field['multiple']) || $field["type"] === 'object-multiple';
                    $fieldAttributes["required"] = $field["required"];


                    break;

                case "custom":
                    if (isset($field["typeClass"])) {
                        $type = $field["typeClass"];
                        $option = $this->getDefaultsOptionsOfType($type);
                        // Nécessaire pour éviter que le fait d'ajouter des data-* dans les attr puisse écraser les classes par défaults
                        $fieldAttributes['attr'] = array_merge($fieldAttributes['attr'], $option['attr']);
                    }
                    break;

            }
            $form->add($fieldName, $type, $fieldAttributes);

        }

        return $form;
    }

    private function getDefaultsOptionsOfType(string $formType): array
    {
        return $this->formRegistry->getType($formType)->getOptionsResolver()->resolve();
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
        // Define a custom method to retrieve field value
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

                /**
                 * If :
                 *  - type is set
                 *  - type is an object or object-multiple
                 *  - item is not null
                 *
                 *
                 *
                 */
                if (isset($field['type']) &&
                    in_array($field['type'], ['object', 'object-multiple']) &&
                    !is_object($item) &&
                    null !== $item
                ) {

                    // Find the repository of relatedClass
                    $rep = $that->em->getRepository($field['relatedClass']);

                    // If object is a 'multiple' object
                    $multiple = ((isset($field['multiple']) && $field['multiple']) || ($field['type'] === 'object-multiple'));

                    if ($multiple) {

                        if (is_array($item) && is_object(array_values($item)[0])) {
                            return $item;
                        }

                        // If multiple, find every values
                        return $rep->findBy([$formConfiguration[$fieldName]['filterAttribute'] => func_get_args()]);

                    } else {

                        // If not multiple, find ONLY one
                        return $rep->findOneBy([$formConfiguration[$fieldName]['filterAttribute'] => $item]);

                    }

                } else {
                    return $item;
                }

            };

        }

        return call_user_func_array(
            $callable,
            isset($field['value']['arguments']) ? (array)$field['value']['arguments'] : []
        );
    }
}
