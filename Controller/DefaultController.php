<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Tellaw\SunshineAdminBundle\Service\ThemeService;

class DefaultController extends AbstractController
{
    protected ThemeService $themeService;

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    /**
     * @Route(" /", name="sunshine_admin_homepage")
     */
    public function indexAction(  )
    {
        if ($this->themeService->getHomePageType() == "url") {
            return $this->redirect( $this->themeService->getHomePageAttribute() );
        } elseif ( $this->themeService->getHomePageType() == "page" ) {
            return $this->redirectToRoute('sunshine_page', array('pageId' => $this->themeService->getHomePageAttribute() ));
        } elseif ( $this->themeService->getHomePageType() == "entity" ) {
            return $this->redirectToRoute('sunshine_page_list', array('pageId' => $this->themeService->getHomePageAttribute() ));
        }

        return $this->render("@TellawSunshineAdmin/base-sunshine.html.twig");

    }
}
