<?php

namespace Tellaw\SunshineAdminBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Tellaw\SunshineAdminBundle\Entity\Context;
use Tellaw\SunshineAdminBundle\Interfaces\ConfigurationReaderServiceInterface;
use Tellaw\SunshineAdminBundle\Interfaces\CrudServiceInterface;

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

    /**
     * CrudService constructor.
     * @param EntityManagerInterface $em
     * @param ConfigurationReaderServiceInterface $configurationReaderService
     */
    public function __construct(
        EntityManagerInterface $em,
        ConfigurationReaderServiceInterface $configurationReaderService
    ) {
        $this->em = $em;
        $this->configurationService = $configurationReaderService;
    }

    /**
     * Get an entity list
     * @param Context $context
     * @param $configuration
     * @return array
     */
    public function getEntityList(Context $context, $configuration)
    {
        $fields = [];
        $joins = [];
        $qb = $this->em->createQueryBuilder();


        // ALIAS OF SEARCHED ENTITY
        $alias = 'l';

        // PAGINATION INFOS
        $offset = $context->getStartPage() * $context->getNbItemPerPage();
        $limit = $context->getNbItemPerPage();

        // GET COLUMNS AS FIELDS
        foreach ($configuration as $key => $item) {
            if (key_exists('class', $item)) {
                $joinField = ['class' => $item['class'], 'name' => $key];

                // GET FOREIGN STRING FIELD TO SHOW
                if (isset($item['string'])) {
                    $joinField['string'] = $item['string'];
                }
                $joins[] = $joinField;
            } else {
                $fields[] = $alias.".".$key;
            }
        }

        // PREPARE QUERY WITH FIELDS
        $fieldsLine = implode(',', $fields);
        $qb->select($fieldsLine ? $fieldsLine : $alias);
        $qb->from($context->getClassName(), $alias);

        // PREPARE QUERY WITH JOINED FIELDS
        foreach ($joins as $k => $join) {

            $joinAlias = 'j'.$k;
            $qb->innerJoin($alias.'.'.$join['name'], $joinAlias);
            $joinField = isset($join['string']) ? $join['string'] : 'id';

            $qb->addSelect($joinAlias.'.'.$joinField.' as '.$join['name']);
        }

        // PREPARE QUERY FOR PAGINATION AND ORDER
        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);
        if ($context->getOrderBy()) {

            $qb->orderBy($context->getOrderBy(), $context->getOrderWay());
        }

        // PREPARE QUERY FOR PARAM SEARCH
        if ($context->getSearchKey() != "") {

            $searchConfig = $this->configurationService->
            getFinalConfigurationForAViewContext(
                $context,
                ConfigurationReaderService::VIEW_CONTEXT_SEARCH
            );

            $searchParams = [];
            foreach ($searchConfig as $key => $item) {
                $qb->orWhere($alias.'.'.$key.' LIKE :search');
                $searchParams[] = " l.".$key." LIKE :searchParam";
            }

            $qb->setParameter('search', "%{$context->getSearchKey()}%");
        }

        $context->setDql($qb->getDQL());

        // GET RESULT
        $result = $qb->getQuery()->getResult();

        return $result;

    }

    /**
     * Method used to Load an entity
     * @param Context $context
     * @return mixed
     */
    public function getEntity(Context $context)
    {
        $repo = $this->em->getRepository($context->getClassName());
        $object = $repo->find($context->getTargetId());

        return $object;
    }

    /**
     * Method used to return a new instance of an entity managed by Sunshine
     *
     * @param Context $context
     * @return mixed
     */
    public function getNewEntity(Context $context)
    {
        return new $context->getClassName();
    }

    /**
     * Method used to populate an object from JSon data received by React Frontend
     *
     * @param Context $context
     * @param $object
     * @param $data
     * @return
     */
    public function hydrateEntity(Context $context, $object, $data)
    {

        foreach ($data as $key => $value) {
            $method = "set".ucfirst($key);
            $object->{$method}($value);
        }

        return $object;
    }

    public function deleteEntity(Context $context)
    {

    }

    private function getSqlQuery(Context $context, $configuration)
    {

        $fields = "";
        $numItems = count($configuration);
        $i = 0;

        foreach ($configuration as $key => $item) {
            $fields .= "l.".$key;
            if (++$i != $numItems) {
                $fields .= ", ";
            }
        }

        // Build Fields list
        $dql = 'SELECT '.$fields.' FROM  '.$context->getClassName().' as l WHERE 1=1 ';

        return $dql;
    }

    private function addPaginationToQuery($query, Context $context)
    {
        // Add offset AND Limit
        $startPage = $context->getStartPage() * $context->getNbItemPerPage();

        return $query->setFirstResult($startPage)->setMaxResults($context->getNbItemPerPage());
    }

    private function addSearchToDql($dql, Context $context, $params)
    {

        // Add Search key to every Searchable fields
        // Add OR criteria for every field in search criteria
        // ( l.field1 == %key% OR l.field2 == %key% )...

        if ($context->getSearchKey() != "") {

            $searchConfig = $this->configurationService->getFinalConfigurationForAViewContext(
                $context,
                ConfigurationReaderService::VIEW_CONTEXT_SEARCH
            );

            $dql .= " AND ( ";

            $numItems = count($searchConfig);
            $i = 0;
            foreach ($searchConfig as $key => $item) {
                $dql .= " l.".$key." LIKE :searchParam";
                if (++$i != $numItems) {
                    $dql .= " OR ";
                }
            }

            $dql .= " ) ";

            $params["searchParam"] = "%".$context->getSearchKey()."%";

        }

        return array($dql, $params);
    }

    private function addFiltersToDql($dql, Context $context, $params)
    {

        // Check if there is  any data in filters
        // Add AND Criteria to every field in AND
        // AND l.field1 LIKE param% AND l.field2 LIKE param% ...

        if ($context->getFilters() != "") {

            $filterConfig = $this->configurationService->getFinalConfigurationForAViewContext(
                $context,
                ConfigurationReaderService::VIEW_CONTEXT_FILTERS
            );

            $filters = $context->getFilters();

            $numItems = count($filters);
            $i = 0;
            /* @var $filter \Tellaw\SunshineAdminBundle\Entity\Filter */
            foreach ($filters as $filter) {

                // Ignore filters which may not be declared in config
                if (array_key_exists($filter->getKey(), $filterConfig)) {
                    $dql .= " AND l.".$filter->getKey()." LIKE :filter".$filter->getKey();
                    $params["filter".$filter->getKey()] = "%".$filter->getValue()."%";
                }
            }

        }

        return array($dql, $params);
    }

    private function getOrderBy($dql, Context $context, $params)
    {
        if ($context->getOrderBy() != "") {
            $dql .= " ORDER BY l.".$context->getOrderBy()." ".strtoupper($context->getOrderWay());
        }

        return array($dql, $params);
    }

}
