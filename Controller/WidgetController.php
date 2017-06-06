<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class WidgetController extends Controller
{
    /**
     * @Route("/app/widget/crudlist/{pageName}/{widgetName}", name="sunshine_widget_crudlist")
     * @Method({"GET"})
     */
    public function widgetCrudListAction(Request $request)
    {
        return $this->render('TellawSunshineAdminBundle:Widget:ajax-datatable.html.twig', array());
    }

}
