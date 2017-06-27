<?php
namespace Tellaw\SunshineAdminBundle\Service\Widgets;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Tellaw\SunshineAdminBundle\Form\Type\DefaultType;
use Tellaw\SunshineAdminBundle\Service\AbstractWidget;
use Tellaw\SunshineAdminBundle\Service\CrudService;
use Tellaw\SunshineAdminBundle\Service\EntityService;

class EditWidget extends AbstractWidget {

    private $crudService;

    private $entitiesService;

    /** @var FormFactory $formFactory */
    private $formFactory = null;

    public function create ( $configuration, MessageBag $messagebag ) {

        $request = $this->getCurrentRequest();

        $route = $request->get("_route");

        $entityName = $messagebag['entityName'];
        $id = $messagebag['id'];
        $fieldsConfiguration = $this->entitiesService->getFormConfiguration($entityName);
        $entityConfiguration = $this->entitiesService->getConfiguration($entityName);

        if ($id) {
            $entity = $this->crudService->getEntity($entityName, $id);
        } else {
            $entity = new $entityConfiguration['configuration']['class'];
        }

        $formOptions = [
            'fields_configuration' => $fieldsConfiguration,
            'crud_service' => $this->crudService
        ];

        if (!empty($entityConfiguration['form']['formType'])) {
            $form = $this->formFactory->create($entityConfiguration['form']['formType'], $entity, $formOptions);
        } else {
            $form = $this->formFactory->create(DefaultType::class, $entity, $formOptions);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entity = $form->getData();
            $em = $this->getDoctrine();
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

    /**
     * @param CrudService $crudService
     */
    public function setCrudService(CrudService $crudService)
    {
        $this->crudService = $crudService;
    }

    /**
     * @param EntityService $entitiesService
     */
    public function setEntitiesService(EntityService $entitiesService)
    {
        $this->entitiesService = $entitiesService;
    }

    /**
     * @param FormFactory $formFactory
     */
    public function setFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }



}
