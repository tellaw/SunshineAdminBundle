<?php

namespace Tellaw\SunshineAdminBundle\Twig;

use Tellaw\SunshineAdminBundle\Service\WidgetService;

class WidgetExtension extends \Twig_Extension
{

    /**
     * Pages configuration
     * @var array
     */
    private $configuration;

    /**
     * Service managing widgets
     * @var WidgetService
     */
    private $widgetService;

    /**
     * Constructor
     *
     * @param array $configuration
     */
    public function __construct(array $configuration, WidgetService $widgetService)
    {
        $this->configuration = $configuration;
        $this->widgetService = $widgetService;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getWidgetUrl', array($this, 'getWidgetUrl'), array()),
        );
    }

    public function getWidgetUrl($widget, $widgetName, $pageId, $row)
    {
        return $this->widgetService->getUrlOfWidget( $widget, $widgetName, $pageId, $row );
    }

}