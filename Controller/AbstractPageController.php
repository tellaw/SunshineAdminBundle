<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Tellaw\SunshineAdminBundle\Service\WidgetService;

abstract class AbstractPageController extends AbstractController
{

    /**
     *
     * Method called in controlleur who wants to render a sunshine page.
     * This method reads the page configuration, and render it.
     *
     * @param $template
     * @param $parameters
     * @param null $pageId
     * @return mixed
     * @throws \Exception
     */
    protected function renderPage ( $pageId = null, $messageBag = null )
    {
        $messageBag = $this->validateMessageBag( $messageBag );

        if ($pageId === null) {
            throw new \Exception( "Page ID cannot be null to render a page" );
        }

        /** @var array $page */
        $page = $this->get("sunshine.pages")->getPageConfiguration($pageId);

        // Check roles to display the page of not.
        $isVisible = false;
        if (array_key_exists( "roles", $page )) {
            foreach ( $page["roles"] as $role ) {
                if ( $this->get('security.authorization_checker')->isGranted($role) ) {
                    $isVisible = true;
                }
            }
        } else {
            $isVisible = true;
        }

        if (!$isVisible) {
            throw new AccessDeniedException();
        }

        if ( $page == null ) {
            throw new \Exception("Page not found : ".$pageId);
        }

        /** @var WidgetService $widgetService */
        $widgetService = $this->get("sunshine.widgets");
        $serviceWidgets = $widgetService->loadServicesWidgetsForPage( $page, $messageBag );

        $parameters = array();
        $parameters ["serviceWidgets"]  = $serviceWidgets;
        $parameters ["pageId"]          = $pageId;
        $parameters ["page"]            = $page;
        $parameters ["messageBag"]      = $messageBag;

        return $this->renderWithTheme( "Page:index" , $parameters );

    }

    private function validateMessageBag ( $messageBag ) {

        if ($messageBag == null) {
            $messageBag = new MessageBag();
        } else if ( is_array( $messageBag ) ) {
            throw new \Exception("MessageBag must be an instance of MessageBag... Array Given ");
        } else if ( ! $messageBag instanceof MessageBag) {
            throw new \Exception("MessageBag must be an instance of MessageBag... -> Given : ".get_class($messageBag));
        }

        return $messageBag;

    }

    protected function renderWidget ( $pageId, $widgetId, $messageBag ) {


        $messageBag = $this->validateMessageBag( $messageBag );

        if ($pageId === null) {
            throw new \Exception( "Page ID cannot be null to render a widget" );
        }

        if ($widgetId === null) {
            throw new \Exception( "Widget ID cannot be null to render a widget" );
        }

        /** @var array $page */
        $pageConfiguration = $this->get("sunshine.pages")->getPageConfiguration($pageId);
        if ( $pageId == null ) {
            throw new \Exception("Page not found : ".$pageId);
        }

        $widgetConfiguration = null;

        foreach ( $pageConfiguration["rows"] as $row ) {

            if ( array_key_exists( $widgetId, $row ) ) {
                $widgetConfiguration = $row[$widgetId];
            }

        }
        if ( !$widgetConfiguration ) {
            throw new \Exception("Widget ID (".$widgetId.") is not configured inside the page : ".$pageId);
        }

        /** @var WidgetService $widgetService */
        $widgetService = $this->get("sunshine.widgets");
        $widgetContent = $widgetService->renderWidget( $widgetConfiguration, $messageBag );

        return new Response( $widgetContent );

    }

}
