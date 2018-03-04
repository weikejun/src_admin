<?php
class UserReminder extends Base_User_Reminder{

    /**
     * 获取直播id的提醒列表
     * @param $liveIdList
     * @return array
     */
    public function getLiveRemindList($liveIdList){
        if(empty($liveIdList)){
            return array();
        }else{
            $reminderList=(new UserReminder())->addWhere("user_id",User::getCurrentUser() ? User::getCurrentUser()->mId :
                $_SESSION['easemob_anonymous']['username'])->addWhere("model_id",$liveIdList,'in')->addWhere("model_type","live")->
                addWhere("type","before5")->findMap('model_id');
            $reminderList = array_map(function($reminder){
                return $reminder->getData();
            },$reminderList);
            return $reminderList;
        }
    }
}
