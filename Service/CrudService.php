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

//        $qb = $this->em->createQueryBuilder();
//
//        // ALIAS OF SEARCHED ENTITY
//        $alias = 'e';
//
//        $qb->select($alias);
//        $qb->from($baseConfiguration["configuration"]["class"], $alias);
//        $qb->where($alias.'.id = '.$entityId);
//
//        // GET RESULT
//        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * Get an entity list
     * @param $entityName
     * @return array
     * @internal param Context $context
     * @internal param $configuration
     */
    public function getEntityList( $entityName )
    {

        $listConfiguration = $this->entityService->getListConfiguration( $entityName );
        $baseConfiguration = $this->entityService->getConfiguration( $entityName );

        $fields = [];
        $joins = [];
        $qb = $this->em->createQueryBuilder();

        // ALIAS OF SEARCHED ENTITY
        $alias = 'l';

        // PAGINATION INFOS
//        $limit = $context->getPagination()['limit'];
//        $offset = ($context->getPagination()['Page']-1) * $limit;

        // GET COLUMNS AS FIELDS
        foreach ($listConfiguration as $key => $item) {
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
        $qb->from($baseConfiguration["configuration"]["class"], $alias);

        // PREPARE QUERY WITH JOINED FIELDS
        foreach ($joins as $k => $join) {

            $joinAlias = 'j'.$k;
            $qb->innerJoin($alias.'.'.$join['name'], $joinAlias);
            $joinField = isset($join['string']) ? $join['string'] : 'id';

            $qb->addSelect($joinAlias.'.'.$joinField.' as '.$join['name']);
        }

        // PREPARE QUERY FOR PAGINATION AND ORDER
        /*
        if ($paginate) {
            $qb->setFirstResult($offset);
            $qb->setMaxResults($limit);
        }*/
/*
        if ($context->getOrderBy()) {
            $qb->orderBy($context->getOrderBy(), $context->getOrderWay());
        }
*/
        // PREPARE QUERY FOR PARAM SEARCH
       /*
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
*/
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

}
