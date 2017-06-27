<?php
namespace Tellaw\SunshineAdminBundle\Service\Widgets;

use Symfony\Component\HttpFoundation\RequestStack;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Tellaw\SunshineAdminBundle\Service\AbstractWidget;

class ViewWidget extends AbstractWidget {

    public function create ( $configuration, MessageBag $messagebag ) {

        $entityName = $messagebag->getMessage("entityName");
        $id = $messagebag->getMessage( "id" );

        if ($entityName != null && $id != null ) {

            /** @var EntityService $entities */
            $entities = $this->get("sunshine.entities");
            $configuration = $entities->getFormConfiguration($entityName);

            if ( $configuration == null ) {
                throw new \Exception( "Entity not found in configuration" );
            }

            /** @var CrudService $entities */
            $crudService = $this->get("sunshine.crud_service");
            $entity = $crudService->getEntity($entityName, $id);

            return $this->render( "TellawSunshineAdminBundle:Widget:view", array(
                    "fields" => $configuration,
                    "entityName" => $entityName,
                    "id" => $id,
                    "entity" => $entity
                )
            );

        }
    }

}
