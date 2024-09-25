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
    if(!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))){

        //check email and password
        $email = $filterAll['email'];
        $password = $filterAll['password'];

        //Check password correct
        $userQuery = get_1Raw("SELECT password, id FROM users WHERE email = '$email'");
        if(!empty($userQuery)){
            $passwordHash = $userQuery['password'];
            $userID = $userQuery['id'];
            if(password_verify($password,$passwordHash)){
                //generate loginToken
                $loginToken = sha1(uniqid().time());
                //Insert in loginToken Table
                $dataInsert =[
                    'userID' => $userID,
                    'token' => $loginToken,
                    'createAt' => date('Y-m-d H:i:s')
                ];
                $insertStatus = insert('loginToken',$dataInsert);
                if($insertStatus){
                    //
                    //Save loginToken in to session
                    setSession('loginToken',$loginToken);   
                }
                redirection('?module=home&action=dashboard');
            }
            else{
                setFlashData('smg', 'Password incorrect');
                setFlashData('smg_type','danger');
            }
        }
        else{
            setFlashData('smg', 'Please Enter Email and Password');
            setFlashData('smg_type','danger');
            
        }
        // echo '<pre>';
        // print_r($userQuery);
        // echo '</pre>';
    }
    else{
        setFlashData('smg', 'Please Enter Email and Password');
        setFlashData('smg_type','danger');
        
    };
    redirection('?module=auth&action=login');
}
$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
?>

<div class="row">
    <div class="col-4" style="margin: 100px auto;">
        <h2 class="text-center text-uppercase">Login User Account</h2>
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
            
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input type="password" class="form-control" placeholder="Password" name="password">
            </div>
            <button type="submit" class="btn btn-primary btn-block mg-btn">Sign In</button> 
            <hr>
            <p class="text-center"> <a href="?module=auth&action=forgot">Fogot Password </a></p>
            <p class="text-center"> <a href="?module=auth&action=register">Sign Up</a></p>
        </form>
    </div>
</div>

<?php
layouts('footer');
?>