<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;

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
    protected function renderPage ( $parameters, $pageId = null, $messageBag = null ) {

        if ($messageBag == null) {
            $messageBag = new MessageBag();
        } else if ( is_array( $messageBag ) ) {
            throw new \Exception("MessageBag must be an instance of MessageBag... Array Given ");
        } else if ( ! $messageBag instanceof MessageBag) {
            throw new \Exception("MessageBag must be an instance of MessageBag... -> Given : ".get_class($messageBag));
        }

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
        $serviceWidgets = $widgetService->loadServicesWidgetsForPage( $page, $messageBag );

        $parameters ["serviceWidgets"]  = $serviceWidgets;
        $parameters ["pageId"]          = $pageId;
        $parameters ["page"]            = $page;
        $parameters ["messageBag"]      = $messageBag;

        return $this->renderWithTheme( "Page:index" , $parameters );

    }

}
