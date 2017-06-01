<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Content pages management
 */
class PageController extends Controller
{
    /**
     * Expose page configuration
     *
     * @Route("/page/{id}", name="sunshine_page", requirements={"id"=".+"})
     * @Method({"GET"})
     *
     * @param string $id Identifier of the required page
     * @return JsonResponse
     */
    public function showAction($id)
    {
        $configuration = $this->get("sunshine.pages")->getPageConfiguration($id);

        return new JsonResponse($configuration);
    }
}
