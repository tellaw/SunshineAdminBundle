<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tellaw\SunshineAdminBundle\Service\MenuService;

/**
 * Menu management
 */
class MenuController extends AbstractController
{
    protected MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Expose menu configuration
     *
     * @Route("/menu/{pageType}/{pageIdentifier}", name="sunshine_menu", methods={"GET"})
     * @return Response
     */
    public function indexAction( $pageType, $pageIdentifier )
    {
        $configuration = $this->menuService->getConfiguration( $this->getUser() );

        return $this->renderWithTheme( "Menu/index", array("menu" => $configuration, "pageType" => $pageType, "pageIdentifier" => $pageIdentifier) );
    }
}
