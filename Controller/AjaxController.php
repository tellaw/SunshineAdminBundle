<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Tellaw\SunshineAdminBundle\Service\CrudService;
use Tellaw\SunshineAdminBundle\Service\EntityService;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{
    /**
     * @Route("/ajax/list/{entity}", name="sunshine_ajax_list")
     * @Method({"GET"})
     */
    public function ajaxListAction(Request $request, $entity)
    {

        /** @var CrudService $entities */
        $crudService = $this->get ("sunshine.crud_service");
        $list = $crudService->getEntityList ( $entity );

        // Return them with the JSON Response Serialized
        $serializedEntity = $this->container->get('serializer')->serialize(array("data"=>$list), 'json');
        $response = new Response();
        $response->setContent($serializedEntity);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
