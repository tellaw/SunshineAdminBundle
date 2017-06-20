<?php

namespace Tellaw\SunshineAdminBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;

abstract class AbstractWidget {

    protected $requestStack = null;
    protected $twig = null;

    public function __construct( RequestStack $requestStack, \Twig_Environment $twig )
    {
        $this->requestStack = $requestStack;
        $this->twig = $twig;
    }

    public function getCurrentRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function render ( $template, $parameters ) {
        return $this->twig->render($template.".html.twig" , $parameters);
    }

    public abstract function create ( $configuration );


}