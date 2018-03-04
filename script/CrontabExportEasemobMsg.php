<?php
//for($i=0;$i<10;$i++){
$until=time()-86400;
do{
    $data=Easemob::getInstance()->getLatestMsg($cursor);
    if(!$data||!$data['entities']){
        break;
    }
    $cursor=$data['cursor'];
    $fail=0;
    foreach ($data['entities'] as $msgdata){
        if($msgdata['from']=='trade' || $msgdata['from']=='admin'){
            continue;
        }
        $msg=new EasemobMsg();
        $msg->mMsgId=$msgdata['msg_id'];
        $msg->mFrom=$msgdata['from'];
        $msg->mTo=$msgdata['to'];
        $msg->mMsgType=$msgdata['payload']['bodies'][0]['type'];
        $msg->mMsgText=$msgdata['payload']['bodies'][0]['msg'];
        $msg->mSendTime=intval($msgdata['created']/1000);
        $msg->mRawdata=json_encode($msgdata);
        $res=$msg->save();
        //var_dump($res,$msg->getData(),$msgdata);
        //var_dump($res,DB::getLastQuery());
        if(!$res){
            $fail++;
        }
    }
    if($fail>=3){
        break;
    }
    var_dump($cursor,count($data['entities']),$msg->mSendTime);
}while($msg->mSendTime<$until);
