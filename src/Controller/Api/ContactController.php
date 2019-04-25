<?php

namespace App\Controller\Api;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ContactController
 * @package App\Controller\Api
 * @Rest\Route("/api/contacts")
 */
class ContactController extends AbstractController
{
    /**
     * @Rest\Get("/{id}")
     * @Rest\View(serializerGroups={"simple"})
     */
    public function getContact($id)
    {
        $contact = $this->getDoctrine()->getRepository('App:Contact')->findOneBy(['id' => $id]);
        if(!$contact)
            throw new NotFoundHttpException('Contact not found');
        return $contact;
    }

    /**
     * @Rest\Get("")
     * @Rest\View(serializerGroups={"simple"})
     */
    public function liste()
    {
        $contacts = $this->getDoctrine()->getRepository('App:Contact')->findAll();
        return $contacts;
    }

    /**
     * @param Request $request
     * @return Contact|Response
     * @Rest\Post("")
     * @Rest\View(serializerGroups={"simple"})
     */
    public function create(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->submit($request->request->all());
        if($form->isSubmitted() && $form->isValid()){
            $contact->setCreator($this->getUser());
            $user = $this->getDoctrine()->getRepository('App:Utilisateur')->findOneBy(['email' => $contact->getEmail()]);
            $contact->setUtilisateur($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            return $contact;
        }
        return new Response((string) $form->getErrors(true, false), 500);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Contact|null|object|Response
     * @Rest\Put("/{id}")
     * @Rest\View(serializerGroups={"simple"})
     */
    public function update(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository('App:Contact')->findOneBy(['id' => $id]);
        if(!$contact)
            throw new NotFoundHttpException('Contact not found');
        $form = $this->createForm(ContactType::class, $contact);
        $form->submit($request->request->all());
        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getDoctrine()->getRepository('App:Utilisateur')->findOneBy(['email' => $contact->getEmail()]);
            $contact->setUtilisateur($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            return $contact;
        }
        return new Response((string) $form->getErrors(true, false), 500);
    }

}
