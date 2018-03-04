<?php
class BuyerVerifyController extends AppBaseController{
    public function __construct(){
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new SystemLogInterceptor());
    }
    public function indexAction(){
        $buyer=new Buyer();
        $buyer_count=$buyer->addWhere("status",'apply')->count();
        $buyer->clear()->orderBy("id")->addWhere("status",'apply');
        if($this->_GET('last_id')){
            $buyer->addWhere("id",intval($this->_GET('last_id')),">");
        }
        $buyer=$buyer->select();
        if(!$buyer){
            $buyer=new Buyer();
            $buyer=$buyer->clear()->orderBy("id")->addWhere("status",'apply')->select();
        }
        return ['cadmin/buyer/verify.tpl',['buyer'=>$buyer,'buyer_count'=>$buyer_count]];
    }
    public function passAction(){
        $id=$this->_POST('id',"",10001);
        $buyer=new Buyer();
        $buyer=$buyer->addWhere("id",$id)->select();
        if($buyer){
            $buyer->mStatus='be';
            $buyer->save();
        }

        return ["redirect:".Utils::get_default_back_url()];
    }

    public function rejectAction(){
        $id=$this->_POST('id',"",10001);
        $buyer=new Buyer();
        $buyer=$buyer->addWhere("id",$id)->select();
        if($buyer){
            $buyer->mStatus='notapply';
            $buyer->mCheckWords=$this->_POST('check_words',"",10002);
            $buyer->save();
        }
        return ["redirect:".Utils::get_default_back_url()];
    }
}
