<?php
//短信余额报警提醒
//接收人：波什、王小婵
//接收方式：邮件

$url = "http://self.zucp.net/left.aspx";
$refer = "http://self.zucp.net/main.htm";
$agent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36";
//cookie有效期一年，所以目前用来报警基本够用
$cookie = "ASP.NET_SessionId=5ykwf02aytctv2honx0gwil4";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch,CURLOPT_COOKIE, $cookie);

$res = curl_exec($ch);
//var_dump($res); exit;

$m = null;
preg_match('/Label3\"\>(?P<num>\d+)\<\/span/i', $res, $m);
if( isset($m['num']) && $m['num'] > 100000 ) {
    return ;
}

$email = array(
    'title' => '短信余额不足，请及时充值补款',
    'content' => '短信余额还剩'.$m['num'].'条，请及时打款补充余额。如果误报，请及时联系@波什确认。报警时间：'.date('Y-m-d H:i:s'),
    'to' => 'wangxiaochan@aimeizhuyi.com',
    'cc' => array('boshen@aimeizhuyi.com'),
);
//var_dump($email); exit;
EMail::send($email);
