<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/app/{pageId}", name="sunshine_test",requirements={"pageId"=".+"})
     * @Method({"GET", "POST"})
     */
    public function reactAppAction(Request $request)
    {
        $path = $request->getUri();
        $routingItems = explode("/", $path);

        $valueOfBundleRoutingIs = "";

        foreach ($routingItems as $routingItem) {
            if ($routingItem == "app") {
                break;
            }
            $valueOfBundleRoutingIs = $routingItem;
        }

        return $this->render('TellawSunshineAdminBundle:Default:index.html.twig', array(
            "bundlePrefix" => $valueOfBundleRoutingIs
        ));
    }

}
