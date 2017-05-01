<?php

namespace Tellaw\SunshineAdminBundle\Services;

use Tellaw\SunshineAdminBundle\Entity\Context;
use Tellaw\SunshineAdminBundle\Interfaces\ConfigurationReaderServiceInterface;
use Tellaw\SunshineAdminBundle\Interfaces\CrudServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class CrudService implements CrudServiceInterface
{

    /**
     * Entity manager
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * Entity manager
     *
     * @var ConfigurationReaderServiceInterface
     */
    private $configurationService;

    public function __construct(EntityManagerInterface $em, ConfigurationReaderServiceInterface $configurationReaderService) {
        $this->em = $em;
        $this->configurationService = $configurationReaderService;
    }

    public function getEntityList(Context $context, $configuration)
    {

        $params = array();
        $dql = $this->getSqlQuery( $context, $configuration );

        // Add filters
        list($dql, $params) = $this->addFiltersToDql( $dql, $context, $params );

        // Add Search
        list($dql, $params) = $this->addSearchToDql( $dql, $context, $params );

        list($dql, $params) = $this->getOrderBy( $dql, $context, $params );

        $query = $this->em->createQuery($dql);
        $query = $this->addPaginationToQuery($query, $context );

        $context->setDql($query->getSql());

        // Add Params to Query
        foreach ( $params as $key => $param ) {
            $query->setParameter( $key, $param);
        }

        $result = $query->getResult();

        return $result;

    }

    public function getEntity(Context $context)
    {

        $repo = $this->em->getRepository ("AppBundle\Entity\Project");
        $object = $repo->find( $context->getTargetId() );

        return $object;

    }

    public function deleteEntity ( Context $context ){

    }

    public function saveEntity ( Context $context, $formPost ){

    }

    private function getSqlQuery(Context $context, $configuration)
    {

        $fields = "";
        $numItems = count($configuration);
        $i = 0;
        foreach ( $configuration as $key => $item ) {
            $fields .= "l.".$key;
            if(++$i != $numItems) {
                $fields .= ", ";
            }
        }

        // Build Fields list
        $dql = 'SELECT '.$fields.' FROM  '.$context->getClassName().' l WHERE 1=1 ';

        return $dql;

    }

    private function addPaginationToQuery ( $query, Context $context ) {

        // Add offset AND Limit
        $startPage = $context->getStartPage() * $context->getNbItemPerPage();
        return $query->setFirstResult($startPage)->setMaxResults( $context->getNbItemPerPage() );

    }

    private function addSearchToDql ( $dql, Context $context, $params ) {

        // Add Search key to every Searchable fields
        // Add OR criteria for every field in search criteria
        // ( l.field1 == %key% OR l.field2 == %key% )...

        if ($context->getSearchKey() != "") {

            $searchConfig = $this->configurationService->getFinalConfigurationForAViewContext( $context, ConfigurationReaderService::$_VIEW_CONTEXT_SEARCH );

            $dql .= " AND ( ";

            $numItems = count($searchConfig);
            $i = 0;
            foreach ( $searchConfig as $key => $item ) {
                $dql .= " l.".$key. " LIKE :searchParam";
                if(++$i != $numItems) {
                    $dql .= " OR ";
                }
            }

            $dql .= " ) ";

            $params["searchParam"] = "%".$context->getSearchKey()."%";

        }
        return array($dql,$params);
    }

    private function addFiltersToDql ( $dql, Context $context, $params ) {

        // Check if there is  any data in filters
        // Add AND Criteria to every field in AND
        // AND l.field1 LIKE param% AND l.field2 LIKE param% ...

        if ($context->getFilters() != "") {

            $filterConfig = $this->configurationService->getFinalConfigurationForAViewContext($context, ConfigurationReaderService::$_VIEW_CONTEXT_FILTERS);

            $filters = $context->getFilters();

            $numItems = count($filters);
            $i = 0;
            /* @var $filter \Tellaw\SunshineAdminBundle\Entity\Filter */
            foreach ($filters as $filter) {

                // Ignore filters which may not be declared in config
                if (array_key_exists( $filter->getKey(), $filterConfig )) {
                    $dql .= " AND l.".$filter->getKey()." LIKE :filter".$filter->getKey();
                    $params["filter".$filter->getKey()] = "%".$filter->getValue()."%";
                }
            }

        }

        return array($dql,$params);

    }

    private function getOrderBy ( $dql, Context $context, $params ) {

        if ($context->getOrderBy() != "") {

            $dql .= " ORDER BY l.".$context->getOrderBy()." ".strtoupper($context->getOrderWay());

        }

        return array($dql,$params);

    }

}