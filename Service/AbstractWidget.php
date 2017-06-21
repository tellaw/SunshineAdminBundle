<?php

namespace Tellaw\SunshineAdminBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;

abstract class AbstractWidget {

    protected $requestStack = null;
    protected $twig = null;
    protected $em = null;

    public function __construct( RequestStack $requestStack, \Twig_Environment $twig, EntityManager $em )
    {
        $this->requestStack = $requestStack;
        $this->twig = $twig;
        $this->em = $em;
    }

    protected function getDoctrine () {
        return $this->em;
    }

    protected function getCurrentRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    protected function render ( $template, $parameters ) {
        return $this->twig->render($template.".html.twig" , $parameters);
    }

    public abstract function create ( $configuration );


}