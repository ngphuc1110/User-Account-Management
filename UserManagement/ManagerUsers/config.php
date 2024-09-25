<?php

const _MODULE = 'home';
const _ACTION = 'dashboard';
const _LEGIT = true;

// setting WEB_HOST 
define('_WEB_HOST','http://'. $_SERVER['HTTP_HOST'].'/UserManagement/ManagerUsers');
define('_WEB_HOST_TEMPLATE',_WEB_HOST.'/templates');

// setting WEB_PATH
define('_WEB_PATH', __DIR__);
define('_WEB_PATH_TEMPLATE', _WEB_PATH .'/templates');
define('_WEB_PATH_AUTH', _WEB_PATH .'/auth');

// setting connection 
const _HOST = 'localhost';
const _DB = 'usermanagementdb';
const _USER = 'root';
const _PASSWORD = '';
