<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Content pages management
 */
class PageController extends AbstractController
{
    /**
     * Expose Page
     *
     * @Route("/page/{pageId}", name="sunshine_page")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction( $pageId )
    {

        /** @var array $page */
        $page = $this->get("sunshine.pages")->getPageConfiguration($pageId);

        //$configuration = $this->get("sunshine.menu")->getConfiguration();

        return $this->renderWithTheme( "Page:index", ["page" => $page, "pageId" => $pageId] );
    }
}
