<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;

abstract class AbstractPageController extends AbstractController
{

    private $messageBag = null;

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
    protected function renderPage ( $parameters, $pageId = null ) {

        if ($pageId === null) {
            throw new \Exception( "Page ID cannot be null to render a page" );
        }

        /** @var array $page */
        $page = $this->get("sunshine.pages")->getPageConfiguration($pageId);

        if ( $page == null ) {
            throw new \Exception("Page not found : ".$pageId);
        }

        /** @var WidgetService $widgetService */
        $widgetService = $this->get("sunshine.widgets");
        $serviceWidgets = $widgetService->loadServicesWidgetsForPage( $page, $this->getMessageBag() );

        $parameters ["serviceWidgets"]  = $serviceWidgets;
        $parameters ["pageId"]          = $pageId;
        $parameters ["page"]            = $page;
        $parameters ["messageBag"]      = $this->messageBag;

        return $this->renderWithTheme( "Page:index" , $parameters );

    }

    protected function getMessageBag () {
        if ($this->messageBag == null) {
            $this->messageBag = new MessageBag();
        }
        return $this->messageBag;
    }


}
