<?php
namespace Tellaw\SunshineAdminBundle\Service\Widgets;

use Symfony\Component\HttpFoundation\RequestStack;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Tellaw\SunshineAdminBundle\Service\AbstractWidget;
use Tellaw\SunshineAdminBundle\Service\CrudService;
use Tellaw\SunshineAdminBundle\Service\EntityService;

class ListWidget extends AbstractWidget {
    /** @var  EntityService */
    private $entities;
    private $crudService;


    public function create ( $configuration, MessageBag $messagebag ) {

        $entityName = $messagebag->getMessage("entityName");

        $listConfiguration = $this->entities->getListConfiguration( $entityName );
        $filtersConfiguration = $this->entities->getFiltersConfiguration( $entityName );
        $configuration = $this->entities->getConfiguration( $entityName );

        // Get Filters Definition
        if ($filtersConfiguration != null) {
            $formOptions = [
                'fields_configuration' => $filtersConfiguration,
                'crud_service' => $this->crudService
            ];

            $filtersForm = $this->formFactory->create(DefaultType::class, null, $formOptions);
        }

        return $this->render(
            'TellawSunshineAdminBundle:Widget:list',
            [
                "extraParameters" => array ("name" => "entityName", "value" => $entityName),
                "filtersForm" => $filtersForm,
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
     * @param mixed $entities
     */
    public function setEntities($entities)
    {
        $this->entities = $entities;
    }

    /**
     * @param mixed $crudService
     */
    public function setCrudService(CrudService $crudService) {
        $this->crudService = $crudService;
    }

    /**
     * @param FormFactory $formFactory
     */
    public function setFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

}
