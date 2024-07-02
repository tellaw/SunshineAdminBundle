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
use Symfony\Component\Serializer\SerializerInterface;
use Tellaw\SunshineAdminBundle\Service\CrudService;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends AbstractController
{
    protected EntityManagerInterface $em;
    protected CrudService $crudService;
    protected SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $em, CrudService $crudService, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->crudService = $crudService;
        $this->serializer = $serializer;
    }

    /**
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
        $q = $request->request->get ("q") ?? '';
        $page = $request->request->get ("page");
        $callbackFunction = $request->request->get ("callbackFunction");
        $callbackParams = json_decode($request->request->get('callbackParams', '{}'), true);

        if (!$page) {
            $page = 1;
        }

        $itemPerPage = 10;
        $relatedClass = $request->request->get ("relatedClass");

        $metadata = $this->em->getClassMetadata($relatedClass);

        $list = $this->crudService->getEntityListByClassMetadata($relatedClass, $toStringField, $q, $metadata, $page, $itemPerPage, $callbackFunction, $callbackParams);
        $totalCount = $this->crudService->getCountEntityListByClassMetadata($relatedClass, $toStringField, $q, $metadata,$callbackFunction, $callbackParams);

        $responseArray = array (
            "items" => $list,
            "total_count" => $totalCount
        );

        // Return them with the JSON Response Serialized
        $serializedEntity = $this->serializer->serialize($responseArray, 'json', [
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
        if (isset($request->request->all("datatable")["sort"]["field"]) && $request->request->all("datatable")["sort"]["field"] != "") {
            $orderCol = $request->request->all("datatable")["sort"]["field"];
            $orderDir = $request->request->all("datatable")["sort"]["sort"];
        }

        $paginationLength = 10;
        if (isset($request->request->all("datatable")["pagination"]["perpage"]) && $request->request->all("datatable")["pagination"]["perpage"] != "") {
            $paginationLength = $request->request->all("datatable")["pagination"]["perpage"];
        }

        $page = 0;
        if (isset($request->request->all("datatable")["pagination"]["page"])) {
            $page = $request->request->all("datatable")["pagination"]["page"];
        }

        $paginationStart =  ( $page -1 ) * $paginationLength ;
        if ($paginationStart < 0) $paginationStart = 0;

        $searchValue = "";
        if (isset($request->request->all("datatable")["query"])) {
            if (isset($request->request->all("datatable")["query"]["undefined"])) {
                $searchValue = $request->request->all("datatable")["query"]["undefined"];
            }
        }

        $filters = null;

        // Init service, takes care of prefilled filters at page init
        if (isset($request->request->all("datatable")["query"]) && isset($request->request->all("datatable")["query"]["filters"]) && is_array($request->request->all("datatable")["query"]["filters"])) {
            // Preset filters in page
            foreach ( $request->request->all("datatable")["query"]["filters"] as $filter ) {
                $filters[$filter["property"]] = array ("property" => $filter["property"], "value" => $filter["value"]);
            }
        }
        if (isset($request->request->all("datatable")["query"]) && is_array($request->request->all("datatable")["query"])) {

            // Filters set with search button
            foreach ( $request->request->all("datatable")["query"] as $name => $value ) {

                // If key is undefined (genberal search) of filter, then skip this step.
                if ($name != 'undefined' && $name != 'filters')  {

                    // If value is null or empty, it means filter needs to be cleared
                    if (!is_array( $value ) && ($value == null || trim($value) == '')) {

                        // Check if filter is set by init service and unset it.
                        if (null !== $filters && array_key_exists(strtolower($name), $filters)) {
                            unset ($filters[strtolower($name)]);
                        }
                    } else {

                        // Or just set a filter (maybe override init service)
                        $filters[strtolower($name)] = array ("property" => $name, "value" => $value);
                    }
                }
            }
        }

        $list = $this->crudService->getEntityList($entity, $orderCol, $orderDir, $paginationStart, $paginationLength, $searchValue, true, $filters );

        // Get the number of elements using the filter
        $nbElementsOfFilteredEntity = $this->crudService->getCountEntityElements($entity, $searchValue, $filters);

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

        $json = $this->serializer->serialize($responseArray, 'json', [
            'circular_reference_handler' => function ($object) {
                return (method_exists( $object, "__toString" ))? $object->__toString() : null;
            },
        ]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
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
        $repository = $this->em->getRepository($entityName);
        $entity = $repository->find($id);
        if ($entity)
        {
            $this->em->remove($entity);
            $this->em->flush();
            $status = 200;
        }

        return new JsonResponse(null, $status);
    }

}
