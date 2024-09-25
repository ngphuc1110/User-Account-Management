<?php

if(!defined('_LEGIT'))
{
    die('Access Denied');
}

$data = [
    'pageTitle' => 'DashBoard'
];

layouts('header2', $data);

if(!isLogin()){
    redirection('?module=auth&action=login');
}
else{
    redirection('?module=users&action=list');
}
?>
<?php
layouts('footer2');
?> 