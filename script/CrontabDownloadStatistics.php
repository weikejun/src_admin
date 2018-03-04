<?php
//蘑菇街活动 http://m.taoshij.com/download.html?for=mogujie
//微博 http://m.taoshij.com/download.html?for=weibo
//单页夹报广告 http://m.taoshij.com/download.html?for=jiabao
//饮水机水桶 http://m.taoshij.com/download.html?for=water
//易拉宝 http://m.taoshij.com/download.html?for=yilabao
//停车牌 http://m.taoshij.com/download.html?for=parking
//[08/Nov/2014
$nginxDateStr=system("LC_ALL=en_US.UTF-8;/bin/date -d \"1 day ago\" +%d/%b/%Y");
$logFile="/var/log/nginx/access.log.1 /var/log/nginx/access.log";
$stLogFile="/data/logs/st/st.taoshij.com.log";
$sources=[
    "蘑菇街活动"=>"/download.html?for=mogujie",
    "微博"=>"/download.html?for=weibo",
    "单页夹报广告"=>"/download.html?for=jiabao",
    "饮水机水桶"=>"/download.html?for=water",
    "易拉宝"=>"/download.html?for=yilabao",
    "停车牌"=>"/download.html?for=parking",
    "web商品页"=>"/download.html?for=stock",
];
$pv=[];
$pv_android=[];
$pv_ios=[];
$click=[];
$click_android=[];
$click_ios=[];
foreach($sources as $sourceName=>$sourceUrl){
    $count=system("/bin/grep  \"GET $sourceUrl\" $logFile|grep \"$nginxDateStr\"|/usr/bin/wc -l");
    $pv[$sourceName]=$count;
}

//android
foreach($sources as $sourceName=>$sourceUrl){
    $count=system("/bin/grep  \"GET $sourceUrl\" $logFile|grep \"$nginxDateStr\"|grep Android|/usr/bin/wc -l");
    $pv_android[$sourceName]=$count;
}

//ios
foreach($sources as $sourceName=>$sourceUrl){
    $count=system("/bin/grep  \"GET $sourceUrl\" $logFile|grep \"$nginxDateStr\"|grep iOS|/usr/bin/wc -l");
    $pv_ios[$sourceName]=$count;
}


foreach($sources as $sourceName=>$sourceUrl){
    $count=system("/bin/grep  \"$sourceUrl\" $stLogFile|grep \"GET /s.gif?for=\"|grep \"$nginxDateStr\"|/usr/bin/wc -l");
    $click[$sourceName]=$count;
}

foreach($sources as $sourceName=>$sourceUrl){
    $count=system("/bin/grep  \"$sourceUrl\" $stLogFile|grep \"GET /s.gif?for=\"|grep \"$nginxDateStr\"|grep Android|/usr/bin/wc -l");
    $click_android[$sourceName]=$count;
}

foreach($sources as $sourceName=>$sourceUrl){
    $count=system("/bin/grep  \"$sourceUrl\" $stLogFile|grep \"GET /s.gif?for=\"|grep \"$nginxDateStr\"|grep iOS|/usr/bin/wc -l");
    $click_ios[$sourceName]=$count;
}

$smarty=DefaultViewSetting::getTemplateWithSettings();
$smarty->assign("pv",$pv);
$smarty->assign("pv_android",$pv_android);
$smarty->assign("pv_ios",$pv_ios);
$smarty->assign("click",$click);
$smarty->assign("click_android",$click_android);
$smarty->assign("click_ios",$click_ios);
$email_content=$smarty->fetch("email/download.tpl");


EMail::send([
    'title'=>"下载渠道统计 ".date("Y-m-d",time()-86400),     
    'content'=> $email_content,
    'cc'=>"wangpeng@aimeizhuyi.com",
    'to'=>["gaominghui@aimeizhuyi.com","chendandan@aimeizhuyi.com","weikejun@aimeizhuyi.com","huangying@aimeizhuyi.com"],
    //'cc'=>['lengqiying@aimeizhuyi.com','linlin@aimeizhuyi.com'],
]);
