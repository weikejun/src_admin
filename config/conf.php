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

define("DOMAIN_NAME", $_SERVER['HTTP_HOST']);
define("BASE_URL","http://".DOMAIN_NAME);

# SMS config
define("SMS_SN", "SDK-BBX-010-20083");
define("SMS_PASSWORD", "F39Fe-a5");
define("SMS_BASE_URL", "http://sdk2.zucp.net:8060/webservice.asmx/mt");
SMS::init(SMS_SN, SMS_PASSWORD, SMS_BASE_URL);
# PREPAY RATIO
define("PREPAY_RATIO",0.1);
# MY SITE
define("SITE","http://218.244.144.114:8000");
# ALIPAY INIT CONFIG
# 2014-08-13 删除
#Alipay::init();

define("EASEMOB_ORG","taoshijie");
define("EASEMOB_APP","taoshijie");
define("EASEMOB_CLIENT_ID",'YXA6_7jgUA4xEeSx_T_4UWUjdg');
define("EASEMOB_SECRET",'YXA6ii4s8giagSiVdykhSKxiC7g0q3Q');

# ORDER CONFIG
define("ORDER_PREFIX", "AMZY");
define("ORDER_BASE_NUM", 100100800);

# MAIL CONFIG
define("SMTP_HOST","smtp.exmail.qq.com");
define("SMTP_USERNAME", 'service@aimeizhuyi.com');                 // SMTP username
define("SMTP_PASSWORD", 'amzy1234');                 // SMTP username
