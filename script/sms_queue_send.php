<?php

$smsQ = new SmsQueue;
$smsQ = $smsQ->addWhere('status', 0)->orderBy('create_time', 'ASC')->find();

$phoneMap = [];
$contentMap = [];
$ids = [];
$packN = 100;

foreach($smsQ as $i => $sms) {
    $phoneMap[] = $sms->mPhone;
    $contentMap[] = $sms->mContent.'【淘世界】';
    $ids[] = $sms->mId;
    if($i % $packN == $packN - 1 || $i == count($smsQ) - 1) {
        if(SMS::multiSendSMS($phoneMap, $contentMap)) {
            $smsQUpdate = new SmsQueue;
            $smsQUpdate->addWhere('id', $ids, 'IN')->update([
                'status' => [1, DBTable::NO_ESCAPE],
                'send_time' => [time(), DBTable::NO_ESCAPE],
            ]);
        }
        unset($phoneMap);
        unset($contentMap);
        unset($ids);
    }
}

