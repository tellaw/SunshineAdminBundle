<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Tellaw\SunshineAdminBundle\Service\EntityService;
use Tellaw\SunshineAdminBundle\Service\PageService;

class WidgetController extends Controller
{
    /**
     * @Route("/app/widget/crudlist/{pageName}/{row}/{widgetName}", name="sunshine_widget_crudlist")
     * @Method({"GET"})
     */
    public function widgetCrudListAction(Request $request, $pageName, $row, $widgetName)
    {

        /** @var PageService $pageService */
        $pageService = $this->get("sunshine.pages");
        $pageConfiguration = $pageService->getPageConfiguration( $pageName );

        if (!isset( $pageConfiguration["rows"][$row][$widgetName]["parameters"]["entityName"] )) {
            throw new \Exception("entityName parameter should be configured for widget ".$widgetName." in row : ".$row);
        }
        $entityName = $pageConfiguration["rows"][$row][$widgetName]["parameters"]["entityName"];

        /** @var EntityService $entities */
        $entities = $this->get ("sunshine.entities");
        $listConfiguration = $entities->getListConfiguration( $entityName );

        return $this->render('TellawSunshineAdminBundle:Widget:ajax-datatable.html.twig',
            array(  "fields" => $listConfiguration,
                    "widgetName" => $widgetName,
                    "pageName" => $pageName,
                    "entityName" => $entityName,
                    "widget" => $pageConfiguration["rows"][$row][$widgetName]
                    )
        );
    }

}
