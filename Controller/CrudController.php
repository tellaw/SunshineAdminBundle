<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tellaw\SunshineAdminBundle\Interfaces\ConfigurationReaderServiceInterface;
use Tellaw\SunshineAdminBundle\Interfaces\ContextInterface;
use Tellaw\SunshineAdminBundle\Interfaces\ContextServiceInterface;
use Tellaw\SunshineAdminBundle\Interfaces\CrudServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Tellaw\SunshineAdminBundle\Service\CrudService;
use Tellaw\SunshineAdminBundle\Services\ConfigurationReaderService;

class CrudController extends AbstractController
{
    /**
     * Remove an entity
     *
     * @Route("/crud/delete/{entityName}/{targetId}", name="sunshine_crud_delete", options={"expose": true})
     * @Route("/crud/delete/{entityName}", name="sunshine_crud_delete_js")
     * @Method({"GET", "POST"})
     * @deprecated
     */
    public function deleteAction($entityName, $targetId, Request $request)
    {
        /* @var $crudService CrudService */
        $crudService = $this->get("sunshine.crud_service");

        if ($crudService->deleteEntity($entityName, $targetId)) {
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Element "'.$targetId.'" supprimé.');
        } else {
            $request->getSession()
                ->getFlashBag()
                ->add('error', "Impossible de supprimer l'élément, vérifiez s'il existe des dépendances bloquant la suppresion");
        }

        return $this->redirectToRoute('sunshine_page_list', array('entityName' => $entityName));
    }



}
