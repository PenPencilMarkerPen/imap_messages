<?php

namespace App\Mail;

class MailAttachment{

    private $filename;
    private $attachment;

    function __construct($filename, $attachment)
    {
        $this->filename = $filename;
        $this->attachment = $attachment;
    }

    public function getFileName()
    {
        return $this->filename;
    }
    
    public function getAttachment()
    {
        return $this->attachment;
    }
}