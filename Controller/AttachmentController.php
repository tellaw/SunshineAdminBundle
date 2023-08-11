<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Tellaw\SunshineAdminBundle\Service\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use Tellaw\SunshineAdminBundle\Service\UtilsService;

/**
 * Attachment operations
 */
class AttachmentController extends AbstractController
{
    protected EntityService $entityService;
    protected EntityManagerInterface $em;
    protected UtilsService $utilsService;

    public function __construct(EntityService $entityService, EntityManagerInterface $em, UtilsService $utilsService)
    {
        $this->entityService = $entityService;
        $this->em = $em;
        $this->utilsService = $utilsService;
    }

    /**
     * File download
     *
     * @Route("/download/{entityName}/{id}", name="sunshine_download_attachment", methods={"GET"})
     * @return Response
     */
    public function downloadAction(string $entityName, int $id)
    {
        $config = $this->entityService->getConfiguration($entityName);
        $className = $config['configuration']['class'];
        $attachment = $this->em->getRepository($className)->find($id);

        $file = $this->getParameter('kernel.project_dir') . '/' . $attachment->getPath() . '/' . $attachment->getName();
        if (!file_exists($file)){
            $this->createNotFoundException();
        }

        $fileContent = file_get_contents($file);

        $response = new Response($fileContent);
        $response->headers->set('Content-Type', 'application/octet-stream');

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $this->utilsService->cleanFileName($attachment->getOriginalName())
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
