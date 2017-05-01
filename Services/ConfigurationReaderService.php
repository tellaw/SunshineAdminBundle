<?php

namespace Tellaw\SunshineAdminBundle\Services;

use Tellaw\SunshineAdminBundle\Entity\Context;
use Tellaw\SunshineAdminBundle\Interfaces\ConfigurationReaderServiceInterface;
use Tellaw\SunshineAdminBundle\Interfaces\ContextInterface;
use Tellaw\SunshineAdminBundle\Interfaces\ContextServiceInterface;
use Symfony\Component\Yaml\Yaml;

// TODO : Add the reading of ORM types to override configuration with implicit values
class ConfigurationReaderService implements ConfigurationReaderServiceInterface {

    private $entityConfigurations = array();

    public static $_VIEW_CONTEXT_CONFIGURATION  = "configuration";
    public static $_VIEW_CONTEXT_DEFAULT        = "attributes";
    public static $_VIEW_CONTEXT_LIST           = "list";
    public static $_VIEW_CONTEXT_FORM           = "form";
    public static $_VIEW_CONTEXT_SEARCH         = "search";
    public static $_VIEW_CONTEXT_FILTERS        = "filters";

    /**
     * Method used to read Yml configuration file
     *
     * @param ContextInterface $context
     * @return mixed
     */
    private function getConfigurationForEntity ( Context $context ) {

        if ( !array_key_exists( $context->getEntityName(), $this->entityConfigurations )) {

            // Read Configuration
            $configuration = Yaml::parse(file_get_contents('../app/config/sunshine/crud_entities/'.$context->getEntityName().'.yml'));
            $this->entityConfigurations[ $context->getEntityName() ] = $configuration["tellaw_sunshine_admin_entities"][$context->getEntityName()];

        }

        return $this->entityConfigurations[ $context->getEntityName() ];

    }

    /**
     * Method used to read Yml configuration file
     *
     * @param ContextInterface $context
     * @return mixed
     */
    public function getPageConfiguration ( $pageId ) {
        return Yaml::parse(file_get_contents('../app/config/sunshine/pages/'. $pageId .'.yml'));
    }

    /**
     * Method used to read Yml configuration file
     *
     * @param ContextInterface $context
     * @return mixed
     */
    public function getMenuConfiguration ( $menuId = 'default' ) {
        return Yaml::parse(file_get_contents('../app/config/sunshine/menu/'. $menuId .'.yml'));
    }


    /**
     * Method used to extract headers for a List Object
     * @param ContextInterface $context
     */
    public function getHeaderForLists ( Context $context ) {

        // First Load configuration datas
        $configurationData = $this->getConfigurationForEntity( $context );

        // For each field in Key List
        return $this->getFinalConfigurationForAViewContext( $context, ConfigurationReaderService::$_VIEW_CONTEXT_LIST );

    }

    /**
     * @param Context $context
     * @param $viewContext
     * @return null
     */
    public function getConfigurationForKey ( Context $context, $viewContext ) {
        if ( array_key_exists( $context->getEntityName(), $this->entityConfigurations ) && array_key_exists( $viewContext, $this->entityConfigurations[$context->getEntityName()] ) ) {
            return $this->entityConfigurations[$context->getEntityName()][$viewContext];
        } else {
            return null;
        }
    }

    /**
     *
     * Method used to find the best configuration, depending of possible overrides configured for the view Context
     *
     * @param $context
     * @param $viewContext Static value of the view Context
     */
    public function getFinalConfigurationForAViewContext ( Context $context , $viewContext ) {

        // Load Context
        $this->getConfigurationForEntity($context);

        // Getting the detailed configuration configuration
        $detailedConfiguration = $this->getConfigurationForKey( $context, $viewContext );

        // getting the global configuration
        $globalConfiguration = $this->getConfigurationForKey( $context, ConfigurationReaderService::$_VIEW_CONTEXT_DEFAULT );

        $resultData = array();

        // For every configuration in detailled configuration
        foreach ( $detailedConfiguration as $fieldName => $fieldDetailedConfiguration ) {

            // Check if a configuration is related in global configuration and merge, overwise, just copy the detailed configuration
            // A field cannot be copyied if it is not declared in detailed configuration
            if ( array_key_exists( $fieldName, $globalConfiguration ) ) {
                $fieldGlobalConfiguration = $globalConfiguration[$fieldName];
                $resultData[$fieldName] = array_merge($fieldGlobalConfiguration, $fieldDetailedConfiguration);
            } else {
                $resultData[$fieldName] = $fieldDetailedConfiguration;
            }
        }

        return $resultData;

    }

}