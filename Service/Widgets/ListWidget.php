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

    /** @var  FormFactory */
    private $formFactory;

    /**
     * @var CrudService
     */
    protected $crudService;

    public function create ( $configuration, MessageBag $messagebag ) {

        $entityName = $messagebag->getMessage("entityName");
        $parameters = $messagebag->getMessage("parameters");
        if ($entityName == null) {
            $entityName = $parameters["entityName"];
        }

        $entityName = $configuration['parameters']['entityName'];
        $filtersConfiguration = $this->entities->getFiltersConfiguration($entityName);

        $filters= $messagebag->getMessage( "parameters" );

        $defaultDatas = array();

        if (array_key_exists("filters", $filters))
        {
            $filters = $filters["filters"];
            foreach ($filters as $filterKey => $filterDetail)
            {

                // Get the related class of the filter
                if (!array_key_exists("relatedClass", $filtersConfiguration[$filterKey])) {throw new \Exception("relatedClass not found while trying to preload value for filter ".$filterKey);}
                $class = $filtersConfiguration[$filterKey]["relatedClass"];

                if (array_key_exists( "field", $filterDetail )) {

                    // Simple filter

                    // Find the data from filters repository
                    if (!array_key_exists("field", $filterDetail) || !array_key_exists("value", $filterDetail)) {throw new \Exception("field and value must be defined to preload value for filter ".$filterKey);}
                    $obj = $this->em->getRepository($class)->findOneBy( array($filterDetail["field"] => $filterDetail["value"]) );

                    // Call the filterAttribute Getter to retrieve value
                    if (!array_key_exists("filterAttribute", $filtersConfiguration[$filterKey])) {throw new \Exception("filterAttribute Class not found while trying to preload value for filter ".$filterKey);}
                    $value = $obj->{"get".ucfirst($filtersConfiguration[$filterKey]["filterAttribute"])}();

                    // Set value in the form.
                    $filtersConfiguration[$filterKey]['value']['arguments'] = array($value => $obj->getId());
                    $defaultDatas[$filterKey] = array($value => $obj->getId());

                } else {

                    // Repository and Method Filter
                    $repository = $this->em->getRepository($class);

                    if ( !method_exists( $repository, $filterDetail["method"]  )) {throw new \Exception("Method (".$filterDetail["method"].") in repository doesn't exists while trying to preload filter (".$filterKey.") as described in YAML");}
                    $obj = $repository->{$filterDetail["method"]}( $filterKey, $filtersConfiguration );

                    $filtersConfiguration[$filterKey]['value']['arguments'] = $obj;
                    $defaultDatas[$filterKey] = $obj;

                }
            }
        }

        $filtersConfiguration["disableLoadingValues"] = true;

        if ($filtersConfiguration !== null) {

            $formOptions = [
                'fields_configuration' => $filtersConfiguration,
                'crud_service' => $this->crudService,
                'empty_data' => $defaultDatas
            ];

            $filtersForm = $this->formFactory->create(  \Tellaw\SunshineAdminBundle\Form\Type\FiltersType::class,
                                                        null,
                                                        $formOptions
            );

        } else {
            $filtersForm = null;
        }

        return $this->render(
            '@sunshine/Widget/ajax-datatable',
            [
                "filtersForm" => !empty($filtersForm) ? $filtersForm->createView() : null,
                "configuration" => $configuration,
                "fields" => $this->entities->getListConfiguration($entityName),
                "row" => '12',
                'widgetName' => "list".$entityName,
                'entityName' => $entityName,
                'widget' => $configuration,
                'generalSearch' => isset($parameters["searchKey"]) ? $parameters["searchKey"] : "",
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
