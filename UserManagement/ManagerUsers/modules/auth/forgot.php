<?php
if(!defined('_LEGIT'))
{
    die('Access Denied');
}
$data = [
    'pageTitle' => 'Login Account'
];

layouts('header', $data);


if(isLogin()){
    redirection('?module=home&action=dashboard');
}

if(isPost()){
    $filterAll = filter();
    if(!empty($filterAll['email'])){
        $email = $filterAll['email'];
        $queryEmail = get_1Raw("SELECT id FROM users WHERE email = '$email'");
        if(!empty($queryEmail)){
            $userID = $queryEmail['id']; 
            $forgotToken = sha1(uniqid().time());
            $dataUpdate = [
                'forgotToken' => $forgotToken,
            ];
            $updateStatus = update('users',$dataUpdate,$userID);
            if($updateStatus){
                //Generate link to reset password
                $linkReset= _WEB_HOST.'?module=auth&action=reset&token='.$forgotToken;

                //Send mail to reset password
                $subject = 'Request to reset Password.';
                $content = 'Hello,<br>';
                $content .= 'We received your password recovery request. Please click on the link to reset your Password: <br>';
                $content .= $linkReset.'<br>';
                $content .= 'Thank you for using our service.'; 
                
                $sendEmail = sendMail($email,$subject,$content);

                if($sendEmail){
                    setFlashData('smg','Message sent please check your Email !!!');
                    setFlashData('smg_type','success');
                }
                else{
                    setFlashData('smg','Send mail fail !!!');
                    setFlashData('smg_type','danger');
                }
            }
            else{
                setFlashData('smg','System Error, Try again !!!');
                setFlashData('smg_type','danger');
            }
        }
        else{
            setFlashData('smg','Email Address not exsits !!!');
            setFlashData('smg_type','danger');
        }
    }
    else{
        setFlashData('smg','Please enter email address !!!');
        setFlashData('smg_type','danger');
    }
}
$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
?>

<div class="row">
    <div class="col-4" style="margin: 100px auto;">
        <h2 class="text-center text-uppercase">Forgot Password</h2>
        <?php
        if(!empty($smg)){
            getSmg($smg,$smg_type); 
        }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input type="email" class="form-control" placeholder="Email Address" name ="email">
            </div>
            <button type="submit" class="btn btn-primary btn-block mg-btn">SEND</button> 
            <hr>
            <p class="text-center"> <a href="?module=auth&action=login">SIGN IN </a></p>
            <p class="text-center"> <a href="?module=auth&action=register">SIGN UP</a></p>
        </form>
    </div>
</div>

<?php
layouts('footer');
?>