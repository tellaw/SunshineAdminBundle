<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tellaw\SunshineAdminBundle\Service\ThemeService;

class DefaultController extends Controller
{

    /**
     * @return mixed
     *
     * @Route(" /", name="sunshine_admin_homepage")
     */
    public function indexAction(  )
    {
        /** @var ThemeService $themeService */
        $themeService = $this->get("sunshine.theme");

        if ($themeService->getHomePageType() == "url") {
            return $this->redirect( $themeService->getHomePageAttribute() );
        } elseif ( $themeService->getHomePageType() == "page" ) {
            return $this->redirectToRoute('sunshine_page', array('pageId' => $themeService->getHomePageAttribute() ));
        } elseif ( $themeService->getHomePageType() == "entity" ) {
            return $this->redirectToRoute('sunshine_page_list', array('pageId' => $themeService->getHomePageAttribute() ));
        }

        return $this->render("TellawSunshineAdminBundle::base-sunshine.html.twig");

    }
}
