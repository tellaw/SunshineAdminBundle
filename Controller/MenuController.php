<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Menu management
 */
class MenuController extends AbstractController
{
    /**
     * Expose menu configuration
     *
     * @Route("/menu/{pageType}/{pageIdentifier}", name="sunshine_menu", methods={"GET"})
    *
     * @param $pageType
     * @param $pageIdentifier
     * @return Response
     */
    public function indexAction( $pageType, $pageIdentifier )
    {
        $configuration = $this->get("sunshine.menu")->getConfiguration( $this->getUser() );

        return $this->renderWithTheme( "Menu/index", array("menu" => $configuration, "pageType" => $pageType, "pageIdentifier" => $pageIdentifier) );
    }
}
