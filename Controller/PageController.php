<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Tellaw\SunshineAdminBundle\Form\Type\DefaultType;
use Tellaw\SunshineAdminBundle\Service\WidgetService;

/**
 * Content pages management
 */
class PageController extends AbstractPageController
{
    /**
     * Expose Page by default for the sunshine bundle
     *
     * @Route("/page/{pageId}", name="sunshine_page")
     * @Method({"GET", "POST"})
     *
     * @return JsonResponse
     */
    public function pageAction($pageId = null)
    {
        /** @var MessageBag $messages */
        $messageBag = new MessageBag();
        $messageBag->addMessage( "myCustomKey", "MyCustomValue" );

        return $this->renderPage( array(), $pageId, $messageBag );
    }

    /**
     *
     * Show a list for an entity
     * @Route("/page/list/{entityName}", name="sunshine_page_list")
     *
     * @param Request $request
     * @param $entityName
     */
    public function listAction (Request $request, $entityName) {

        /** @var EntityService $entities */
        $entities = $this->get("sunshine.entities");
        $listConfiguration = $entities->getListConfiguration($entityName);
        $configuration = $entities->getConfiguration($entityName);

        return $this->render(
            'TellawSunshineAdminBundle:Page:list.html.twig',
            [
                "extraParameters" => array ("name" => "entityName", "value" => $entityName),
                "widget" => array ("type" => "list"),
                "formConfiguration" => $configuration,
                "fields" => $listConfiguration,
                "entityName" => $entityName,
                "entity" => $entityName,
                "pageId" => null,
            ]
        );

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

        /** @var CrudService $crudService */
        $crudService = $this->get("sunshine.crud_service");
        if ($id) {
            $entity = $crudService->getEntity($entityName, $id);
        } else {
            $entity = new $configuration['configuration']['class'];
        }

        $formOptions = [
            'fields_configuration' => $fieldsConfiguration,
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
