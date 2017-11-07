<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Tellaw\SunshineAdminBundle\Event\EntityEvent;
use Tellaw\SunshineAdminBundle\Event\SunshineEvents;
use Tellaw\SunshineAdminBundle\Form\Type\DefaultType;
use Tellaw\SunshineAdminBundle\Service\EntityService;

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
        return $this->renderPage($pageId);
    }

    /**
     * Show a list for an entity
     *
     * @Route("/page/list/{entityName}", name="sunshine_page_list")
     *
     * @param $entityName
     *
     * @return Response
     */
    public function listAction ($entityName) {

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
     * @Route("/page/edit/{entityName}/{id}", name="sunshine_page_edit", options={"expose"=true})
     * @Route("/page/edit/{entityName}", name="sunshine_page_new", options={"expose"=true})
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
            'crud_service' => $crudService,
            'em' => $this->get('doctrine')->getEntityManager()
        ];

        if (!empty($configuration['form']['formType'])) {
            try {
                $form = $this->createForm($configuration['form']['formType'], $entity, $formOptions);
            } catch (UndefinedOptionsException $exception)
            {
                $form = $this->createForm($configuration['form']['formType'], $entity, []);
            }
        } else {
            $form = $this->createForm(DefaultType::class, $entity, $formOptions);
        }
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entity = $form->getData();
            $em = $this->get('doctrine')->getEntityManager();
            $em->persist($entity);

            $event = new EntityEvent($entity);
            $this->get('event_dispatcher')->dispatch(SunshineEvents::ENTITY_PRE_FLUSHED, $event);

            $em->flush($entity);

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Enregistrement effectué.');


            if ($form->has('buttons') && $form->get('buttons')->get('save_and_quit')->isClicked()) {
                return $this->redirectToRoute('sunshine_page_list', ['entityName' => $entityName]);
            }

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

    /**
     * @Route("/page/view/{entityName}/{id}", name="sunshine_page_view", options={"expose"=true})
     * @Method("GET")
     * @param $id
     * @param $entityName
     * @return mixed
     */
    public function viewAction($id, $entityName)
    {
        /** @var MessageBag $messages */
        $messageBag = new MessageBag();
        $messageBag->addMessage("id", $id );
        $messageBag->addMessage("entityName", $entityName);

        return $this->renderPage("practicalFileView", $messageBag);
    }

}
