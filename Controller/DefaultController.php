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
     * @Route("/page/{pageId}", name="sunshine_page",requirements={"pageId"=".+"})
     * @Method({"GET", "POST"})
     */
    public function indexAction( $pageId )
    {

        // This method intend to load a page configuration and return its JSON

        /* @var $configurationReaderService ConfigurationReaderServiceInterface */
        $configurationReaderService = $this->get("sunshine.configuration-reader_service");

        $pageConfiguration = $configurationReaderService->getPageConfiguration( $pageId );

        // Read Parent
        if ( array_key_exists('parent', $pageConfiguration["tellaw_sunshine_admin_entities"]["page"]) ) {

            // Merge Parent and Child

            $parentPageConfiguration = $configurationReaderService->getPageConfiguration( $pageConfiguration["tellaw_sunshine_admin_entities"]["page"]["parent"] );

            $pageConfiguration = array_merge($parentPageConfiguration, $pageConfiguration);

        }

        if (array_key_exists('tellaw_sunshine_admin_entities',$pageConfiguration)) {
            $pageConfiguration= $pageConfiguration["tellaw_sunshine_admin_entities"]["page"];
        } else {
            $pageConfiguration = null;
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

        if (array_key_exists('tellaw_sunshine_admin_entities',$menuConfiguration)) {
            $menuConfiguration= $menuConfiguration["tellaw_sunshine_admin_entities"]["menu"];
        } else {
            $menuConfiguration = null;
        }

        // Return them with the JSON Response Serialized
        $serializedEntity = $this->container->get('serializer')->serialize($menuConfiguration, 'json');
        $response = new Response();
        $response->setContent($serializedEntity);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Route("/app/{pageId}", name="sunshine_test",requirements={"pageId"=".+"})
     * @Method({"GET", "POST"})
     */
    public function testAction()
    {
        return $this->render('TellawSunshineAdminBundle:Default:index.html.twig');
    }

}
