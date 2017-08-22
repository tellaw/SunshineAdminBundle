<?php

namespace Tellaw\SunshineAdminBundle\Service;

/**
 * Menu Manager
 */
class MenuService
{
    /**
     * Menu configuration
     * @var array
     */
    private $configuration;

    /**
     * Entity Configuration
     * @var array
     */
    private $entityConfiguration;

    /**
     * Constructor
     *
     * @param array $configuration
     */
    public function __construct(array $configuration, $entityConfiguration)
    {
        $this->configuration = $configuration;
        $this->entityConfiguration = $entityConfiguration;
    }

    /**
     * Provide the menu configuration
     * @return array
     */
    public function getConfiguration( $currentUser )
    {

        $autoMenu = array();

        if ( $currentUser->hasRole('ROLE_ADMIN') ) {

            foreach ( $this->entityConfiguration as $entityName => $entityConf ) {
                $autoMenu[] = array (
                    "icon" => "puzzle",
                    "label" => $entityName,
                    "entityName" => $entityName,
                    "type" => "list"

                );
            }

        $this->configuration[] = array ("type" => "section",
                                        "label" => "Vos entités",
                                        "children" => $autoMenu
        );
        }
        return $this->configuration;
    }
}
