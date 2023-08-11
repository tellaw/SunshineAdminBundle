<?php

namespace Tellaw\SunshineAdminBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Twig\Environment;

abstract class AbstractWidget
{
    protected CrudService $crudService;
    protected EntityService $entityService;
    protected FormFactoryInterface $formFactory;
    protected RequestStack $requestStack;
    protected Environment $twig;
    protected EntityManagerInterface $em;

    public function __construct(
        CrudService $crudService,
        EntityService $entityService,
        FormFactoryInterface $formFactory,
        RequestStack $requestStack,
        Environment $twig,
        EntityManagerInterface $em
    ) {
        $this->crudService = $crudService;
        $this->entityService = $entityService;
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->twig = $twig;
        $this->em = $em;
    }

    protected function getDoctrine()
    {
        return $this->em;
    }

    protected function getCurrentRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @return string
     */
    protected function render($template, $parameters )
    {
        return $this->twig->render($template.".html.twig" , $parameters);
    }

    /**
     * @return mixed
     */
    public abstract function create($configuration, MessageBag $messageBag);
}
