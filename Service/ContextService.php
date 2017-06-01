<?php

namespace Tellaw\SunshineAdminBundle\Service;

use Tellaw\SunshineAdminBundle\Entity\Context;
use Tellaw\SunshineAdminBundle\Interfaces\ContextServiceInterface;
use Symfony\Component\Yaml\Yaml;

class ContextService implements ContextServiceInterface {

    /**
     * @var array
     */
    private $contexts = array();

    /**
     * @var string
     */
    private $rootDir;

    /**
     * ContextService constructor.
     * @param $root_dir
     */
    public function __construct($root_dir)
    {
        $this->rootDir =  $root_dir.'/';
    }

    /**
     * Retourne le contexte d'une liste d'entités (pagination, etc...)
     *
     * @param string $entityName
     * @param int $limit
     * @param int $page
     * @param string $searchKeyword
     * @param array $filters
     * @param $orderBy
     * @param string $orderSort
     *
     * @return array
     */
    public function buildEntityListContext($entityName, $limit, $page, $searchKeyword, $filters, $orderBy, $orderSort)
    {
        $context = $this->getContext($entityName);
        $context->setSearchKey($searchKeyword);
        $context->setFilters($filters);
        $context->setOrderBy($orderBy);
        $context->setOrderWay($orderSort);
        $context->setPagination($page, $limit);

        return $context;
    }


    public function getContext ( $contextEntity ) {

        if (!array_key_exists( $contextEntity, $this->contexts )) {

            /* @var $newContext ContextServiceInterface */
            $newContext = new Context();
            $newContext->setEntityName( $contextEntity );

            $newContext = $this->getYmlContextEntity( $newContext );
            $this->contexts[$contextEntity] = $newContext;

        }

        return $this->contexts[$contextEntity];

    }

    private function getYmlContextEntity ( Context $context ) {

        $configuration = Yaml::parse(file_get_contents($this->rootDir.'../app/config/sunshine/crud_entities/'.$context->getEntityName().'.yml'));
        $ymlConfiguration = $configuration["tellaw_sunshine_admin_entities"][$context->getEntityName()][ConfigurationReaderService::VIEW_CONTEXT_CONFIGURATION];

        if (array_key_exists( 'id',$ymlConfiguration  )) {
            $context->setIdentifier( $ymlConfiguration["id"] );
        }

        if (array_key_exists( 'class',$ymlConfiguration  )) {
            $context->setClassName( $ymlConfiguration["class"] );
        }

        if (array_key_exists( 'displayedInMenu',$ymlConfiguration  )) {
            $context->setIsDisplayedInMenu( $ymlConfiguration["displayedInMenu"] );
        }

        if (array_key_exists( 'ROLES',$ymlConfiguration  )) {
            $context->setRoles( $ymlConfiguration["ROLES"] );
        }

        return $context;
    }

}
