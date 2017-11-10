<?php
namespace Tellaw\SunshineAdminBundle\Service\Widgets;

use Symfony\Component\Form\FormFactory;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Tellaw\SunshineAdminBundle\Service\AbstractWidget;
use Tellaw\SunshineAdminBundle\Service\CrudService;
use Tellaw\SunshineAdminBundle\Service\EntityService;

class ListWidget extends AbstractWidget {

    /** @var  EntityService */
    private $entities;

    /**
     * @var CrudService
     */
    protected $crudService;

    public function create ( $configuration, MessageBag $messagebag ) {

        $entityName = $messagebag->getMessage("entityName");

        $listConfiguration = $this->entities->getListConfiguration( $entityName );
        $configuration = $this->entities->getConfiguration( $entityName );

        return $this->render(
            'TellawSunshineAdminBundle:Widget:list',
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
