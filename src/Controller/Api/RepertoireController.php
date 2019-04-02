<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class RepertoireController
 * @package App\Controller\Api
 * @Rest\Route("/api/directories")
 */
class RepertoireController extends AbstractController
{
    /**
     * @Rest\Get("/{hash}")
     * @Rest\View(serializerGroups={"simple"})
     */
    public function getRepertoire($hash)
    {
        $rep = $this->getDoctrine()->getRepository('App:Repertoire')->findOneBy(['hash' => $hash]);
        return $rep;
    }
}
