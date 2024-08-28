<?php

namespace App\Mail;

class Mail {
    
    private $id;
    private $header;
    private $body;
    private $files;
    private $email;

    function __construct($id, $email, $header, $body, $files)
    {
        $this->id = $id;
        $this->email = $email;
        $this->header = $header;
        $this->body = $body;
        $this->files = $files;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function getHeader()
    {
        return $this->header;
    }
    public function getBody()
    {
        return $this->body;
    }
    public function getFiles()
    {
        return $this->files;
    }
}


