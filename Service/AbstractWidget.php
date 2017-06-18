<?php

namespace Tellaw\SunshineAdminBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractWidget {

    protected $router = null;
    protected $requestStack = null;
    protected $twig = null;

    public function __construct( $router, RequestStack $requestStack, \Twig_Environment $twig )
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->twig = $twig;
    }

    public function getCurrentRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function getRouter ()
    {
        return $this->router;
    }

    public abstract function render ( $configuration );


}