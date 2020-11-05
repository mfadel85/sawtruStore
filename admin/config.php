<?php
// HTTP
//error_reporting(E_ERROR | E_PARSE);

//define('HTTP_SERVER', 'http://localhost/store/admin/');
define('IP', '192.168.1.51');

define('HTTP_SERVER', "http://".IP."/store/admin/");
define('HTTP_CATALOG', "http://".IP."/store/");
//define('HTTP_CATALOG', 'http://localhost/store/');

// HTTPS
define('HTTPS_SERVER', "http://".IP."/store/admin/");
//define('HTTPS_SERVER', 'http://localhost/store/admin/');
define('HTTPS_CATALOG',  "http://".IP."/store/");
//define('HTTPS_CATALOG', 'http://localhost/store/');

//define('HTTPS_CATALOG', 'http://localhost/store/');
// DIR
define('DIR_APPLICATION', 'D:\WebServer\htdocs\store\admin/');
define('DIR_SYSTEM', 'D:\WebServer\htdocs\store\system/');
define('DIR_IMAGE', 'D:\WebServer\htdocs\store\image/');
define('DIR_STORAGE', 'D:\WebServer\htdocs\store\storage/');
define('DIR_CATALOG', 'D:\WebServer\htdocs\store\catalog/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'store');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');
// MFH

