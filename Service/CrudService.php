<?php

namespace Tellaw\SunshineAdminBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Form;
use Tellaw\SunshineAdminBundle\Form\Type\Select2Type;
use Tellaw\SunshineAdminBundle\Interfaces\ConfigurationReaderServiceInterface;
use Doctrine\DBAL\Types\JsonArrayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

    public function getTotalElementsInTable ( $entityName ) {

        $baseConfiguration = $this->entityService->getConfiguration( $entityName );

        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(l)')->from($baseConfiguration["configuration"]["class"], 'l');
        return $qb->getQuery()->getSingleScalarResult();

    }

    /**
     * Return the total count of an entity
     * @param $entityName
     */
    public function getCountEntityElements (  $entityName, $orderCol, $orderDir, $start, $length, $searchValue ) {


        $result = $this->getEntityList(  $entityName, $orderCol, $orderDir, $start, $length, $searchValue, false );

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
        $baseConfiguration = $this->entityService->getConfiguration( $entityName );
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
    public function getEntityListByClassMetadata ( $entityClass, $toString, $query, $metadata, $page, $itemPerPage ) {

        $identifier = $metadata->identifier;

        $qb = $this->em->createQueryBuilder();
        $qb->select(array ( 'l.'.$identifier[0], 'l.'.$toString." AS text"));
        $qb->from($entityClass, 'l');
        $qb->orWhere ('l.'.$toString.' LIKE :search');
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
    public function getCountEntityListByClassMetadata ( $entityClass, $toString, $query, $metadata ) {

        $identifier = $metadata->identifier;

        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(l)');
        $qb->from($entityClass, 'l');
        $qb->orWhere ('l.'.$toString.' LIKE :search');
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
    public function getEntityList( $entityName, $orderCol, $orderDir, $start, $length, $searchValue, $enablePagination = true )
    {

        $listConfiguration = $this->entityService->getListConfiguration( $entityName );
        $baseConfiguration = $this->entityService->getConfiguration( $entityName );

        $qb = $this->em->createQueryBuilder();

        $qb = $this->addSelectAndJoin ( $qb, $listConfiguration, $baseConfiguration );
        $qb = $this->addPagination( $qb, $start, $length, $enablePagination );
        if (!empty($orderCol) && !empty($orderDir)) {
            $qb = $this->addOrderBy ( $qb, $listConfiguration, $orderCol, $orderDir );
        }
        $qb = $this->addSearch ( $qb, $searchValue, $listConfiguration, $baseConfiguration );

        return $this->flattenObjects($listConfiguration, $qb->getQuery()->getResult());
    }

    private function flattenObjects ( $listConfiguration, $result ) {

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return (method_exists( $object, "__toString" ))? $object->__toString() : null;
        });
        $encoder = new JsonEncoder();
        $serializer = new Serializer(array($normalizer), array($encoder));

        $callback = function ($object) {
            if (method_exists($object, "__toString")) {
                return $object->__toString();
            } else {
                return "toString undefined for entity : ".get_class($object);
            }

        };

        $outputserialized = array();
        $joinFields = array();
        foreach ($listConfiguration as $key => $item) {
            if (key_exists('relatedClass', $item) && $item['relatedClass'] != false) {
                $normalizer->setCallbacks(array($key => $callback));
                $joinFields [] = $key;
            }
        }

        foreach ( $result as $key => $object ) {
            $serializedEntity = $serializer->serialize($object, 'json');
            $serializedEntity = json_decode($serializedEntity, true);
            foreach ( $joinFields as $field ) {
                if ( is_array($serializedEntity[$field]) ) {
                    $serializedEntity[$field] = implode( ",", $serializedEntity[$field] );
                }
            }
            $outputserialized[$key] = $serializedEntity;
        }

        return $outputserialized;

    }


    private function getAliasForEntity ( $property ) {
        return strtolower( $property."_" );
    }

    /**
     *
     * Add Select and Join elements to the query
     *
     * @param $qb
     * @param $listConfiguration
     * @param $baseConfiguration
     * @return mixed
     */
    private function addSelectAndJoin ( $qb, $listConfiguration, $baseConfiguration ) {

        $fields = [];
        $joins = [];

        $qb->select($this->alias);
        $qb->from($baseConfiguration["configuration"]["class"], $this->alias);

        // GET COLUMNS AS FIELDS
        foreach ($listConfiguration as $key => $item) {

            if (isset( $item["type"] ) && $item["type"] != "custom" || !isset($item["type"]) ) {

                if (key_exists('relatedClass', $item) && $item['relatedClass'] != false ) {

                    $join = ['class' => $item['relatedClass'], 'name' => $key];
                    $joinAlias = $this->getAliasForEntity( $join['name'] );
                    $qb->innerJoin($this->alias.'.'.$join['name'], $joinAlias);
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
    private function addPagination ( $qb, $start, $length, $enablePagination ) {

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
    private function addOrderBy ( $qb, $listConfiguration, $orderCol, $orderDir )
    {
        $keys = array_keys( $listConfiguration );

        if ($this->isRelatedObject( $listConfiguration[$keys[$orderCol]]) ) {
            $joinAlias = $this->getAliasForEntity( $keys[$orderCol] );
            $qb->orderBy( $joinAlias.".".$listConfiguration[$keys[$orderCol]]["toString"] , $orderDir);
        } else {
            $qb->orderBy( $this->alias.".".$keys[$orderCol] , $orderDir);
        }

        return $qb;
    }

    private function isRelatedObject ( $item ) {
        if (key_exists('relatedClass', $item ) && $item["relatedClass"] != false ) {
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
    private function addSearch ( $qb, $searchValue, $listConfiguration, $baseConfiguration ) {
        // PREPARE QUERY FOR PARAM SEARCH
        if ($searchValue != "" && isset($baseConfiguration["list"]["search"]) ) {

            $searchConfig = $baseConfiguration["list"]["search"];

            $searchParams = [];
            foreach ($searchConfig as $key => $item) {

                if ( $this->isRelatedObject( $listConfiguration[$key]) ) {
                    $joinAlias = $this->getAliasForEntity( $key );
                    $qb->orWhere(' '.$joinAlias.'.'.$listConfiguration[$key]["filterAttribute"].' LIKE :search');
                    $searchParams[] = " ".$joinAlias.".".$listConfiguration[$key]["filterAttribute"]." LIKE :searchParam";
                } else {
                    $qb->orWhere($this->alias.'.'.$key.' LIKE :search');
                    $searchParams[] = " l.".$key." LIKE :searchParam";
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

            $fieldAttributes = array ();

            if (isset($field['label'])) {
                $fieldAttributes['label'] = $field['label'];
            }

            // Default, let the framework decide
            $type = null;

            switch ( $field["type"] ) {
                case "date":
                    $fieldAttributes["widget"]  = 'single_text';
                    $fieldAttributes["input"]   = 'datetime';
                    $fieldAttributes["format"]  = 'dd/MM/yyyy';
                    $fieldAttributes["attr"]    = array('class' => 'datetime-picker');
                    break;

                case "datetime":
                    $fieldAttributes["widget"]  = 'single_text';
                    $fieldAttributes["input"]   = 'datetime';
                    $fieldAttributes["format"]  = 'dd/MM/yyyy hh:mm';
                    $fieldAttributes["attr"]    = array('class' => 'datetime-picker');
                    break;

                case "file":
                    $type = FileType::class;
                    break;

                case "object":

                    if ( !isset ( $field["relatedClass"] ) ) throw new \Exception("Object must define its related class, using relatedClass attribute or Doctrine relation on Annotation");

                    if (!isset($field["expanded"]) || $field["expanded"] == false) {
                        $fieldAttributes["attr"] = array(
                            'class' => $fieldName . '-select2',
                            'filterAttribute' => $field["filterAttribute"],
                            'relatedClass' => str_replace("\\", "\\\\", $field["relatedClass"])
                        );
                        $fieldAttributes["class"] = $field["relatedClass"];
                        $type = Select2Type::class;
                    } else {
                        $fieldAttributes["attr"] = array('class' => 'select-picker', "data-live-search"=>"true");
                        $fieldAttributes["expanded"] = "true";
                    }
                    break;

                case "object-multiple":

                    if ( !isset ( $field["relatedClass"] ) ) throw new \Exception("Object must define its related class, using relatedClass attribute or Doctrine relation on Annotation");

                    if (!isset($field["expanded"]) || $field["expanded"] == false) {
                        $fieldAttributes["attr"] = array(
                            'class' => $fieldName . '-select2',
                            'filterAttribute' => $field["filterAttribute"],
                            'relatedClass' => str_replace("\\", "\\\\", $field["relatedClass"])
                        );
                        $fieldAttributes["class"] = $field["relatedClass"];
                    } else {
                        $fieldAttributes["attr"] = array('class' => 'select-picker', "data-live-search"=>"true");
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
