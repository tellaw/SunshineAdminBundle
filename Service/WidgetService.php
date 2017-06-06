<?php

namespace Tellaw\SunshineAdminBundle\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Widget Manager
 */
class WidgetService
{

    /**
     * Symfony Router on request context
     */
    private $router;

    /**
     * Constructor
     *
     * @param array $configuration
     */
    public function __construct($router)
    {
        $this->router = $router;
    }

    public function getUrlOfWidget ( array $widget, $widgetName, $pageName, $row )
    {

        /**
         * route of widget may receive first :
         * - Widget name
         * - Page name
         */

        // If widget has type attribute, the service should decide of the correct route.
        if (array_key_exists( "type", $widget )) {

            switch ( $widget["type"] )
            {
                case "list":
                    $route = "sunshine_widget_crudlist";
                    break;
                case "edit":
                    $route = "sunshine_widget_crudlist";
                    break;
            }

        } else if ( array_key_exists( "route", $widget )) {

            // If not, a route attribute should be present to generate the correct routing.
            $route = $widget["route"];

        } else {
            throw new \Exception( "Impossible to generate widget route, widget must have a 'type' or a 'route' attribute");
        }

        $dataUrl = $this->router->generate(
            $route,
            array( "pageName" => $pageName, "widgetName" => $widgetName, "row" => $row ),
            UrlGeneratorInterface::ABSOLUTE_URL // This guy right here
        );


        return $dataUrl;
    }

}