<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Tellaw\SunshineAdminBundle\Service\PageService;
use Tellaw\SunshineAdminBundle\Service\WidgetService;

abstract class AbstractPageController extends AbstractController
{
    /**
     * @var PageService
     */
    private $pageService;

    /**
     * @var WidgetService
     */
    private $widgetService;

    /**
     * AbstractPageController constructor.
     * @param PageService $pageService
     * @param WidgetService $widgetService
     */
    public function __construct(PageService $pageService, WidgetService $widgetService)
    {
        $this->pageService = $pageService;
        $this->widgetService = $widgetService;
    }

    /**
     *
     * Method called in controlleur who wants to render a sunshine page.
     * This method reads the page configuration, and render it.
     *
     * @param null $pageId
     * @param null $messageBag
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
        $page = $this->pageService->getPageConfiguration($pageId);

        if (false === $page) {
            throw new \Exception("Page not found : ".$pageId);
        }

        // Check roles to display the page of not.
        $isVisible = false;
        if (array_key_exists( "roles", $page )) {
            foreach ( $page["roles"] as $role ) {
                if ( $this->isGranted($role) ) {
                    $isVisible = true;
                }
            }
        } else {
            $isVisible = true;
        }

        if (!$isVisible) {
            throw new AccessDeniedException();
        }

        $serviceWidgets = $this->widgetService->loadServicesWidgetsForPage( $page, $messageBag );

        $parameters = array();
        $parameters ["serviceWidgets"]  = $serviceWidgets;
        $parameters ["pageId"]          = $pageId;
        $parameters ["page"]            = $page;
        $parameters ["messageBag"]      = $messageBag;

        return $this->renderWithTheme( "Page/index" , $parameters );

    }

    private function validateMessageBag ( $messageBag ) {

        if ($messageBag === null) {
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
        $pageConfiguration = $this->pageService->getPageConfiguration($pageId);
        if ( $pageId === null ) {
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
        $widgetContent = $this->widgetService->renderWidget( $widgetConfiguration, $messageBag );

        return new Response( $widgetContent );

    }

}
