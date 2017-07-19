<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{

    /**
     * @Route(" /", name="sunshine_admin_homepage")
     */
    public function indexAction()
    {
        return $this->render("TellawSunshineAdminBundle::base-sunshine.html.twig");
    }
}
