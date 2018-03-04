<?php
$cacheFile=ROOT_PATH."/tmp/sendReminderLive";
$sendLives=file_get_contents($cacheFile);
$sendLives=array_values(array_filter(explode("\n",$sendLives)));
$now=time();
$live=new Live();
$live->addWhere('start_time',$now+300,"<")->addWhere("end_time",$now,">");
foreach($live->iterator() as $_live){
    if(!in_array($_live->mId,$sendLives)){
        process_live_reminder($_live);
        file_put_contents($cacheFile,$_live->mId."\n",FILE_APPEND);
    }
}
function process_live_reminder($live){
    $reminder=new UserReminder();
    $reminder->addWhere("type","before5")->addWhere("model_type","live")
        ->addWhere("model_id",$live->mId)->addWhere("status","not_send");
    foreach($reminder->iterator() as $_reminder){
        $user=new User();
        $user=$user->addWhere("id",$_reminder->mUserId)->select();
        if(!$user){
            continue;
        }
        Easemob::getInstance()->sendMsg($user->mEasemobUsername,"您订阅的直播即将开始了！{$live->mName}");
        Logger::info("send live message {$user->mId} {$user->mName} {$user->mEasemobUsername}");
        $_reminder->mStatus="send";
        $_reminder->save();
    }
}
