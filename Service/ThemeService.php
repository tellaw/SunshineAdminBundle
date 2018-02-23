<?php

namespace Tellaw\SunshineAdminBundle\Service;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Entities Manager
 */
class ThemeService
{
    /**
     * Entities configuration
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
     * Provide the entity configuration for the form view
     *
     * @param string $entityName
     * @return array
     * @throws \Exception
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     *
     * Return the type of the homepage.
     *
     * @return type of the page (string, could be URL, PAGE, ENTITY)
     */
    public function getHomePageType () {
        $config = $this->getConfiguration();

        if (array_key_exists( "homePage", $config )) {
            return $config["homePage"]["type"];
        }

        return null;

    }

    /**
     *
     * Return the value attrivute of the homepage type
     *
     * @return value attribute depending of the homepage type
     * @throws \Exception
     */
    public function getHomePageAttribute () {

        $type = $this->getHomePageType();
        $config = $this->getConfiguration();

        if (!$type) {
            return null;
        }

        if ($type == "url") {
            return $config["homePage"]["url"];
        } elseif ( $type == "entity" ) {
            return $config["homePage"]["entityId"];
        } elseif ( $type == "page") {
            return $config["homePage"]["pageId"];
        } else {
            throw new \Exception("Unable to read homepage data attribute. Must be (url, pageId or entityId)");
        }


    }

}
