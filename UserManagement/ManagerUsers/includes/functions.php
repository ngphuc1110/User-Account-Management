<?php

if(!defined('_LEGIT'))
{
    die('Access Denied');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function layouts ($layoutName='header', $data=[]){
    if(file_exists(_WEB_PATH_TEMPLATE.'/layout/'.$layoutName.'.php')){
            require_once(_WEB_PATH_TEMPLATE.'/layout/'.$layoutName.'.php');
    }
}

function sendMail($to, $subject, $content){

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'ilovebesun1996@gmail.com';             //SMTP username 
        $mail->Password   = 'iiwovvscaavymnbs';                     //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('phuc.11102001@gmail.com', 'Phuc');
        $mail->addAddress($to);     //Add a recipient

        //Content
        $mail-> CharSet ="UTF-8";
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;

        //PHPMailer SSL certificate verify failed
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => false
            )
        );

        $sendMail = $mail->send();
        if($sendMail)
        {
            return $sendMail; 
        }
        //echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}

function isGet(){
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        return true;
    }
    return false;
}

function isPost(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        return true;
    }
    return false;
}

// Handles the input data of POST and GET
function filter(){
    $filterArr = [];
    if(isGet()){
        if(!empty($_GET)){
            foreach( $_GET as $key => $value){
                $key = strip_tags($key);
                if(is_array($value)){
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                }
                else{
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
                 
            }
        }
    }

    if(isPost()){
        if(!empty($_POST)){
            foreach( $_POST as $key => $value){
                $key = strip_tags($key);
                if(is_array($value)){
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                }
                else{
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }

    return $filterArr;
}

function isEmail($email){
    $checkEmail = filter_var($email,FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}

function isNumberInt($number){
    $checkNumber = filter_var($number,FILTER_VALIDATE_INT);
    return $checkNumber;
}

function isNumberFloat($number){
    $checkNumber = filter_var($number,FILTER_VALIDATE_FLOAT);
    return $checkNumber;
}

function isPhone($phone){
    if($phone[0]=='0') // first number must be 0
    {
        $phone = substr($phone,1);
        //phone number must be Int and 9 numbers after 0
        if(isNumberInt($phone) && (strlen($phone) == 9)){
            return true;
        }
    }
    return false;
}

function getSmg($smg, $type='success'){
    echo '<div class="alert alert-'.$type.'">';
    echo $smg;
    echo '</div>'; 
}

function redirection($path='index.php'){
    header("Location: $path");
    exit;
}

function formError($fileName, $beforeHTML, $afterHTML, $errors){
    return (!empty($errors[$fileName]))?$beforeHTML.reset($errors[$fileName]).$afterHTML:null;
}

function oldData($filename, $old, $defaut = null){
    return (!empty($old[$filename]))? $old[$filename] : $defaut;
}

function isLogin(){
    //check login status
    $checkLogin = false;
    if(getSession('loginToken')){
        $loginToken = getSession('loginToken');
        //Verify loginToken
        $userQuery = get_1Raw("SELECT userID FROM logintoken WHERE token = '$loginToken'");
        if(!empty($userQuery)){
            $checkLogin = true;
        }
        else{
            removeSession('loginToken');
        }
    }
    return $checkLogin;
}
