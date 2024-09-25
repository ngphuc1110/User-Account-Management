<?php
session_start();
require_once('config.php');
require_once('includes/connection.php');

// Library phpMailer
require_once('includes/phpMailer/Exception.php');
require_once('includes/phpMailer/PHPMailer.php');
require_once('includes/phpMailer/SMTP.php');

require_once('includes/functions.php');
require_once('includes/database.php');
require_once('includes/session.php');

// $session_test = setSession('testKey','sessionValue');
// var_dump($session_test);

// setFlashData('testKey','sessionFlashValue');

// echo getFlashData('testKey');

// removeSession('testKey');
// echo getSession('testKey');

//sendMail('phuc.11102001@gmail.com','Test','Nội dung thử nghiệm');

$module = _MODULE;
$action = _ACTION;



if(!empty($_GET['module'])){
    if (is_string($_GET['module']))
    {
        $module = trim($_GET['module']);      
    }
}

if(!empty($_GET['action'])){
    if (is_string($_GET['action']))
    {
        $action = trim($_GET['action']);      
    }
}


$path = 'modules'.'/'.$module.'/'.$action.'.php';

if(file_exists($path)){
    require_once($path);
}
else{
    require_once('modules/error/404.php');
}
?>
