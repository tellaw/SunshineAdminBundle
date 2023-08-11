<?php
namespace Tellaw\SunshineAdminBundle\Service\Widgets;

use Symfony\Component\HttpFoundation\RequestStack;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Tellaw\SunshineAdminBundle\Service\AbstractWidget;

class ViewWidget extends AbstractWidget
{
    public function create($widgetConfiguration, MessageBag $messagebag)
    {
        $entityName = $messagebag->getMessage("entityName");
        $id = $messagebag->getMessage( "id" );
        if ($entityName !== null && $id !== null ) {


            $configuration = $this->entityService->getFormConfiguration($entityName);

            if ( $configuration === null ) {
                throw new \Exception( "Entity not found in configuration" );
            }

            $entity = $this->crudService->getEntity($entityName, $id);
            $template = isset($widgetConfiguration["template"])? $widgetConfiguration["template"] :'@TellawSunshineAdmin/Widget/view';
            return $this->render($template, array(
                    "fields" => $configuration,
                    "entityName" => $entityName,
                    "id" => $id,
                    "entity" => $entity
                )
            );

        }
    }
}
