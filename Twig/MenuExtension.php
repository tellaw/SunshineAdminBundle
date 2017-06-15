<?php

namespace Tellaw\SunshineAdminBundle\Twig;

use Tellaw\SunshineAdminBundle\Service\WidgetService;

class MenuExtension extends \Twig_Extension
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
     * @param WidgetService $widgetService
     */
    public function __construct()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('isThisActivePage', array($this, 'isThisActivePage'), array()),
            new \Twig_SimpleFunction('isActivePageIsAChildPage', array($this, 'isActivePageIsAChildPage'), array())
        );
    }

    /**
     * @param $item
     * @param $pageId
     * @return bool
     */
    public function isThisActivePage($item, $pageId)
    {
        if (isset ($item["parameters"]["id"]) && $item["parameters"]["id"] == $pageId) {
            return true;
        } else if (isset ($item["entityName"]) && $item["entityName"] == $pageId) {
            return true;
        } else {
            return false;
        }
    }

    public function isActivePageIsAChildPage ($item, $pageType, $pageIdentifier)
    {
        if ( isset( $item["children"]) ) {
            return $this->isAChildPage( $item, $pageType, $pageIdentifier );
        } else {
            return false;
        }
    }

    private function isAChildPage ( $item, $pageType, $pageIdentifier )
    {

        if ( !isset( $item["children"]) ) {
            return false;
        }

        foreach ( $item["children"] as $item ) {
            if ( $item["type"] == "sunshine_page" && $item["parameters"]["id"] == $pageIdentifier ) {
                return true;
            } else if ($item["type"] == "sunshine_page_list" && $item["entityName"] == $pageIdentifier) {
                return true;
            } else if ($item["type"] == "custom_page" && $item["route"] == $pageIdentifier) {
                return true;
            } else if ( isset( $item["children"] ) ) {
                $this->isAChildPage( $item, $pageType, $pageIdentifier );
            }
        }

        return false;

    }

}
