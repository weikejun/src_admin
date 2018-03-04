<?php
class LoginController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new BuyerLoginInterceptor());
    }
    public function loginAction(){
        $name=$this->_POST('name','','10001');
        $password=$this->_POST("password",'','10002');
        if($this->_POST("use_md5","")!='1'){
            $password=md5($password);
        }
        $buyer=new Buyer();
        $buyer=$buyer->addWhere('name',$name)->addWhere('email',$name,'=','or')->select();
        if(!$buyer||$password!=$buyer->mPassword){
            return ['json:',AppUtils::returnValue(['buyer not found or password error'],10003)];
        }
        $data=$buyer->getData();
        $_SESSION['buyer']=$data;
        unset($data['password']);
        $data['id_pics']=json_decode($data['id_pics'],true);
        $data['favor_brands']=json_decode($data['favor_brands'],true);
        return ['json:',AppUtils::returnValue($data,0)];
    }
    public function checkNameAction(){
        //检查用户名是否被注册了
        $name=$this->_GET('name','','10001');
        $buyer=new Buyer();
        $buyer=$buyer->addWhere('name',$name)->select();
        return ['json:',AppUtils::returnValue([$buyer?1:0],0)];
    }
    public function checkEmailAction(){
        //检查邮箱是否被注册了
        $email=$this->_GET('email','','10001');
        $buyer=new Buyer();
        $buyer=$buyer->addWhere('email',$email)->select();
        return ['json:',AppUtils::returnValue([$buyer?1:0],0)];
    }
    private $easemob_salt='hw%#(d8*]/d';
    public function registerAction(){
        $password=$this->_POST("password",'','10001');
        if($this->_POST("use_md5","")!='1'){
            $password=md5($password);
        }
        //$password=md5($password);
        $name=$this->_POST('name','','10002');
        $email=$this->_POST('email','','10003');
        $buyer=new Buyer();
        $buyer->mEmail=$email;
        $buyer->mPassword=$password;
        $buyer->mName=$name;
        $buyer->mUpdateTime=time();
        $buyer->mCreateTime=time();

        $buyer->mEasemobUsername="buyer_".md5($this->easemob_salt.$buyer->mName);
        $buyer->mEasemobPassword=md5($this->easemob_salt.$buyer->mPassword.time());
        Easemob::getInstance()->createUser($buyer->mEasemobUsername,$buyer->mEasemobPassword);
        
        $ret=$buyer->save();
        if(!$ret){
            return ['json:',AppUtils::returnValue(['name or email not unique'],99999)];
        }
        $buyerData=$buyer->getData();
        $_SESSION['buyer']=$buyerData;
        unset($buyerData['password']);
        return ['json:',AppUtils::returnValue($buyerData,0)];
    }
    public function updatePasswordAction(){
        $password=$this->_POST('password','','10001');
        $old_password=$this->_POST('old_password','','10002');
        if($this->_POST("use_md5","")!='1'){
            $password=md5($password);
            $old_password=md5($old_password);
        }
        $buyer=Buyer::getCurrentBuyer();
        if($buyer->mPassword==$old_password){
            $buyer->mPassword=$password;
            $buyer->mUpdateTime=time();
            $buyer->save();
            $_SESSION['buyer']=$buyer->getData();
            return ['json:',AppUtils::returnValue([],0)];
        }else{
            return ['json:',AppUtils::returnValue(['当前密码错误'],99999)];
        }
        
    }
    public function updateAction(){
        unset($_POST['status']);
        unset($_POST['valid']);
        unset($_POST['id']);
        unset($_POST['password']);
        $buyer=Buyer::getCurrentBuyer();
        $buyer=$buyer->select();
        //$newBuyer=new Buyer();
        if(!$buyer){
            return ['json:',AppUtils::returnValue(['login user is missed.'],10001)];
        }
        if(!in_array($buyer->mStatus,['notapply','reject'])){
            foreach($buyer->getFieldList() as $field){
                //允许修改买手的头像、个人页背景图、签名档三个信息 by boshen@20141201
                if(!in_array($field['name'],['head', 'background_pic', 'background_pic_small', 'signature'])){
                    ///如果已经通过审核，就只能改密码和头像
                    unset($_POST[$field['name']]);
                }
            }
        }
        /*
        if(isset($_POST['password'])&&$_POST['use_md5']=='1'){
            $_POST['password']=md5($_POST['password']);
        }*/
        $buyer->setDataMerge($_POST);
        $buyer->mUpdateTime=time();
        $ret=$buyer->save();
        if(!$ret){
            return ['json:',AppUtils::returnValue(['save error'],99999)];
        }
        $data=$buyer->getData();
        $_SESSION['buyer']=$data;
        return ['json:',AppUtils::returnValue([],0)];
    }
    public function showAction(){
        $buyer=Buyer::getCurrentBuyer();
        $buyer=$buyer->select();
        //$newBuyer=new Buyer();
        if(!$buyer){
            return ['json:',AppUtils::returnValue(['login user is missed.'],10001)];
        }
        $data=$buyer->getData();
        $_SESSION['buyer']=$data;
        $data['id_pics']=json_decode($data['id_pics'],true);
        $data['favor_brands']=json_decode($data['favor_brands'],true);
        unset($data['password']);
        return ['json:',AppUtils::returnValue($data,0)];
    }
    public function uploadIdPicsAction(){
        $paths=FileUtil::uploadFile(isset($_FILES['id_pics_file'])?$_FILES['id_pics_file']:$_POST['id_pics_file'],PUBLIC_IMAGE_BASE,["png",'jpg','jpeg','gif'],PUBLIC_IMAGE_URI);
        if(!$paths){
            return ['json:',AppUtils::returnValue(['upload error'],99999)];
        }
        return ['json:',AppUtils::returnValue($paths,0)];
    }
    public function uploadHeadPicAction(){
        $paths=FileUtil::uploadFile(isset($_FILES['head_file'])?$_FILES['head_file']:$_POST['head_file'],PUBLIC_IMAGE_BASE,["png",'jpg','jpeg','gif'],PUBLIC_IMAGE_URI);
        if(!$paths){
            return ['json:',AppUtils::returnValue(['upload error'],99999)];
        }
        if($this->_POST('update')){
            $buyer=Buyer::getCurrentBuyer();
            $buyer=$buyer->select();
            if(!$buyer){
                return ['json:',AppUtils::returnValue(['login user is missed.'],10001)];
            }
            $buyer->mHead=$paths[0];
            $buyer->mUpdateTime=time();
            $buyer->save();
            $data=$buyer->getData();
            $_SESSION['buyer']=$data;
        }
        return ['json:',AppUtils::returnValue($paths,0)];
    }
    public function applyAction(){
        $buyer=Buyer::getCurrentBuyer();
        $buyer->mStatus='apply';
        $buyer->mUpdateTime=time();
        $ret=$buyer->save();
        $data=$buyer->getData();
        $_SESSION['buyer']=$data;
        if(!$ret){
            return ['json:',AppUtils::returnValue(['save error'],99999)];
        }
        return ['json:',AppUtils::returnValue([],0)];
    }

    public function getBuyerNumAction(){
        $num = Buyer::getBuyerNum();
        $num = $num>0?$num:0;
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($num)];
    }

    public function setDefaultBackgroundPicsAction() {
        $buyer=Buyer::getCurrentBuyer();
        $buyer=$buyer->select();
        $background_pic = '/public_upload/7/3/e/73e05bae969ec779ce4d0365b1a2ddc4.png';
        $background_pic_small = '/public_upload/b/c/1/bc1aec935e92666ebb73846b29b9c8c0.png';

        $buyer->mBackgroundPic = $background_pic;
        $buyer->mBackgroundPicSmall = $background_pic_small;
        $buyer->mUpdateTime = time();
        $ret = $buyer->save();
        $paths = array('background_pic'=>$background_pic, 'background_pic_small'=>$background_pic_small);

        return ['json:',AppUtils::returnValue($paths,0)];
    }
/*
    public function showIdPicAction(){
        //TODO 客户端查看身份证复印件只能用这个接口，会验证查看的人的身份
    }*/

}
