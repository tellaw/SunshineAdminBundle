<?php

namespace Tellaw\SunshineAdminBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
    public function getEntityList(Context $context, $configuration, $paginate = true)
    {
        $fields = [];
        $joins = [];
        $qb = $this->em->createQueryBuilder();

        // ALIAS OF SEARCHED ENTITY
        $alias = 'l';

        // PAGINATION INFOS
        $limit = $context->getPagination()['limit'];
        $offset = ($context->getPagination()['page']-1) * $limit;

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
        if ($paginate) {
            $qb->setFirstResult($offset);
            $qb->setMaxResults($limit);
        }

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

        // Filters
        $filters = $context->getFilters();
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                $qb->andWhere($alias . '.' . $key . ' LIKE :filterValue');
                $qb->setParameter('filterValue', "%{$value}%");
            }
        }

        $context->setDql($qb->getDQL());

        // GET RESULT
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * Nombre d'éléments total (hors paginaton)
     *
     * @param Context $context
     * @param array $configuration
     * 
     * @return int
     */
    public function getEntityListTotalCount(Context $context, $configuration)
    {
        return count($this->getEntityList($context, $configuration, false));
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

}
