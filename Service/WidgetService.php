<?php

namespace Tellaw\SunshineAdminBundle\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;

/**
 * Widget Manager
 */
class WidgetService
{

    private $serviceWidgets = array();

    /**
     * Symfony Router on request context
     */
    private $router;

    /**
     * Constructor
     *
     * @param $router
     * @internal param array $configuration
     */
    public function __construct($router)
    {
        $this->router = $router;
    }

    public function getUrlOfWidget ( array $widget, $widgetName, $pageName, $row, $extraParam = null )
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

        $parameters = array( "pageName" => $pageName, "widgetName" => $widgetName, "row" => $row );

        if ($extraParam) {
            $parameters[$extraParam["name"]] = $extraParam["value"];
        }

        $dataUrl = $this->router->generate(
            $route,
            $parameters,
            UrlGeneratorInterface::ABSOLUTE_URL // This guy right here
        );


        return $dataUrl;
    }

    public function addServiceWidget ( $id, $widgetReference )
    {
        $this->serviceWidgets[ $id ] = $widgetReference;
    }

    /**
     * Method loads the configuration and render widgets based on services
     *
     * @param array $pageConfiguration
     * @return array
     * @throws \Exception
     */
    public function loadServicesWidgetsForPage ($pageConfiguration, MessageBag $messageBag ) {

        $serviceWidgets = array();
        foreach ( $pageConfiguration["rows"] as $row ) {

            foreach ( $row as $key => $widgetConfiguration ) {

                $widgetMessages = array();
                if (array_key_exists( "parameters", $widgetConfiguration ))
                {
                    foreach ( $widgetConfiguration["parameters"] as $parameterKey => $parameterValue )
                    {
                        $widgetMessages[$parameterKey] = $parameterValue;
                    }
                }

                $messageBag->addMessage( "parameters", $widgetMessages );

                $widget = $this->renderWidget( $widgetConfiguration, $messageBag );

                if ( $widget ) {
                    $serviceWidgets[$key] = $widget;
                }
            }
        }

        return $serviceWidgets;
    }

    /**
     *
     * Method used to render a widget
     *
     * @param $widgetConfiguration
     * @param $messageBag
     * @return array
     * @throws \Exception
     */
    public function renderWidget ( $widgetConfiguration, $messageBag ) {

        if (    array_key_exists( "service", $widgetConfiguration ) &&
                array_key_exists($widgetConfiguration["service"], $this->serviceWidgets) ) {

            $service = $this->serviceWidgets[$widgetConfiguration["service"]];

            if ($service) {
                $widgetContent = $service->create($widgetConfiguration, $messageBag);

            } else {
                throw new \Exception("Service call for widget (" . $widgetConfiguration["service"] . ") returned a null instead of service ");
            }

            return $widgetContent;

        } else {

            return null;

        }
    }

}
