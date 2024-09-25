<?php

if(!defined('_LEGIT'))
{
    die('Access Denied');
}
$data = [
    'pageTitle' => 'Reset Password'
];
layouts('header', $data);

$token = filter()['token'];
if(!empty($token)){
    $tokenQuery = get_1Raw("SELECT id, fullname, email FROM users WHERE forgotToken = '$token'");
    if(!empty($tokenQuery)){ 
        $userID = $tokenQuery['id'];
        if(isPost()){
            $filterAll = filter();
            $errors = [];

            //Validate password
            if(empty($filterAll['password'])){
                $errors['password']['required'] = 'Password Required.'; 
            }
            else{
                if(strlen($filterAll['password']) < 8){
                    $errors['password']['password_min'] = 'Password must contain at least 8 characters.';
                }
            }

            //Validate password_confirm 
            if(empty($filterAll['password_confirm'])){
                $errors['password_confirm']['required'] = 'You must re-type your Password.'; 
            }
            else{
                if($filterAll['password'] != $filterAll['password_confirm']){
                    $errors['password_confirm']['password_not_match'] = 'Your re-type Password does not match the Password ';
                }
            }

            if(empty($errors)){
                //
                $passwordHash = password_hash($filterAll['password'],PASSWORD_DEFAULT);
                $dataUpdate = [
                    'password' => $passwordHash,
                    'forgotToken' => null,
                    'updateAt' => date('Y-m-d H:i:s')
                ];
                
                $updateStatus = update('users',$dataUpdate,$userID);
                if($updateStatus){
                    setFlashData('smg','Update Password Success !!!');
                    setFlashData('smg_type','sucess');
                    redirection('?module=auth&action=login');
                }
                else{
                    setFlashData('smg','System Error, Try again !!!');
                    setFlashData('smg_type','danger');
                }
            }
            else{
                setFlashData('smg','Check your input again!');
                setFlashData('smg_type','danger');
                setFlashData('errors',$errors);
                redirection('?module=auth&action=reset&token='.$token);
            }
        }
        $smg = getFlashData('smg');
        $smg_type = getFlashData('smg_type');
        $errors = getFlashData('errors');

?>
<div class="row">
    <div class="col-4" style="margin: 100px auto;">
        <h2 class="text-center text-uppercase">Register User Account</h2>
        <?php
        if(!empty($smg)){
            getSmg($smg,$smg_type); 
        }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Password">
                <?php
                    echo formError('password','<span class="error">','</span>',$errors);
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Re-type Password</label>
                <input name="password_confirm" type="password" class="form-control" placeholder="Re-type Password">
                <?php
                    echo formError('password_confirm','<span class="error">','</span>',$errors);
                ?>
            </div>
            <input type="hidden" name ="token" value="<?php echo $token ?>">
            <button type="submit" class="btn btn-primary btn-block mg-btn">SUBMIT</button> 
            <hr>
            <p class="text-center"> <a href="?module=auth&action=login">Sign In Account</a></p>
        </form>
    </div>
</div>

<?php
    }
    else{
        getSmg('Link does not exist or expired !!!','danger');
    }
}
else{
    getSmg('Link does not exist or expired !!!','danger');
}

layouts('footer');
?>