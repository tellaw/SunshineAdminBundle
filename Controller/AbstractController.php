<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends SymfonyAbstractController
{
    /**
     *
     * This method is used to read the parameter in configuration to use the correct bundle for templating.
     * It'll make possible to create templating bundles.
     *
     * @param $template
     * @param $params
     * @return Response
     */
    protected function renderWithTheme($template, $params)
    {
        return $this->render('@TellawSunshineAdmin/'.$template.".html.twig" , $params);
    }

}
