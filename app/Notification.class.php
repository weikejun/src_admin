<?php
class Notification{
    public static function sendNotification($user_id,$infos,$isSms=1){
        //TODO
        $user = new User();
        $ret = $user->addWhere("id", $user_id)->select();
        if(!$ret){
            return false;
        }
        Easemob::getInstance()->sendMsg(
            $ret->mEasemobUsername,
            $infos['title'],
            $infos['type']?$infos['type']:"admin",
            $infos['from']?$infos['from']:"admin",
            $infos['data']?$infos['data']:[]
        );
        if($isSms){
            PhoneUtil::sendSMS($user->mPhone, $infos['title']);
        }
        return true;
    }

    public static function sendNotification4Buyer($buyer_id, $infos, $isSms=0) {
        $buyer = new Buyer();
        $ret = $buyer->addWhere("id", $buyer_id)->select();
        if (!$ret) {
            return false;
        }
        Easemob::getInstance()->sendMsg(
            $ret->mEasemobUsername,
            $infos['title'],
            $infos['type']?$infos['type']:"admin",
            $infos['from']?$infos['from']:"admin",
            $infos['data']?$infos['data']:[]
        );
        if ($isSms) {
            PhoneUtil::sendSMS($ret->mPhone, $infos['title']);
        }
        return true;
    }

    /**
     * send ms
     * @param $user_id
     * @param $user_type
     * @param $infos
     * @param $isSms
     */
    public static function sendNotify($user_id,$user_type,$infos,$isSms){
        if($user_type == 1){
            Notification::sendNotification($user_id,$infos,$isSms);
        }else if($user_type == 2){
            Notification::sendNotification4Buyer($user_id,$infos,$isSms);
        }
    }
}
