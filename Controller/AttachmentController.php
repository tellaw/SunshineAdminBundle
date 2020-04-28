<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Attachment operations
 */
class AttachmentController extends AbstractController
{
    /**
     * File download
     *
     * @Route("/download/{entityName}/{id}", name="sunshine_download_attachment", methods={"GET"})
     * @param string $entityName
     * @param int $id
     * @return Response
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function downloadAction(string $entityName, int $id)
    {
        $config = $this->get('sunshine.entities')->getConfiguration($entityName);
        $className = $config['configuration']['class'];
        $attachment = $this->getDoctrine()->getRepository($className)->find($id);

        $file = $this->getParameter('kernel.project_dir') . '/' . $attachment->getPath() . '/' . $attachment->getName();
        if (!file_exists($file)){
            $this->createNotFoundException();
        }

        $utilsService = $this->get('sunshine.utils');

        $fileContent = file_get_contents($file);

        $response = new Response($fileContent);
        $response->headers->set('Content-Type', 'application/octet-stream');

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $utilsService->cleanFileName($attachment->getOriginalName())
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
