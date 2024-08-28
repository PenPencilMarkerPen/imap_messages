<?php

namespace App\Mail;

require_once('MailMessage.php');
require_once('MailAttachment.php');

use App\Mail\MailMessage;
use App\Mail\MailAttachment;

use ArrayObject;

class MailAttachments extends ArrayObject{

    private $message;

    public function __construct(MailMessage $message)
    {
        $array = $this->setMessage($message);
        parent::__construct($array);
    }


    private function setMessage(MailMessage $message)
    {
        $this->message = $message;
        return $this->getAttachments($message->getStructure());
    }

    private function decodeAttachment($encoding, $attachment)
    {
        switch($encoding) {
            case 0:
            case 1:
            case 2:
                return $attachment;
            case 3:
                return base64_decode($attachment);
            case 4:
                return imap_qprint($attachment);
        }
    }

    public function getAttachments($structure)
    {
        $attachments = array();

        if (!isset($structure->parts)) {
            return $attachments;
        }

        foreach ($structure->parts as $index => $part)
        {
            if (isset($part->disposition) && strtolower($part->disposition) === "attachment"){
                $filename = imap_mime_header_decode($part->dparameters[0]->value)[0]->text;
                $attachment = $this->decodeAttachment($part->encoding, $this->message->getBody($index+1));
                $attachments[] = new MailAttachment($filename, $attachment);
            }
        }
        return $attachments;
    }


}