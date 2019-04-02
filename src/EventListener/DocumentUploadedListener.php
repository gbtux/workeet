<?php


namespace App\EventListener;

use Symfony\Component\EventDispatcher\Event;
use App\EventListener\DocumentEvent;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class DocumentUploadedListener
 * @package App\EventListener
 */
class DocumentUploadedListener
{

    private $pdfPath;

    public function __construct(string $pdfPath)
    {
        $this->pdfPath = $pdfPath;
    }

    /**
     * @param DocumentEvent|Event $event
     */
    public function onDocumentUpload(Event $event)
    {
        $document = $event->getDocument();
        if($document->getExtension() !== "pdf") {
            $path = $document->getPath();
            //libreoffice --convert-to pdf /path/to/file.{doc,docx}
            $process = new Process(['soffice','--convert-to', 'pdf', $path, '--outdir', $this->pdfPath]);
            try {
                $process->mustRun();
                echo $process->getOutput();
            } catch (ProcessFailedException $exception) {
                dump($exception->getMessage());
            }
        }
    }

}