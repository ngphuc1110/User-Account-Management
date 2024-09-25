<?php

if(!defined('_LEGIT'))
{
    die('Access Denied');
}

$data = [
    'pageTitle' => 'Active Page'
];

layouts('header', $data);

$token = filter()['token'];
if(!empty($token)){
    $tokenQuery = get_1Raw("SELECT id FROM users WHERE activeToken = '$token'");
    if(!empty($tokenQuery)){
        $userID = $tokenQuery['id'];
        $dataUpdate = [
            'status' => 1,
            'activeToken' => null
        ];
        $updateStatus = update('users',$dataUpdate, $userID);
        if($updateStatus){
            setFlashData('smg','Active Success !!!');
            setFlashData("smg_type","success");
        }
        else{
            setFlashData('smg','Active Fail !!!');
            setFlashData("smg_type","danger");
        }
        redirection('?module=auth&action=login');
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



