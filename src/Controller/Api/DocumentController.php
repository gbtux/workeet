<?php

namespace App\Controller\Api;

use App\Entity\DocEvenement;
use App\Entity\Partage;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Hashids\Hashids;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DocumentController
 * @package App\Controller\Api
 * @Rest\Route("/api/documents")
 */
class DocumentController extends AbstractController
{
    /**
     * @Rest\Get("/shared")
     * @Rest\View(serializerGroups={"simple"})
     */
    public function getSharedDocuments()
    {
        $groupes = $this->getUser()->getGroups();
        $partages = $this->getDoctrine()->getRepository('App:Partage')->getShared($this->getUser(), $groupes);
        $docs = [];
        $ids = [];
        foreach ($partages as $partage) {
            if(! in_array($partage->getDocument()->getId(), $ids)) {
                $docs[] = $partage->getDocument();
                $ids[] = $partage->getDocument()->getId();
            }
        }
        return $docs;
    }

    /**
     * @param Request $request
     * @param $hashedId
     * @param Hashids $hashids
     * @throws EntityNotFoundException
     * @Rest\Post("/{hashedId}/share")
     * @Rest\View(serializerGroups={"simple"})
     */
    public function addShare(Request $request, $hashedId, Hashids $hashids)
    {
        $id = $hashids->decode($hashedId);
        $doc = $this->getDoctrine()->getRepository('App:Document')->findOneBy(['id' => $id]);
        if(!$doc)
            throw new EntityNotFoundException('Document not found');
        $users = $request->get('users');
        $groups = $request->get('groups');
        $external = $request->get('external');
        $type = $request->get('type');
        $em = $this->getDoctrine()->getManager();
        if($users) {
            foreach ($users as $user) {
                $partage = new Partage();
                $userid = $hashids->decode($user);
                $utilisateur = $this->getDoctrine()->getRepository('App:Utilisateur')->findOneBy(['id' => $userid]);
                $partage->setUtilisateur($utilisateur);
                $partage->setTypePartage($type);
                $partage->setDocument($doc);
                $em->persist($partage);

                $docEvent = new DocEvenement();
                $docEvent->setDocument($doc);
                $docEvent->setUtilisateur($this->getUser());
                $docEvent->setDateEvent(new \DateTime());
                $docEvent->setTypeEvent(DocEvenement::TYPE_EVENEMENT_PARTAGE);
                $docEvent->setDescription(sprintf(" pour l'utilisateur %s (%s)", $utilisateur->getUsername(),$type === Partage::TYPE_PARTAGE_ECRITURE ? "ecriture" : "lecture"));
                $em->persist($docEvent);
            }
        }

        if($groups) {
            foreach ($groups as $group) {
                $partage = new Partage();
                $groupid = $hashids->decode($group);
                $groupe = $this->getDoctrine()->getRepository('App:Groupe')->findOneBy(['id' => $groupid]);
                $partage->setGroupe($groupe);
                $partage->setTypePartage($type);
                $partage->setDocument($doc);
                $em->persist($partage);

                $docEvent = new DocEvenement();
                $docEvent->setDocument($doc);
                $docEvent->setUtilisateur($this->getUser());
                $docEvent->setDateEvent(new \DateTime());
                $docEvent->setTypeEvent(DocEvenement::TYPE_EVENEMENT_PARTAGE);
                $docEvent->setDescription(sprintf("pour le groupe %s (%s)", $groupe->getName(),$type === Partage::TYPE_PARTAGE_ECRITURE ? "ecriture" : "lecture"));
                $em->persist($docEvent);

                //TODO : send a notification / an email to all users in groups
            }
        }

        if($external) {
            $shares = $doc->getPartagesExternes();
            if(null !== $shares) {
                $result = array_merge($shares, $external);
                $doc->setPartagesExternes($result);
            }else{
                $doc->setPartagesExternes($external);
            }

            foreach ($doc->getPartagesExternes() as $share) {
                $docEvent = new DocEvenement();
                $docEvent->setDocument($doc);
                $docEvent->setExternalUser($share);
                $docEvent->setDateEvent(new \DateTime());
                $docEvent->setTypeEvent(DocEvenement::TYPE_EVENEMENT_PARTAGE);
                $docEvent->setDescription(sprintf("pour l'utilisateur externe %s", $share));
                $em->persist($docEvent);

                //TODO : send an email
            }

            $em->persist($doc);
        }
        $em->flush();
        return $doc;
    }

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
     * @param $hashedId
     * @param Hashids $hashids
     * @Rest\Get("/{hashedId}/download")
     * @throws EntityNotFoundException
     */
    public function download($hashedId, Hashids $hashids)
    {
        $id = $hashids->decode($hashedId);
        $doc = $this->getDoctrine()->getRepository('App:Document')->findOneBy(['id' => $id]);
        if(!$doc)
            throw new EntityNotFoundException('Document not found');
        return $this->file($doc->getPath(), $doc->getNom());

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

    /**
     * @param Request $request
     * @param $hashedId
     * @param Hashids $hashids
     * @throws EntityNotFoundException
     * @Rest\Put("/{hashedId}/public")
     * @Rest\View(serializerGroups={"simple"})
     */
    public function updateDocument(Request $request, $hashedId, Hashids $hashids)
    {
        $id = $hashids->decode($hashedId);
        $doc = $this->getDoctrine()->getRepository('App:Document')->findOneBy(['id' => $id]);
        if(!$doc)
            throw new EntityNotFoundException('Document not found');
        $isPublic = $request->request->get('isPublic');
        $doc->setPublic($isPublic);
        $em = $this->getDoctrine()->getManager();
        $em->persist($doc);
        $em->flush();
        return $doc;
    }
}
