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
     * @Route("/menu/{pageType}/{pageIdentifier}", name="sunshine_menu")
     * @Method({"GET"})
     *
     * @return \HttpResponse
     */
    public function indexAction( $pageType, $pageIdentifier )
    {
        $configuration = $this->get("sunshine.menu")->getConfiguration( $this->getUser() );

        return $this->renderWithTheme( "Menu/index", array("menu" => $configuration, "pageType" => $pageType, "pageIdentifier" => $pageIdentifier) );
    }
}
