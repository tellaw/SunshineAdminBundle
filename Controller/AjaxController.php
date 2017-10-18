<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

        if (isset($request->request->get ("datatable")["sort"]["field"]) && $request->request->get ("datatable")["sort"]["field"] != "") {
            $orderCol = $request->request->get("datatable")["sort"]["field"];
            $orderDir = $request->request->get("datatable")["sort"]["sort"];
        } else {
            $orderCol = "id";
            $orderDir = "asc";
        }

        if (isset($request->request->get ("datatable")["pagination"]["perpage"]) && $request->request->get ("datatable")["pagination"]["perpage"] != "") {
            $paginationLength = $request->request->get ("datatable")["pagination"]["perpage"];
        } else {
            $paginationLength = 10;
        }

        if (isset($request->request->get ("datatable")["pagination"]["page"])) {
            $page = $request->request->get ("datatable")["pagination"]["page"];
        } else {
            $page = 0;
        }

        $paginationStart =  ( $page -1 ) * $paginationLength ;

        //$paginationStart = $request->request->get ("pagination");
        //$paginationLength = $request->request->get ("length");
        if (isset($request->request->get ("datatable")["query"])) {
        $searchValue = $request->request->get ("datatable")["query"]["generalSearch"];
        } else {
            $searchValue = "";
        }
        $filters = [$request->request->get("filters", null)];

        /** @var CrudService $crudService */
        $crudService = $this->get("sunshine.crud_service");
        $list = $crudService->getEntityList($entity, $orderCol, $orderDir, $paginationStart, $paginationLength, $searchValue, true, $filters );

        // Get the number of elements using the filter
        $nbElementsOfFilteredEntity = $crudService->getCountEntityElements( $entity, $orderCol, $orderDir, $paginationStart, $paginationLength, $searchValue, $filters);

        // Get the total number of elements for this entity
        $nbElementsInTable = $crudService->getTotalElementsInTable( $entity );

        $responseArray = array (
            'infos' => array (
                'orderCol' => $orderCol,
                'orderDir' => $orderDir,
                'pagination' => $page,
                'paginationLength' => $paginationLength,
                'searchValue' => $searchValue
            ),
            'meta' => array (
                'page' => $page,
                'pages' => '',
                'perpage' => $paginationLength,
                "total" => $nbElementsOfFilteredEntity
            ),

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

    /**
     * @Route("collection/{entityName}/delete/{id}", name="collection-delete", options={"expose"=true})
     * @Method("DELETE")
     * @param $entityName
     * @param $id
     * @return JsonResponse
     */
    public function deleteOneFromCollectionAction($entityName, $id)
    {
        if (!class_exists($entityName)) {
            throw new NotFoundHttpException($entityName . " : L'entité n'existe pas");
        }

        $status = 404;
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository($entityName);
        $entity = $repository->find($id);
        if ($entity)
        {
            $em->remove($entity);
            $em->flush();
            $status = 200;
        }

        return new JsonResponse(null, $status);
    }

}
