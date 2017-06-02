<?php

namespace Tellaw\SunshineAdminBundle\Service;

/**
 * Pages Manager
 */
class PageService
{
    /**
     * Pages configuration
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
     *
     * @param string $id Id of the page
     * @return array|false
     * @throws \Exception
     */
    public function getPageConfiguration($id)
    {
        // On vérifie que la page existe
        if (!isset($this->configuration[$id])) {
            return false;
        }

        $page = $this->configuration[$id];

        // Si la page possède un parent, on charge le parent également
        if (isset($page['parent']) && !empty($page['parent'])) {
            $page['parent'] = $this->getPageConfiguration($page['parent']);
            if ($page['parent'] === false) {
                throw new \Exception("The page " . $id . " registers a unknown parent page");
            }
        }

        return $page;
    }
}
