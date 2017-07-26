<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Tellaw\SunshineAdminBundle\Service\CrudService;
use Tellaw\SunshineAdminBundle\Service\EntityService;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{

    /**
     *
     * Callback for the SELECT2 Ajax plugin
     *
     * @Route("/ajax/select2/{entityName}/{toStringField}", name="sunshine_ajax_select2_callback")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param $entity
     * @param $page
     */
    public function ajaxSelect2CallbackAction ( Request $request, $entityName, $toStringField ) {

        $q = $request->request->get ("q");
        $page = $request->request->get ("page");
        $callbackFunction = $request->request->get ("callbackFunction");

        if (!$page) {
            $page = 1;
        }

        $itemPerPage = 10;
        $relatedClass = $request->request->get ("relatedClass");

        // Get class metadata
        $doctrine = $this->get("doctrine");
        $em = $doctrine->getManager();
        $metadata = $em->getClassMetadata($relatedClass);

        /** @var CrudService $crudService */
        $crudService = $this->get("sunshine.crud_service");
        $list = $crudService->getEntityListByClassMetadata($relatedClass, $toStringField, $q, $metadata, $page, $itemPerPage,$callbackFunction);
        $totalCount = $crudService->getCountEntityListByClassMetadata($relatedClass, $toStringField, $q, $metadata,$callbackFunction);

        $responseArray = array (
            "items" => $list,
            "total_count" => $totalCount
        );



        // Return them with the JSON Response Serialized
        $serializedEntity = $this->container->get('serializer')->serialize($responseArray, 'json');
        $response = new Response();
        $response->setContent($serializedEntity);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     *
     * CallBack for the Datatable LIST AJAX plugin
     *
     * @Route("/ajax/datatable/{entity}", name="sunshine_ajax_datatable_callback")
     * @Method({"GET", "POST"})
     */
    public function ajaxListCallBackAction ( Request $request, $entity ) {

        $draw = $request->request->get ("draw");
        $orderCol = $request->request->get("order")[0]["column"];
        $orderDir = $request->request->get("order")[0]["dir"];
        $paginationStart = $request->request->get ("start");
        $paginationLength = $request->request->get ("length");
        $searchValue = $request->request->get ("search")["value"];
        $filters = [$request->request->get("filters", null)];
        /** @var CrudService $crudService */
        $crudService = $this->get("sunshine.crud_service");
        $list = $crudService->getEntityList($entity, $orderCol, $orderDir, $paginationStart, $paginationLength, $searchValue, true, $filters );

        // Get the number of elements using the filter
        $nbElementsOfFilteredEntity = $crudService->getCountEntityElements( $entity, $orderCol, $orderDir, $paginationStart, $paginationLength, $searchValue, $filters);

        // Get the total number of elements for this entity
        $nbElementsInTable = $crudService->getTotalElementsInTable( $entity );
        $responseArray = array (
            "draw" => $draw,
            "recordsTotal" => $nbElementsInTable,
            "recordsFiltered" => $nbElementsOfFilteredEntity,
            "data" => $list
        );

        // Return them with the JSON Response Serialized
        return $this->getSerializedResponse( $responseArray );
    }

    private function getSerializedResponse ( $responseArray  ) {

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return (method_exists( $object, "__toString" ))? $object->__toString() : null;
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $serializedEntity = $serializer->serialize($responseArray, 'json');
        $response = new Response();
        $response->setContent($serializedEntity);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
