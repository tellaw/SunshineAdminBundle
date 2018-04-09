<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController extends Controller
{
    /**
     *
     * This method is used to read the parameter in configuration to use the correct bundle for templating.
     * It'll make possible to create templating bundles.
     *
     * @param $template
     * @param $params
     * @return mixed
     *
     */
    protected function renderWithTheme ( $template, $params )
    {
        return $this->render('@sunshine/'.$template.".html.twig" , $params);
    }

}
