<?php

namespace Tellaw\SunshineAdminBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Twig_Environment;

abstract class AbstractWidget {

    /**
     * @var RequestStack|null
     */
    protected $requestStack = null;

    /**
     * @var Twig_Environment|null
     */
    protected $twig = null;

    /**
     * @var EntityManagerInterface|null
     */
    protected $em = null;

    /**
     * AbstractWidget constructor.
     * @param RequestStack $requestStack
     * @param Twig_Environment $twig
     * @param EntityManagerInterface $em
     */
    public function __construct(RequestStack $requestStack, Twig_Environment $twig, EntityManagerInterface $em )
    {
        $this->requestStack = $requestStack;
        $this->twig = $twig;
        $this->em = $em;
    }

    /**
     * @return EntityManagerInterface|null
     */
    protected function getDoctrine () {
        return $this->em;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request|null
     */
    protected function getCurrentRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @param $template
     * @param $parameters
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function render ($template, $parameters ) {
        return $this->twig->render($template.".html.twig" , $parameters);
    }

    /**
     * @param $configuration
     * @param MessageBag $messageBag
     * @return mixed
     */
    public abstract function create ($configuration, MessageBag $messageBag);
}
