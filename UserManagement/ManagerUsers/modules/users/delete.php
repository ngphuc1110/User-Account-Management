<?php

if(!defined('_LEGIT'))
{
    die('Access Denied');
}

$filterAll = filter();
if(!empty($filterAll['id'])){
    $userID = $filterAll['id'];
    $userDetail = getRows("SELECT * FROM users WHERE id = $userID");
    if($userDetail > 0){
        //remove Token from logintoken table
       $deleteToken = delete('logintoken',"userID = $userID");
       if($deleteToken){
        //remove user from users table
        $deleteUser = delete('users',"id = $userID");
        if($deleteUser){
            setFlashData('smg','Remove User Success');
            setFlashData('smg_type','success');
        }
        else{
            setFlashData('smg','Remove User Fail');
            setFlashData('smg_type','danger');
        }
       }
    }
    else{
        setFlashData('smg','Users not exists');
        setFlashData('smg_type','danger');
    }
}
else{
    setFlashData('smg','Link not exists');
    setFlashData('smg_type','danger');
}

redirection('?module=users&action=list');