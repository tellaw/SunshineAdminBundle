<?php

namespace Tellaw\SunshineAdminBundle\Services;

use Tellaw\SunshineAdminBundle\Entity\Context;
use Tellaw\SunshineAdminBundle\Interfaces\ContextServiceInterface;
use Symfony\Component\Yaml\Yaml;

class ContextService implements ContextServiceInterface {

    private $contexts = array();

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

        $configuration = Yaml::parse(file_get_contents('../app/config/crud_entities/'.$context->getEntityName().'.yml'));
        $ymlConfiguration = $configuration["tellaw_sunshine_admin_entities"][$context->getEntityName()][ConfigurationReaderService::$_VIEW_CONTEXT_CONFIGURATION];

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