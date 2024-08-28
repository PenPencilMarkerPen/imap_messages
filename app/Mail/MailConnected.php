<?php

namespace App\Mail;

require_once('MailMessage.php');

class MailConnected {
    
    private $connection;

    function __construct($mailLogin, $mailPassword, $mailServer, $mailPort)
    {
        $mailImap = "{".$mailServer.":".$mailPort."/imap/ssl}";
        $connection = imap_open($mailImap, $mailLogin, $mailPassword);
        if (!$connection){
            throw new \Exception('Connect err '.imap_last_error());
        }
        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function checkConnection()
    {
        $check = imap_check($this->connection);
        if (!$check)
        {
            return false;
        }
        return true;
    }
    public function getMessageAll(){
        $emails = imap_search($this->connection, 'ALL');
        if (!$emails)
        {
            throw new \Exception('Search err: '.imap_last_error());
        }
        foreach ($emails as &$number)
        {
            $number = $this->getMessageNumber($number);
        }

        return $emails;
    }

    public function getMessageNew(){
        $emails = imap_search($this->connection, 'NEW');
        if (!$emails)
        {
            throw new \Exception('Search err: '.imap_last_error());
        }
        foreach ($emails as &$number)
        {
            $number = $this->getMessageNumber($number);
        }

        return $emails;
    }

    public function closeConnection()
    {
        imap_close($this->connection);
    }

    public function getMessageNumber($number){
        return new MailMessage($this, $number);
    }

}