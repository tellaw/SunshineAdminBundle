<?php
namespace Tellaw\SunshineAdminBundle\Service\Widgets;

use Symfony\Component\HttpFoundation\RequestStack;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Tellaw\SunshineAdminBundle\Service\AbstractWidget;
use Tellaw\SunshineAdminBundle\Service\EntityService;

class ViewWidget extends AbstractWidget {

    /** @var EntityService $entities */
    private $entities;
    private $crudService;


    public function create ( $widgetConfiguration, MessageBag $messagebag )
    {
        $entityName = $messagebag->getMessage("entityName");
        $id = $messagebag->getMessage( "id" );
        if ($entityName != null && $id != null ) {


            $configuration = $this->entities->getFormConfiguration($entityName);

            if ( $configuration === null ) {
                throw new \Exception( "Entity not found in configuration" );
            }

            $entity = $this->crudService->getEntity($entityName, $id);
            $template = isset($widgetConfiguration["template"])? $widgetConfiguration["template"] :"TellawSunshineAdminBundle:Widget:view";
            return $this->render($template, array(
                    "fields" => $configuration,
                    "entityName" => $entityName,
                    "id" => $id,
                    "entity" => $entity
                )
            );

        }
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
    public function setCrudService($crudService) {
        $this->crudService = $crudService;
    }

}
