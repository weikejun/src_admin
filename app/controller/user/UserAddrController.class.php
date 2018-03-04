<?php

class UserAddrController extends AppBaseController{
    public function __construct(){
        $this->addInterceptor(new LoginInterceptor());        
    }
    public function addAction(){
        $userAddr=new UserAddr();
        //首先确认是否已经有地址，如果没有则将firstChoice=1
        $userAddr = $userAddr->addWhere('user_id',User::getCurrentUser()->mId)->find();
        $isFirstChoice = 0;
        if(empty($userAddr)){
            $isFirstChoice = 1;
        }else{
            $isFirstChoice = 0;
        }
        $userAddr = new UserAddr();
        $userAddr->mCountry=$this->_POST("country","");
        $userAddr->mProvince=$this->_POST("province","");
        $userAddr->mCity=$this->_POST("city","");
        $userAddr->mAddr=$this->_POST("addr","",99999);
        $userAddr->mPostcode=$this->_POST("postcode","");
        $userAddr->mName=$this->_POST("name","",99999);
        $userAddr->mPhone=$this->_POST("phone","");
        $userAddr->mCellphone=$this->_POST("cellphone","",99999);
        $userAddr->mEmail=$this->_POST("email","");
        $userAddr->mUserId=User::getCurrentUser()->mId;
        $userAddr->mValid='valid';
        $userAddr->mCreateTime=time();
        $userAddr->mFirstChoice=$isFirstChoice;
        if($userAddr->save()){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['id'=>$userAddr->mId])];
        }else{
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'can\'t save user addr'],99999)];
        }
    }
    public function listAction(){
        $userAddr=new UserAddr();
        $userAddrs=$userAddr->addWhere("user_id",User::getCurrentUser()->mId)->addWhere('valid','valid')->find();
        $userAddrs=array_map(
            function($userAddr){
                return $userAddr->getData();
            },
            $userAddrs
        );
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["addrs"=>$userAddrs])];
    }
    public function updateAction(){
        $id=$this->_POST("id","",99999);
        $userAddr=new UserAddr();
        $userAddr=$userAddr->addWhere("id",$id)->select();
        if(!$userAddr){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'no this user addr'],99999)];
        }
        if(isset($_POST['country'])){
            $userAddr->mCountry=$this->_POST("country","");
        }
        if(isset($_POST['province'])){
            $userAddr->mProvince=$this->_POST("province","");
        }
        if(isset($_POST['city'])){
            $userAddr->mCity=$this->_POST("city","");
        }
        if(isset($_POST['addr'])){
            $userAddr->mAddr=$this->_POST("addr","",99999);
        }
        if(isset($_POST['postcode'])){
            $userAddr->mPostcode=$this->_POST("postcode","");
        }
        if(isset($_POST['name'])){
            $userAddr->mName=$this->_POST("name","",99999);
        }
        if(isset($_POST['phone'])){
            $userAddr->mPhone=$this->_POST("phone","");
        }
        if(isset($_POST['cellphone'])){
            $userAddr->mCellphone=$this->_POST("cellphone","",99999);
        }
        if(isset($_POST['email'])){
            $userAddr->mEmail=$this->_POST("email","");
        }
        $userAddr->mValid='valid';
        $userAddr->mCreateTime=time();
        
        $first_choice=$this->_POST("first_choice",0);
        if($first_choice){
            $tbl=new DBTable('user_addr');
            $tbl->addWhere("user_id",User::getCurrentUser()->mId)->update(["first_choice"=>0]);
            $userAddr->mFirstChoice=1;
        }
        if($userAddr->save()){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['id'=>$userAddr->mId])];
        }else{
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'can\'t save user addr'],99999)];
        }
    }
    public function delAction(){
        $id=$this->_POST("id","",99999);
        $userAddr=new UserAddr();
        $userAddr=$userAddr->addWhere("id",$id)->select();
        if(!$userAddr){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'no thiis user addr'],99999)];
        }
        $userAddr->mValid='invalid';
        if($userAddr->save()){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([])];
        }else{
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'can\'t save user addr'],99999)];
        }
    }

}
