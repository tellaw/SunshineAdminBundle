<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use League\Fractal\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Tellaw\SunshineAdminBundle\Service\CrudService;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends AbstractController
{

    /**
     *
     * Callback for the SELECT2 Ajax plugin
     *
     * @Route("/ajax/select2/{entityName}/{toStringField}", name="sunshine_ajax_select2_callback", methods={"POST"})
     *
     * @param Request $request
     * @param $toStringField
     * @return Response
     */
    public function ajaxSelect2CallbackAction(Request $request, $toStringField)
    {
        $q = $request->request->get ("q");
        $page = $request->request->get ("page");
        $callbackFunction = $request->request->get ("callbackFunction");
        $callbackParams = json_decode($request->request->get('callbackParams', []), true);

        if (!$page) {
            $page = 1;
        }

        $itemPerPage = 10;
        $relatedClass = $request->request->get ("relatedClass");

        // Get class metadata
        $doctrine = $this->get("doctrine");
        /**
         * @var EntityManagerInterface $em
         */
        $em = $doctrine->getManager();
        $metadata = $em->getClassMetadata($relatedClass);

        /** @var CrudService $crudService */
        $crudService = $this->get("sunshine.crud_service");
        $list = $crudService->getEntityListByClassMetadata($relatedClass, $toStringField, $q, $metadata, $page, $itemPerPage, $callbackFunction, $callbackParams);
        $totalCount = $crudService->getCountEntityListByClassMetadata($relatedClass, $toStringField, $q, $metadata,$callbackFunction, $callbackParams);

        $responseArray = array (
            "items" => $list,
            "total_count" => $totalCount
        );

        // Return them with the JSON Response Serialized
        $serializedEntity = $this->container->get('serializer')->serialize($responseArray, 'json', [
            'circular_reference_handler' => function ($object) {
                return (method_exists( $object, "__toString" ))? $object->__toString() : null;
            },
        ]);

        $response = new Response();
        $response->setContent($serializedEntity);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     *
     * CallBack for the Datatable LIST AJAX plugin
     *
     * @Route("/ajax/datatable/{entity}", name="sunshine_ajax_datatable_callback", methods={"GET", "POST"})
     */
    public function ajaxListCallBackAction ( Request $request, $entity ) {

        $orderCol = "id";
        $orderDir = "asc";
        if (isset($request->request->get ("datatable")["sort"]["field"]) && $request->request->get ("datatable")["sort"]["field"] != "") {
            $orderCol = $request->request->get("datatable")["sort"]["field"];
            $orderDir = $request->request->get("datatable")["sort"]["sort"];
        }

        $paginationLength = 10;
        if (isset($request->request->get ("datatable")["pagination"]["perpage"]) && $request->request->get ("datatable")["pagination"]["perpage"] != "") {
            $paginationLength = $request->request->get ("datatable")["pagination"]["perpage"];
        }

        $page = 0;
        if (isset($request->request->get ("datatable")["pagination"]["page"])) {
            $page = $request->request->get ("datatable")["pagination"]["page"];
        }

        $paginationStart =  ( $page -1 ) * $paginationLength ;
        if ($paginationStart < 0) $paginationStart = 0;

        $searchValue = "";
        if (isset($request->request->get ("datatable")["query"])) {
            if (isset($request->request->get ("datatable")["query"]["undefined"])) {
                $searchValue = $request->request->get ("datatable")["query"]["undefined"];
            }
        }

        $filters = null;

        // Init service, takes care of prefilled filters at page init
        if (isset($request->request->get ("datatable")["query"]) && isset($request->request->get ("datatable")["query"]["filters"]) && is_array($request->request->get ("datatable")["query"]["filters"])) {
            // Preset filters in page
            foreach ( $request->request->get ("datatable")["query"]["filters"] as $filter ) {
                $filters[$filter["property"]] = array ("property" => $filter["property"], "value" => $filter["value"]);
            }
        }
        if (isset($request->request->get ("datatable")["query"]) && is_array($request->request->get ("datatable")["query"])) {

            // Filters set with search button
            foreach ( $request->request->get ("datatable")["query"] as $name => $value ) {

                // If key is undefined (genberal search) of filter, then skip this step.
                if ($name != 'undefined' && $name != 'filters')  {

                    // If value is null or empty, it means filter needs to be cleared
                    if (!is_array( $value ) && ($value == null || trim($value) == '')) {

                        // Check if filter is set by init service and unset it.
                        if (array_key_exists(strtolower($name), $filters)) {
                            unset ($filters[strtolower($name)]);
                        }
                    } else {

                        // Or just set a filter (maybe override init service)
                        $filters[strtolower($name)] = array ("property" => $name, "value" => $value);
                    }
                }
            }
        }

        /** @var CrudService $crudService */
        $crudService = $this->get("sunshine.crud_service");
        $list = $crudService->getEntityList($entity, $orderCol, $orderDir, $paginationStart, $paginationLength, $searchValue, true, $filters );

        // Get the number of elements using the filter
        $nbElementsOfFilteredEntity = $crudService->getCountEntityElements($entity, $searchValue, $filters);

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
     * @Route("collection/{entityName}/delete/{id}", name="collection-delete", methods={"DELETE"}, options={"expose"=true})
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
