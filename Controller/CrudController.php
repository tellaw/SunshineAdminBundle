<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Tellaw\SunshineAdminBundle\Service\CrudService;

class CrudController extends AbstractController
{
    /**
     * Remove an entity
     *
     * @Route("/crud/delete/{entityName}/{targetId}", name="sunshine_crud_delete", methods={"GET", "POST"}, options={"expose": true})
     * @Route("/crud/delete/{entityName}", name="sunshine_crud_delete_js", methods={"GET", "POST"})
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
