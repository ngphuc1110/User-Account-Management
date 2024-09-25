<?php

if(!defined('_LEGIT'))
{
    die('Access Denied');
}
$data = [
    'pageTitle' => 'Register Account'
];
layouts('header', $data);

if(isPost()){
    $filterAll = filter();
    $errors=[]; // Array stored error events 

    //Validate fullname 
    if(empty($filterAll['fullname'])){
        $errors['fullname']['required'] = 'Full Name Required.'; 
    }
    
    //Validate email
    if(empty($filterAll['email'])){
        $errors['email']['required'] = 'Email Required.'; 
    }
    else{
        $email = $filterAll['email'];
        $sql = "SELECT id FROM users WHERE email = '$email'";
        if(getRows($sql) > 0){
            $errors['email']['unique'] = 'Email Already Exsist.';
        }
    }

    //Validate phone number 
    if(empty($filterAll['phone'])){
        $errors['phone']['required'] = 'Phone Number Required.'; 
    }
    else{
        if(!isPhone($filterAll['phone'])){
            $errors['phone']['invalid_phone'] = 'Phone Number Invalid.'; 
        }
    }

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
        $activeToken = sha1(uniqid().time());
        $dataInsert = [
            'fullname' => $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone' => $filterAll['phone'],
            'password' => password_hash($filterAll['password'],PASSWORD_DEFAULT),
            'activeToken' => $activeToken,
            'createAt' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('users',$dataInsert);
        if ($insertStatus){
            
            //link active
            $linkActive = _WEB_HOST .'?module=auth&action=active&token='.$activeToken;

            //setting Mail 
            $subject = '['.$filterAll['fullname'].'] Please confirm your user account!!!';
            $content = 'Hello '.$filterAll['fullname'].',<br>';
            $content .= 'Please click the link to active your user account!!!<br>';
            $content .= $linkActive.'<br>';
            $content .= 'Thank you!';
            
            //send Mail
            $sentMail = sendMail($filterAll['email'],$subject, $content);
            if($sentMail){
                setFlashData('smg','Register success! Check your mail to active your account.');
                setFlashData('smg_type','success');
            }else{
                setFlashData('smg','System error, please try again!');
                setFlashData('smg_type','danger');
            }
        }else{
                setFlashData('smg','Register fail!');
                setFlashData('smg_type','danger');
        }
        
        redirection('?module=auth&action=register');
    }
    else{
        setFlashData('smg','Check your input again!');
        setFlashData('smg_type','danger');
        setFlashData('errors',$errors);
        setFlashData('old',$filterAll);
        redirection('?module=auth&action=register');
    }
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

// echo '<pre>';
// print_r($errors);
// echo '</pre>';

layouts('header', $data);
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
                <label for="">Full Name</label>
                <input name="fullname" type="fullname" class="form-control" placeholder="Full Name" value="<?php 
                    echo oldData('fullname',$old) ?>">
                <?php
                    echo formError('fullname','<span class="error">','</span>',$errors);
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Email Address" value="<?php 
                    echo oldData('email',$old) ?>">
                <?php
                    echo formError('email','<span class="error">','</span>',$errors);
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Phone Number</label>
                <input name="phone" type="number" class="form-control" placeholder="Phone Number" value="<?php 
                    echo oldData('phone',$old) ?>">
                <?php
                    echo formError('phone','<span class="error">','</span>',$errors);
                ?>
            </div>
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
            <button type="submit" class="btn btn-primary btn-block mg-btn">Sign Up</button> 
            <hr>
            <p class="text-center"> <a href="?module=auth&action=login">Sign In Account</a></p>
        </form>
    </div>
</div>

<?php
layouts('footer');
?>