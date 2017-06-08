<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Menu management
 */
class MenuController extends AbstractController
{
    /**
     * Expose menu configuration
     *
     * @Route("/menu/{pageId}", name="sunshine_menu")
     * @Method({"GET"})
     *
     * @return \HttpResponse
     */
    public function indexAction( $pageId )
    {
        $configuration = $this->get("sunshine.menu")->getConfiguration();

        return $this->renderWithTheme( "Menu:index", array("menu" => $configuration, "pageId" => $pageId) );
    }
}
