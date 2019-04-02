<?php


namespace App\EventListener;


use App\Entity\Document;
use Symfony\Component\EventDispatcher\Event;

class DocumentEvent extends Event
{
    /**
     * @var Document
     */
    protected $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function getDocument()
    {
        return $this->document;
    }

}