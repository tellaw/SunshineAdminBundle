<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Tellaw\SunshineAdminBundle\Form\Type\DefaultType;

/**
 * Content pages management
 */
class PageController extends AbstractController
{
    /**
     * Expose Page
     *
     * @Route("/page/{pageId}", name="sunshine_page")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction( $pageId )
    {

        /** @var array $page */
        $page = $this->get("sunshine.pages")->getPageConfiguration($pageId);

        //$configuration = $this->get("sunshine.menu")->getConfiguration();

        return $this->renderWithTheme( "Page:index", ["page" => $page, "pageId" => $pageId] );
    }

    /**
     * Shows entity
     *
     * @Route("/page/edit/{entityName}/{id}", name="sunshine_page_edit")
     * @Route("/page/edit/{entityName}", name="sunshine_page_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param $entityName
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $entityName, $id = null)
    {
        /** @var EntityService $entities */
        $entities = $this->get("sunshine.entities");
        $fieldsConfiguration = $entities->getFormConfiguration($entityName);
        $configuration = $entities->getConfiguration($entityName);

        /** @var CrudService $entities */
        $crudService = $this->get("sunshine.crud_service");
        if ($id) {
            $entity = $crudService->getEntity($entityName, $id);
        } else {
            $entity = new $configuration['configuration']['class'];
        }

        $formOptions = [
            'fields_configuration' => $fieldsConfiguration,
            'configuration' => $configuration,
            'crud_service' => $crudService
        ];

        if (!empty($configuration['form']['formType'])) {
            $form = $this->createForm($configuration['form']['formType'], $entity, $formOptions);
        } else {
            $form = $this->createForm(DefaultType::class, $entity, $formOptions);
        }
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entity = $form->getData();
            $em = $this->get('doctrine')->getEntityManager();
            $em->persist($entity);
            $em->flush($entity);

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Enregistrement effectué.')
            ;

            return $this->redirectToRoute('sunshine_page_edit', ['entityName' => $entityName, 'id' => $id]);
        }

        return $this->render(
            'TellawSunshineAdminBundle:Page:edit.html.twig',
            [
                "form" => $form->createView(),
                "formConfiguration" => $configuration,
                "fields" => $fieldsConfiguration,
                "entityName" => $entityName,
                "entity" => $entity,
                "pageId" => null,
            ]
        );
    }

}
