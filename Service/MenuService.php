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
     * Constructor
     *
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Provide the menu configuration
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}
