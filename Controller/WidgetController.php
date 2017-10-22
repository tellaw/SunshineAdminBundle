<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tellaw\SunshineAdminBundle\Form\Type\DefaultType;
use Tellaw\SunshineAdminBundle\Form\Type\FiltersType;
use Tellaw\SunshineAdminBundle\Service\CrudService;
use Tellaw\SunshineAdminBundle\Service\EntityService;
use Tellaw\SunshineAdminBundle\Service\PageService;
use Tellaw\SunshineAdminBundle\Service\WidgetService;

class WidgetController extends Controller
{
    /**
     * List entity in a dataTable ajax loaded bloc
     *
     * @Route("/app/widget/crudlist/{pageName}/{row}/{widgetName}", name="sunshine_widget_crudlist")
     * @Route("/app/widget/crudlist/{pageName}/{row}/{widgetName}/{entityName}", name="sunshine_widget_crudlist")
     * @Method({"GET"})
     * @param Request $request
     * @param $pageName
     * @param $row
     * @param $widgetName
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function widgetCrudListAction(Request $request, $pageName, $row, $widgetName, $entityName = null)
    {

        /** @var PageService $pageService */
        $pageService = $this->get("sunshine.pages");
        $crudService = $this->get("sunshine.crud_service");
        $pageConfiguration = $pageService->getPageConfiguration($pageName);

        if (!$entityName) {
            if (!isset($pageConfiguration["rows"][$row][$widgetName]["parameters"]["entityName"])) {
                throw new \Exception(
                    "entityName parameter should be configured for widget " . $widgetName . " in row : " . $row
                );
            }
            $entityName = $pageConfiguration["rows"][$row][$widgetName]["parameters"]["entityName"];
        }

        /** @var EntityService $entities */
        $entities = $this->get("sunshine.entities");
        $listConfiguration = $entities->getListConfiguration($entityName);

        $filtersConfiguration = $entities->getFiltersConfiguration( $entityName );
        // Instantiate filters
        // Get Filters Definition
        $formFactory = $this->get("form.factory");
        if ($filtersConfiguration != null) {
            $formOptions = [
                'fields_configuration' => $filtersConfiguration,
                'crud_service' => $crudService
            ];
            $filtersForm = $formFactory->create(FiltersType::class, null, $formOptions);
        } else {
            $filtersForm = null;
        }

        return $this->render(
            'TellawSunshineAdminBundle:Widget:ajax-datatable.html.twig',
            array(
                "filtersForm" => $filtersForm->createView(),
                "fields" => $listConfiguration,
                "row" => $row,
                "widgetName" => $widgetName,
                "pageName" => $pageName,
                "entityName" => $entityName,
                "widget" => $pageConfiguration["rows"][$row][$widgetName],
            )
        );
    }

    /**
     * Shows entity
     *
     * @Route("/app/widget/show/{pageName}/{row}/{widgetName}", name="sunshine_widget_view")
     * @Method("GET")
     * @param $pageName
     * @param $row
     * @param $widgetName
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @internal param Request $request
     */
    public function viewAction($pageName, $row, $widgetName)
    {
        /** @var PageService $pageService */
        $pageService = $this->get("sunshine.pages");
        $pageConfiguration = $pageService->getPageConfiguration($pageName);

        if (!isset($pageConfiguration["rows"][$row][$widgetName]["parameters"]["entityName"])) {
            throw new \Exception(
                "entityName parameter should be configured for widget " . $widgetName . " in row : " . $row
            );
        }

        $entityName = $pageConfiguration["rows"][$row][$widgetName]["parameters"]["entityName"];
        $id = $pageConfiguration["rows"][$row][$widgetName]["parameters"]["id"];

        /** @var EntityService $entities */
        $entities = $this->get("sunshine.entities");
        $configuration = $entities->getFormConfiguration($entityName);

        /** @var CrudService $entities */
        $crudService = $this->get("sunshine.crud_service");
        $entity = $crudService->getEntity($entityName, $id);

        return $this->render(
            'TellawSunshineAdminBundle:Widget:view.html.twig',
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
     * @Route("/app/widget/content/{pageId}", name="sunshine_widget_content" , options={"expose":true})
     * @Method("GET")
     * @param $pageId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function widgetContentAction($pageId)
    {
        if ($pageId === null) {
            throw new \Exception("Page ID cannot be null to render a page");
        }

        /** @var array $page */
        $page = $this->get("sunshine.pages")->getPageConfiguration($pageId);

        if ($page == null) {
            throw new \Exception("Page not found : " . $pageId);
        }

        /** @var WidgetService $widgetService */
        $widgetService = $this->get("sunshine.widgets");
        $widgetContent = $widgetService->loadServicesWidgetsForPage($page, []);

        return new JsonResponse(json_encode($widgetContent));

    }


}
