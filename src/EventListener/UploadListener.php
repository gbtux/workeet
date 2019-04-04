<?php
/**
 * Created by PhpStorm.
 * User: gbtux
 * Date: 24/03/19
 * Time: 21:15
 */

namespace App\EventListener;

use App\Entity\DocEvenement;
use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UploadListener
 * @package App\EventListener
 */
class UploadListener
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param PostPersistEvent $event
     * @return \Oneup\UploaderBundle\Uploader\Response\ResponseInterface
     * @throws \Exception
     */
    public function onUpload(PostPersistEvent $event)
    {
        $directory = $event->getRequest()->headers->all()['directory'][0];
        $repertoire = $this->em->getRepository('App:Repertoire')->findOneBy(['hash' => $directory]);
        if(!$repertoire)
            throw new \Exception('Directory not found');
        $document = new Document();
        $file = $event->getFile();
        $files = $event->getRequest()->files->all();
        $original = $files['file'];
        $document->setNom($original->getClientOriginalName()); //$file->getFilename()
        $document->setPath($file->getPathname());
        $document->setDateCreation(new \DateTime());
        $document->setRepertoire($repertoire);
        $document->setSize($file->getSize());
        $document->setExtension($file->getExtension());
        $document->setAuthor($this->tokenStorage->getToken()->getUser());
        $this->em->persist($document);

        $docEvent = new DocEvenement();
        $docEvent->setDateEvent(new \DateTime());
        $docEvent->setDocument($document);
        $docEvent->setTypeEvent(DocEvenement::TYPE_EVENEMENT_CREATION);
        $docEvent->setUtilisateur($this->tokenStorage->getToken()->getUser());
        $this->em->persist($docEvent);

        $this->em->flush();

        $this->dispatcher->dispatch('document.uploaded', new DocumentEvent($document));


        //if everything went fine
        $response = $event->getResponse();
        $response['success'] = true;
        return $response;
    }

}