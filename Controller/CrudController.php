<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tellaw\SunshineAdminBundle\Interfaces\ConfigurationReaderServiceInterface;
use Tellaw\SunshineAdminBundle\Interfaces\ContextInterface;
use Tellaw\SunshineAdminBundle\Interfaces\ContextServiceInterface;
use Tellaw\SunshineAdminBundle\Interfaces\CrudServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;
use Tellaw\SunshineAdminBundle\Services\ConfigurationReaderService;

class CrudController extends AbstractController
{
    /**
     * @Route("/crud/list/{entityName}", name="sunshine_crud_list_default")
     * @Route("/crud/list/{entityName}/{pageStart}/{length}", name="sunshine_crud_list")
     * @Method({"GET", "POST"})
     *
     * @param string $entityName
     * @param int $pageStart
     * @param int $length
     * @param Request $request
     * 
     * @return Response
     *
     */
    public function listAction( $entityName, $pageStart = 1, $length = 30, Request $request)
    {

        $searchKey = $request->query->get('searchKey', '');
        $filters = $request->query->get('filters', []);
        $orderBy = $request->query->get('orderBy');
        $orderWay = $request->query->get('orderWay', 'ASC');

        // Retrieve context for entity
        /* @var $contextService ContextServiceInterface */
        $contextService = $this->get("sunshine.context_service");
        /* @var $context ContextInterface */
        $context = $contextService->buildEntityListContext($entityName, $length, $pageStart, $searchKey, $filters, $orderBy, $orderWay);

        /* @var $configurationReaderService ConfigurationReaderServiceInterface */
        $configurationReaderService = $this->get("sunshine.configuration-reader_service");
        $headers = $configurationReaderService->getHeaderForLists($context);

        // get using the service the list of items
        /* @var $crudService CrudServiceInterface */
        $crudService = $this->get("sunshine.crud_service");
        $entityList = $crudService->getEntityList($context, $headers);
        $totalCount = count($entityList) < $length ? count($entityList) : $crudService->getEntityListTotalCount($context, $headers);
        //$context->setTotalCount($totalCount);
        $context->setPagination($pageStart, $length, $totalCount);

        // Initiate Response
        $response = array ( "headers" => $headers, "context" => $context, "list" => $entityList);

        // Return them with the JSON Response Serialized
        $serializedEntity = $this->container->get('serializer')->serialize($response, 'json');
        $response = new Response();
        $response->setContent($serializedEntity);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Route("/crud/edit/{entityName}/{targetId}", name="sunshine_crud_edit")
     * @Route("/crud/new/{entityName}", name="sunshine_crud_new")
     * @Method({"GET", "POST"})
     */
    public function editAction( $entityName, $targetId = null) {

        // Retrieve context for entity
        /* @var $contextService ContextServiceInterface */
        $contextService = $this->get("sunshine.context_service");

        /* @var $context ContextInterface */
        $context = $contextService->getContext( $entityName );
        $context->setTargetId( $targetId );

        /* @var $configurationReaderService ConfigurationReaderServiceInterface */
        $configurationReaderService = $this->get("sunshine.configuration-reader_service");
        $headers = $configurationReaderService->getFinalConfigurationForAViewContext( $context, ConfigurationReaderService::VIEW_CONTEXT_FORM );

        /* @var $crudService CrudServiceInterface */
        if ( $targetId != null ) {
            $crudService = $this->get("sunshine.crud_service");
            $object = $crudService->getEntity( $context );

            // Initiate Response for Loading object
            $response = array ( "headers" => $headers, "context" => $context, "object" => $object );

        } else {
            // Initiate Response for new Objects
            $response = array ( "headers" => $headers, "context" => $context );
        }

        // Return them with the JSON Response Serialized
        $serializedEntity = $this->container->get('serializer')->serialize($response, 'json');
        $response = new Response();
        $response->setContent($serializedEntity);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Route("/crud/delete/{entityName}/{targetId}", name="sunshine_crud_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteAction( $targetId ) {

        $crudService = $this->get("sunshine.crud_service");

    }

    /**
     * @Route("/crud/post/{entityName}/{targetId}", name="sunshine_crud_post")
     * @Method({"GET", "POST"})
     */
    public function postAction ( $entityName, $targetId, Request $request ) {

        $data = $request->getContent();
        if ($data != "") {
            $jsonData = json_decode( $data );
        } else {
            throw new \Exception ("Form content is empty, this is impossible");
        }

        // Retrieve context for entity
        /* @var $contextService ContextServiceInterface */
        $contextService = $this->get("sunshine.context_service");

        /* @var $context ContextInterface */
        $context = $contextService->getContext( $entityName );
        $context->setTargetId( $targetId );

        $crudService = $this->get("sunshine.crud_service");

        // Get or Create the object
        if ($context->getTargetId()) {
            $object = $crudService->getEntity( $context );
        } else {
            $object = $crudService->getNewEntity ($context);
        }

        // Populate the Object from React JSON
        $object = $crudService->hydrateEntity ( $context, $object, $jsonData);

        // Validate Object
        // cf : http://symfony.com/doc/current/validation.html
        $validator = $this->get('validator');
        $errors = $validator->validate($object);



        // Return response and / or errors
        $response = array();

        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $response["errors"] = $errors;
            $response["status"] = "nok";
        } else {

            // Save the Entity
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            $response["status"] = "ok";
        }

        $response["object"] = $object;

        // Return them with the JSON Response Serialized
        $serializedEntity = $this->container->get('serializer')->serialize($response, 'json');
        $response = new Response();
        $response->setContent($serializedEntity);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

}
