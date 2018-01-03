<?php

namespace Tellaw\SunshineAdminBundle\Service;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @param $entityConfiguration
     */
    public function __construct(array $configuration, $entityConfiguration)
    {
        $this->configuration = $configuration;
        $this->entityConfiguration = $entityConfiguration;
    }

    /**
     * Provide the menu configuration
     * @param $currentUser
     * @return array
     */
    public function getConfiguration($currentUser)
    {
        if (!($currentUser instanceof UserInterface)) {
            return [];
        }

        $autoMenu = array();

        if (call_user_func_array([$currentUser, 'hasRole'], ['ROLE_ADMIN'])) {

            foreach ( $this->entityConfiguration as $entityName => $entityConf ) {
                $autoMenu[] = array (
                    "icon" => "layers",
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
