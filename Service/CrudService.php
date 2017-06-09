<?php

namespace Tellaw\SunshineAdminBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Tellaw\SunshineAdminBundle\Entity\Context;
use Tellaw\SunshineAdminBundle\Interfaces\ConfigurationReaderServiceInterface;
use Tellaw\SunshineAdminBundle\Interfaces\CrudServiceInterface;

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

        return count ($result);
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
        $qb = $this->addOrderBy ( $qb, $listConfiguration, $orderCol, $orderDir );
        $qb = $this->addSearch ( $qb, $searchValue, $listConfiguration, $baseConfiguration );


       /*
        // Filters
        $filters = $context->getFilters();
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                $qb->andWhere($alias . '.' . $key . ' LIKE :filterValue');
                $qb->setParameter('filterValue', "%{$value}%");
            }
        }
*/

        // GET RESULT
        $result = $qb->getQuery()->getResult();

        return $result;
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

        // GET COLUMNS AS FIELDS
        foreach ($listConfiguration as $key => $item) {

            if (isset( $item["type"] ) && $item["type"] != "custom" || !isset($item["type"]) ) {

                if (key_exists('relatedClass', $item) && $item['relatedClass'] != false ) {
                    $joinField = ['class' => $item['relatedClass'], 'name' => $key];

                    // GET FOREIGN STRING FIELD TO SHOW
                    if (isset($item['toString'])) {
                        $joinField['string'] = $item['toString'];
                    }
                    $joins[] = $joinField;
                } else {
                    $fields[] = $this->alias.".".$key;
                }
            }
        }

        // PREPARE QUERY WITH FIELDS
        $fieldsLine = implode(',', $fields);
        $qb->select($fieldsLine ? $fieldsLine : $this->alias);
        $qb->from($baseConfiguration["configuration"]["class"], $this->alias);

        // PREPARE QUERY WITH JOINED FIELDS
        foreach ($joins as $k => $join) {

            $joinAlias = $this->getAliasForEntity( $join['name'] );
            $qb->innerJoin($this->alias.'.'.$join['name'], $joinAlias);
            $joinField = isset($join['string']) ? $join['string'] : 'id';

            $qb->addSelect($joinAlias.'.'.$joinField.' as '.$join['name']);
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
    private function addOrderBy ( $qb, $listConfiguration, $orderCol, $orderDir ) {

        //$listConfiguration[$orderCol]
        $keys = array_keys( $listConfiguration );
        if ( $this->isRelatedObject( $listConfiguration[$keys[$orderCol]]) ) {
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
                    $qb->orWhere(' '.$joinAlias.'.'.$listConfiguration[$key]["toString"].' LIKE :search');
                    $searchParams[] = " ".$joinAlias.".".$listConfiguration[$key]["toString"]." LIKE :searchParam";
                } else {
                    $qb->orWhere($this->alias.'.'.$key.' LIKE :search');
                    $searchParams[] = " l.".$key." LIKE :searchParam";
                }

            }

            $qb->setParameter('search', "%{$searchValue}%");
        }
        return $qb;
    }

}
