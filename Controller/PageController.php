<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Tellaw\SunshineAdminBundle\Event\EntityEvent;
use Tellaw\SunshineAdminBundle\Event\SunshineEvents;
use Tellaw\SunshineAdminBundle\Form\Type\DefaultType;
use Tellaw\SunshineAdminBundle\Service\CrudService;
use Tellaw\SunshineAdminBundle\Service\EntityService;
use Tellaw\SunshineAdminBundle\Service\PageService;
use Tellaw\SunshineAdminBundle\Service\WidgetService;

/**
 * Content pages management
 */
class PageController extends AbstractPageController
{
    protected EventDispatcherInterface $eventDispatcher;
    protected EntityManagerInterface $em;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $em,
        PageService $pageService,
        WidgetService $widgetService,
        EntityService $entityService,
        CrudService $crudService
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->em = $em;
        parent::__construct($pageService, $widgetService, $entityService, $crudService);
    }

    /**
     * Expose Page by default for the sunshine bundle
     *
     * @Route("/page/{pageId}", name="sunshine_page", methods={"GET", "POST"})
     * @param null $pageId
     * @return JsonResponse
     * @throws \Exception
     */
    public function pageAction($pageId = null)
    {
        return $this->renderPage($pageId);
    }

    /**
     * Show a list for an entity
     *
     * @Route("/page/list/{entityName}", name="sunshine_page_list")
     * @return Response
     * @throws \Exception
     */
    public function listAction($entityName)
    {
        $listConfiguration = $this->entityService->getListConfiguration($entityName);
        $configuration = $this->entityService->getConfiguration($entityName);

        return $this->render(
            '@TellawSunshineAdmin/Page/list.html.twig',
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
     * @Route("/page/edit/{entityName}/{id}", name="sunshine_page_edit", options={"expose"=true}, methods={"GET", "POST"})
     * @Route("/page/edit/{entityName}", name="sunshine_page_new", options={"expose"=true}, methods={"GET", "POST"})
     * @return Response
     * @throws \Exception
     */
    public function editAction(Request $request, $entityName, $id = null)
    {
        $fieldsConfiguration = $this->entityService->getFormConfiguration($entityName);
        $configuration = $this->entityService->getConfiguration($entityName);

        if ($id) {
            $entity = $this->crudService->getEntity($entityName, $id);
        } else {
            $entity = new $configuration['configuration']['class'];
        }

        if (null === $entity) {
            throw $this->createNotFoundException();
        }

        $event = new EntityEvent($entity);
        $this->eventDispatcher->dispatch($event, SunshineEvents::ENTITY_PRE_EDIT);

        $formOptions = [
            'fields_configuration' => $fieldsConfiguration,
            'crud_service' => $this->crudService,
            'em' => $this->em
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

            $this->em->persist($entity);

            $event = new EntityEvent($entity);
            $this->eventDispatcher->dispatch($event, SunshineEvents::ENTITY_PRE_FLUSHED);

            try {
                $this->em->flush();
                $flashMsg = 'Enregistrement effectué.';
                $flashType = 'success';
            } catch (\Exception $e) {
                $flashMsg = "Erreur : {$e->getMessage()}";
                $flashType = 'error';
            }

            $request->getSession()
                ->getFlashBag()
                ->add($flashType, $flashMsg);


            if ($form->has('buttons') && $form->get('buttons')->get('save_and_quit')->isClicked()) {
                return $this->redirectToRoute('sunshine_page_list', ['entityName' => $entityName]);
            }

            return $this->redirectToRoute('sunshine_page_edit', ['entityName' => $entityName, 'id' => $entity->getId()]);
        }

        return $this->render(
            '@TellawSunshineAdmin/Page/edit.html.twig',
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
     * @Route("/page/view/{entityName}/{id}", name="sunshine_page_view", options={"expose"=true}, methods={"GET"})
     * @param $id
     * @param $entityName
     * @return mixed
     * @throws \Exception
     */
    public function viewAction($id, $entityName)
    {
        /** @var MessageBag $messages */
        $messageBag = new MessageBag();
        $messageBag->addMessage("id", $id );
        $messageBag->addMessage("entityName", $entityName);

        return $this->renderPage(strtolower($entityName) . 'View', $messageBag, $messageBag);
    }
}
