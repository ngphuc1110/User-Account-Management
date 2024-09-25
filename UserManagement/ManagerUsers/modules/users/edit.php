<?php

if(!defined('_LEGIT'))
{
    die('Access Denied');
}
$data = [
    'pageTitle' => 'Register Account'
];
layouts('header', $data);

$filterAll = filter();

if(!empty($filterAll['id'])){
    $userID = $filterAll['id'];
    $userDetail = get_1Raw("SELECT * FROM users WHERE id= '$userID'");
    if(!empty($userDetail)){
        setFlashData('user_detail',$userDetail);
        setFlashData('smg_type','success');
    }
    else{
        redirection('?module=users&action=list');
    }
}

if(isPost()){
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
        $sql = "SELECT id FROM users WHERE email = '$email' AND id <> $userID";
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

    if(!empty($filterAll['password'])){
        //Validate password_confirm 
    if(empty($filterAll['password_confirm'])){
        $errors['password_confirm']['required'] = 'You must re-type your Password.'; 
    }
    else{
        if($filterAll['password'] != $filterAll['password_confirm']){
            $errors['password_confirm']['password_not_match'] = 'Your re-type Password does not match the Password ';
        }
    }
    }

    if(empty($errors)){
        
        $dataUpdate = [
            'fullname' => $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone' => $filterAll['phone'],
            'status' => $filterAll['status'],
            'updateAt' => date('Y-m-d H:i:s')
        ];
        if(!empty($filterAll['password'])){
            $dataUpdate['password'] = password_hash($filterAll['password'],PASSWORD_DEFAULT);
        }

        $condition = $userID;
        echo $condition;
        $updateStatus = update('users',$dataUpdate, $condition);
        // echo '<pre>';
        // print_r($updateStatus);
        // echo '</pre>';
        if ($updateStatus){
                setFlashData('smg','Update Success');
                setFlashData('smg_type','success');               
        }
        else
        {
                setFlashData('smg','System error, please try again!');
                setFlashData('smg_type','danger');
        }  
    }
    else{
        setFlashData('smg','Check your input again!');
        setFlashData('smg_type','danger');
        setFlashData('errors',$errors);
        setFlashData('old',$filterAll);
    }
    redirection('?module=users&action=edit&id='.$userID);
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
$userDetail = getFlashData('user_detail');
if(!empty($userDetail)){
    $old = $userDetail;
}

layouts('header', $data);
?>

<div class="container">
    <div class="row" style="margin: 100px auto;">
        <h2 class="text-center text-uppercase">Update User</h2>
        <?php
        if(!empty($smg)){
            getSmg($smg,$smg_type); 
        }
        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col">
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
                </div>

                <div class="col">
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
            <div class="form-group mg-form">
                <label for="">Status</label>
                <select name="status" id="" class="form-control">
                    <option value="0">Inactivated</option>
                    <option value="1">Actived</option>
                </select>
            </div>
                </div>
            </div>
            <input type="hidden" name="id" value="<?php echo $userID ?>">
            <button type="submit" class="btn btn-primary btn-block mg-btn2">Add User</button> 
            <a href="?module=users&action=list" class="btn btn-success btn-block mg-btn2">Back</a> 
        </form>
    </div>
</div>

<?php
layouts('footer');
?>