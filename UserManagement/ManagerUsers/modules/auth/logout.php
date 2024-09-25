<?php

if(!defined('_LEGIT'))
{
    die('Access Denied');
}

if(isLogin()){
    $token = getSession('loginToken');
    delete('loginToken', "token ='$token'");
    removeSession('loginToken');
    redirection('?module=auth&action=login');
}