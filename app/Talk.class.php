<?php
class Talk extends Base_Talk{
    public static function sendToUser($userId,$stockId,$msg){
        $talk=new self();
        $talk->mBuyerId=Buyer::getCurrentBuyer()->mId;
        $talk->mUserId=$userId;
        $talk->mMsg=substr($msg,0,255);
        $talk->mStockId=$stockId;
        $talk->mSender=1;
        $talk->save();
    }

    public static function sendToBuyer($buyerId,$stockId,$msg){
        $talk=new self();
        $talk->mUserId=User::getCurrentUser()->mId;
        $talk->mBuyerId=$buyerId;
        $talk->mMsg=substr($msg,0,255);
        $talk->mStockId=$stockId;
        $talk->mSender=0;
        $talk->save();
    }

}
