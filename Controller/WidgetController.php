<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tellaw\SunshineAdminBundle\Form\Type\FiltersType;
use Tellaw\SunshineAdminBundle\Service\CrudService;
use Tellaw\SunshineAdminBundle\Service\EntityService;
use Tellaw\SunshineAdminBundle\Service\PageService;
use Tellaw\SunshineAdminBundle\Service\WidgetService;

class WidgetController extends AbstractController
{
    protected PageService $pageService;
    protected WidgetService $widgetService;
    protected EntityService $entityService;
    protected CrudService $crudService;

    public function __construct(
        protected FormFactoryInterface $formFactory,
        PageService $pageService,
        WidgetService $widgetService,
        EntityService $entityService,
        CrudService $crudService
    ) {
        $this->pageService = $pageService;
        $this->widgetService = $widgetService;
        $this->entityService = $entityService;
        $this->crudService = $crudService;
    }

    /**
     * List entity in a dataTable ajax loaded bloc
     *
     * @Route("/app/widget/crudlist/{pageName}/{row}/{widgetName}", name="sunshine_widget_crudlist", methods={"GET"})
     * @Route("/app/widget/crudlist/{pageName}/{row}/{widgetName}/{entityName}", name="sunshine_widget_crudlist", methods={"GET"})
     * @return Response
     */
    public function widgetCrudListAction($pageName, $row, $widgetName, $entityName = null)
    {
        $pageConfiguration = $this->pageService->getPageConfiguration($pageName);

        if (!$entityName) {
            if (!isset($pageConfiguration["rows"][$row][$widgetName]["parameters"]["entityName"])) {
                throw new \Exception(
                    "entityName parameter should be configured for widget " . $widgetName . " in row : " . $row
                );
            }
            $entityName = $pageConfiguration["rows"][$row][$widgetName]["parameters"]["entityName"];
        }

        /** @var EntityService $entities */
        $listConfiguration = $this->entityService->getListConfiguration($entityName);

        $filtersConfiguration = $this->entityService->getFiltersConfiguration( $entityName );
        $configuration = $this->entityService->getConfiguration($entityName);

        // Instantiate filters
        // Get Filters Definition
        if ($filtersConfiguration !== null) {
            $formOptions = [
                'fields_configuration' => $filtersConfiguration,
                'crud_service' => $this->crudService
            ];
            $filtersForm = $this->formFactory->create(FiltersType::class, null, $formOptions);
        } else {
            $filtersForm = null;
        }

        return $this->render(
            '@TellawSunshineAdmin/Widget/ajax-datatable.html.twig',
            array(
                "filtersForm" => is_null($filtersForm) ? null : $filtersForm->createView(),
                "configuration" => $configuration,
                "fields" => $listConfiguration,
                "row" => $row,
                "widgetName" => $widgetName,
                "pageName" => $pageName,
                "entityName" => $entityName,
                "widget" => $pageConfiguration["rows"][$row][$widgetName] ?? null,
                "generalSearch" => $configuration['list']['general_search'] ?? null
            )
        );
    }

    /**
     * Shows entity
     *
     * @Route("/app/widget/show/{pageName}/{row}/{widgetName}", name="sunshine_widget_view", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($pageName, $row, $widgetName)
    {
        $pageConfiguration = $this->pageService->getPageConfiguration($pageName);

        if (!isset($pageConfiguration["rows"][$row][$widgetName]["parameters"]["entityName"])) {
            throw new \Exception(
                "entityName parameter should be configured for widget " . $widgetName . " in row : " . $row
            );
        }

        $entityName = $pageConfiguration["rows"][$row][$widgetName]["parameters"]["entityName"];
        $id = $pageConfiguration["rows"][$row][$widgetName]["parameters"]["id"];

        $configuration = $this->entityService->getFormConfiguration($entityName);

        $entity = $this->crudService->getEntity($entityName, $id);

        return $this->render(
            '@TellawSunshineAdmin/Widget/view.html.twig',
            [
                "fields" => $configuration,
                "widgetName" => $widgetName,
                "pageName" => $pageName,
                "entityName" => $entityName,
                "widget" => $pageConfiguration["rows"][$row][$widgetName],
                "entity" => $entity,
            ]
        );
    }

    /**
     * Widget Content
     *
     * @Route("/app/widget/content/{pageId}", name="sunshine_widget_content", methods={"GET"}, options={"expose":true})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function widgetContentAction($pageId)
    {
        if ($pageId === null) {
            throw new \Exception("Page ID cannot be null to render a page");
        }

        /** @var array $page */
        $page = $this->pageService->getPageConfiguration($pageId);

        if ($page === null) {
            throw new \Exception("Page not found : " . $pageId);
        }

        $widgetContent = $this->widgetService->loadServicesWidgetsForPage($page, []);

        return new JsonResponse(json_encode($widgetContent));
    }
}
