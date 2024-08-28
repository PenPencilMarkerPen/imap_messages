<?php

session_start();

require_once(__DIR__.'/Mail/Mail.php');
require_once(__DIR__.'/Mail/MailAttachment.php');
require_once(__DIR__.'/Mail/MailMessage.php');
require_once(__DIR__.'/Mail/MailConnected.php');
require_once(__DIR__.'/Validate/Validate.php');


use App\Mail\MailConnected;
use App\Mail\MailAttachment;
use App\Mail\MailMessage;
use App\Mail\Mail;
use App\Validate\Validate;


$dir = __DIR__ . '/../files/';

if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}


function getMailMessages(MailConnected $inbox, $isMessagesAll, $dir)
{
    $array = array();
    try {
        $emails = $isMessagesAll ? $inbox->getMessageNew() : $inbox->getMessageAll();
    }
    catch(Exception $e)
    {
        return $array;
    }
    if (empty($emails))
    {
        return $array;
    }
    foreach ($emails as $email)
    {
        $attachments = $email->getAttachments();
        $attachmentNames = [];
        if (count($attachments) > 0)
        {
            foreach ($attachments as $item)
            {
                $filename = $item->getFileName();
                $attachment = $item->getAttachment();
                $attachmentNames[] = $filename; 
                file_put_contents($dir.$filename, $attachment);
            }
        }
        var_dump($email->getNumber());
        $array[] = new Mail($email->getNumber(), $email->getFromMessage(), $email->getHeader(),$email->getMessage(), $attachmentNames );
    }
    return $array;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $validate = new Validate();
    $isValidation = $validate->validateNotEmpty($_POST);
    if (!$isValidation)
    {
        $_SESSION['err'] = 'Заполните все поля!';
        header('Location: ../app/templates/index.php');
    }

    $login = $_POST['login'];
    $password = $_POST['password'];
    $server = $_POST['server'];
    $port = $_POST['port'];
    $check = isset($_POST['check'])?true:false;

    try {
        $inbox = new MailConnected($login,$password,$server, $port);
    }
    catch (Exception $e)
    {
        $_SESSION['err'] = 'Проверьте правильность введенных данных';
        header('Location: ../app/templates/index.php');
    }
   
    $array = getMailMessages($inbox, $check, $dir);
    $_SESSION['messages'] = serialize($array);
    header('Location: ../app/templates/messages.php');
}

