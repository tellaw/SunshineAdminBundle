<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Tellaw\SunshineAdminBundle\Interfaces\ConfigurationReaderServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/page/{pageId}", name="sunshine_page")
     * @Method({"GET", "POST"})
     */
    public function indexAction( $pageId )
    {

        // This method intend to load a page configuration and return its JSON

        /* @var $configurationReaderService ConfigurationReaderServiceInterface */
        $configurationReaderService = $this->get("sunshine.configuration-reader_service");

        $pageConfiguration = $configurationReaderService->getPageConfiguration( $pageId );

//dump($pageConfiguration["tellaw_sunshine_admin_entities"]["Page"]);

        // Read Parent
        if ( array_key_exists('parent', $pageConfiguration["tellaw_sunshine_admin_entities"]["Page"]) ) {

            // Merge Parent and Child

            $parentPageConfiguration = $configurationReaderService->getPageConfiguration( $pageConfiguration["tellaw_sunshine_admin_entities"]["Page"]["parent"] );

            $pageConfiguration = array_merge($parentPageConfiguration, $pageConfiguration);

        }

        // Return them with the JSON Response Serialized
        $serializedEntity = $this->container->get('serializer')->serialize($pageConfiguration, 'json');
        $response = new Response();
        $response->setContent($serializedEntity);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Route("/menu", name="sunshine_menu")
     * @Route("/menu/{menuId}", name="sunshine_menu_byId")
     * @Method({"GET", "POST"})
     */
    public function menuAction ( $menuId = 'default') {

        /* @var $configurationReaderService ConfigurationReaderServiceInterface */
        $configurationReaderService = $this->get("sunshine.configuration-reader_service");

        // This method intends to return the configuration of the menu
         $menuConfiguration = $configurationReaderService->getMenuConfiguration( $menuId );

        // Return them with the JSON Response Serialized
        $serializedEntity = $this->container->get('serializer')->serialize($menuConfiguration, 'json');
        $response = new Response();
        $response->setContent($serializedEntity);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

}
