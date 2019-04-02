<?php
/**
 * Created by PhpStorm.
 * User: gbtux
 * Date: 24/03/19
 * Time: 21:15
 */

namespace App\EventListener;

use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
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
        $this->em->persist($document);
        $this->em->flush();

        $this->dispatcher->dispatch('document.uploaded', new DocumentEvent($document));


        //if everything went fine
        $response = $event->getResponse();
        $response['success'] = true;
        return $response;
    }

}