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

class UploadListener
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
        $document->setNom($file->getFilename());
        $document->setPath($file->getPathname());
        $document->setDateCreation(new \DateTime());
        $document->setRepertoire($repertoire);
        $document->setSize($file->getSize());
        $this->em->persist($document);
        $this->em->flush();
        //if everything went fine
        $response = $event->getResponse();
        $response['success'] = true;
        return $response;
    }

}