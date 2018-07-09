<?php 
ini_set('session.gc_maxlifetime', 2592000*3);
ini_set('session.cookie_lifetime', 2592000*3);

global $IS_DEBUG;
if (file_exists(ROOT_PATH.'/DEBUG'))
{
    $IS_DEBUG = true;
    ini_set('track_errors', true);
    ini_set("display_errors", "On");
    ini_set('error_reporting', E_ALL & ~E_NOTICE);
    Logger::setLevel(3);
}
else
{
    $IS_DEBUG=false;
    Logger::setLevel(2);
}
date_default_timezone_set('Asia/Shanghai');

DB::init("mysql:host=rm-m5e26k65he0m48ss4.mysql.rds.aliyuncs.com;dbname=src_admin;port=3306;charset=utf8",'root','Wkj@12345678');
if(php_sapi_name()!='cli'){
//    ini_set("session.save_handler", "memcache");  
//    ini_set("session.save_path", "tcp://127.0.0.1:11211");
    session_start();
}
define("LOG_PATH", ROOT_PATH."/log/");
define("PUBLIC_IMAGE_BASE", ROOT_PATH."/webroot/public_upload/");
define("PUBLIC_IMAGE_URI", "/public_upload/");
define("PRIVATE_IMAGE_BASE", ROOT_PATH."/private_upload/");
define("IS_DEBUG", $IS_DEBUG);
define("VERSION", 1);

# Aliyun config
define("ALIYUN_ACCESS_KEY", "LTAI8xlavAUSjQPA");
define("ALIYUN_SECRECT", "7cf79hg7wgR8h794nyGIli2hgC9hLJ");
define("ALIYUN_CAPTCHA_APPKEY", "FFFF0N000000000064AF");
