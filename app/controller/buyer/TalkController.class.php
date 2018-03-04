<?php

class TalkController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new BuyerLoginInterceptor());
    }
    public function listAction(){
        $talk=new Talk();
        $talk->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->orderBy("id",'desc')->groupBy(['buyer_id','user_id','stock_id'])->limit(10);
        if($this->_GET('last_id')){
            $talk->addWhere('id',intval($this->_GET('last_id')),'<');
        }
        $talks=$talk->find();
        $user_ids=[];
        $stock_ids=[];
        foreach($talks as $talk){
            $user_ids[]=$talk->mUserId;
            $stock_ids[]=$talk->mStockId;
        }
        $user=new User();
        $userMap=$user->addWhere("id",array_unique($user_ids),"in")->findMap("id");


        $stock=new Stock();
        $stockMap=$stock->addWhere("id",array_unique($stock_ids),"in")->findMap("id");

        $talksData=array_map(function($talk)use($userMap,$stockMap){
            $data=$talk->getData();
            $data['user_name']=$userMap[$talk->mUserId]->mName;
            $data['imgs']=json_decode($stockMap[$talk->mStockId]->mImgs,true);
            $data['stock_name']=$stockMap[$talk->mStockId]->mName;
        },$talks);
        return ['json:',AppUtils::returnValue($talksData,0)];
    }
    public function sendAction(){
        $userId=$this->_POST('user_id',"",10001);
        $stockId=$this->_POST('stock_id',"",10002);
        $msg=$this->_POST('msg',"",10003);
        Talk::sendToUser($user_id,$stock_id,$msg);
        return ['json:',AppUtils::returnValue([],0)];
    }

}
