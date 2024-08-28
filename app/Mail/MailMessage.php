<?php

namespace App\Mail;

require_once('MailConnected.php');
require_once('MailAttachments.php');

use App\Mail\MailConnected;
use App\Mail\MailAttachments;
use Exception;

class MailMessage {

    private $email;
    private $number;
    private $connection;

    function __construct(MailConnected $email, $number)
    {
        $this->email =$email;
        $this->number = $number;
        $this->connection = $email->getConnection();
    }

    private function decodingMessage($encoding, $message)
    {
        switch($encoding) {
            case 0:
            case 1:
            case 2:
                return $message;
            case 3:
                return base64_decode($message);
            case 4:
                return imap_qprint($message);
        }
    }


    private function getPartMessages($structure, $partNumber = '')
    {
        if (!property_exists($structure,"parts"))
        {
            return $this->decodingMessage($structure->encoding, $this->getBody($partNumber ?: 1));
        }

        foreach ($structure->parts as $i=> $part)
        {
            $currentPart = $partNumber ? "$partNumber.".($i+1): ($i+1);
            if ($part->subtype === 'PLAIN' || $part->subtype === 'HTML' ) {
                return $this->decodingMessage($part->encoding, $this->getBody($currentPart));;
            }

            if (property_exists($part, "parts")) {
                $result = $this->getPartMessages($part, $currentPart);
                if ($result) {
                    return $result;
                }
            }
        }
    }


    public function getMessage()
    {
        $message = $this->getPartMessages($this->getStructure());
        return trim($message);
    }

    public function getNumber(){
        return $this->number;
    }
    
    public function getHeader()
    {
        $header = imap_headerinfo( $this->connection, $this->number);
        $header = imap_mime_header_decode($header->subject);
        return $header[0]->text;
    }

    public function getBody($number)
    {
        return imap_fetchbody($this->connection, $this->number, $number);
    }

    public function deleteMessage()
    {
        $delete = imap_delete($this->connection, $this->number);
        if (!$delete)
        {
            throw new \Exception('Delete err: '.imap_last_error());
        }
        else {
            imap_expunge($this->connection);
        }
    }

    public function getStructure()
    {
        $structure = imap_fetchstructure($this->connection, $this->number);
        if (!$structure)
        {
            throw new \Exception('Structure err: '.imap_last_error());
        }
        return $structure;
    }

    public function getFromMessage()
    {
        $email = imap_headerinfo($this->connection, $this->number);
        return $email->from[0]->mailbox.'@'.$email->from[0]->host;
    }
    
    public function __toString()
    {
        return (string)$this->number;
    }

    public function getAttachments()
    {
        return new MailAttachments($this);
    }

}