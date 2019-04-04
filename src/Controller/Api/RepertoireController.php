<?php

namespace App\Controller\Api;

use App\Entity\Repertoire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /**
     * @Rest\Post("/{hash}/sub")
     * @Rest\View(serializerGroups={"simple"})
     */
    public function createSubDirectory(Request $request, $hash)
    {
        $rep = $this->getDoctrine()->getRepository('App:Repertoire')->findOneBy(['hash' => $hash]);
        if(!$rep)
            throw new NotFoundHttpException('Directory not found');
        $nRep = new Repertoire();
        $form = $this->createFormBuilder($nRep, ['csrf_protection' => false])
                ->add('nom', TextType::class)
                ->getForm();
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = uniqid("dir", true);
            $nRep->setHash($hash);
            $nRep->setRepertoireParent($rep);
            $em = $this->getDoctrine()->getManager();
            $em->persist($nRep);
            $em->flush();
            return $nRep;
        }
        return new Response((string) $form->getErrors(true, false), 500);
    }
}
