<?php
class LiveVerifyController extends AppBaseController{
    public function __construct(){
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new SystemLogInterceptor());
    }
    public function indexAction(){
        
        $live=new Live();
        $live_count=$live->addWhere("status",'verifying')->count();
        $live->clear()->orderBy("id")->addWhere("status",'verifying');
        if($this->_GET('last_id')){
            $live->addWhere("id",intval($this->_GET('last_id')),">");
        }
        $live=$live->select();
        if(!$live){
            $live=new Live();
            $live=$live->clear()->orderBy("id")->addWhere("status",'verifying')->select();
        }
        return ['cadmin/live/verify.tpl',['live'=>$live,'live_count'=>$live_count]];
    }
    public function passAction(){
        $id=$this->_POST('id',"",10001);
        $live=new Live();
        $live=$live->addWhere("id",$id)->select();
        if($live){
            $live->mStatus='verified';
            $live->save();
        }

        return ["redirect:".Utils::get_default_back_url()];
    }

    public function rejectAction(){
        $id=$this->_POST('id',"",10001);
        $live=new Live();
        $live=$live->addWhere("id",$id)->select();
        if($live){
            $live->mStatus='not_verify';
            $live->mCheckWords=$this->_POST('check_words',"",10002);
            $live->save();
        }
        return ["redirect:".Utils::get_default_back_url()];
    }
}

