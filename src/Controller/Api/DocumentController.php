<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Hashids\Hashids;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class DocumentController
 * @package App\Controller\Api
 * @Rest\Route("/api/documents")
 */
class DocumentController extends AbstractController
{

    /**
     * @param $hashedId
     * @param Hashids $hashids
     * @Rest\Get("/{hashedId}/pdf")
     * @throws EntityNotFoundException
     */
    public function getPdf($hashedId, Hashids $hashids)
    {
        $id = $hashids->decode($hashedId);
        $doc = $this->getDoctrine()->getRepository('App:Document')->findOneBy(['id' => $id]);
        if(!$doc)
            throw new EntityNotFoundException('Document not found');
        $infos = pathinfo($doc->getPath());
        $pdfPath = $infos['dirname'].'/../pdf/' . $infos['filename']. '.pdf';
        return new BinaryFileResponse($pdfPath);
    }

    /**
     * @Rest\Get("/{hashedId}")
     * @Rest\View(serializerGroups={"simple"})
     */
    public function getDocument($hashedId, Hashids $hashids)
    {
        $id = $hashids->decode($hashedId);
        $doc = $this->getDoctrine()->getRepository('App:Document')->findOneBy(['id' => $id]);
        return $doc;
    }
}
