<?php

class UserController extends AppBaseController{
    public function __construct(){
        $this->addInterceptor(new LoginInterceptor());        
    }
    public function loginAction(){
        if(is_null($this->_POST('name')) && is_null($this->_POST('phone'))){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(null,10006)];
        }
        $password=$this->_POST('password',"",10012);
        if($this->_POST("use_md5","")!='1'){
            $password=md5($password);
        }
        $user=new User();
        $phone=$this->_POST('phone');
        $ret=null;
        if($phone){
            $ret=$user->addWhere("phone",$phone)->addWhere('valid','valid')->addWhere('phone_verified', 1)->select();
        }else{
            $name=$this->_POST('name');
            $ret=$user->addWhere("name",$name)->addWhere('valid','valid')->addWhere('phone_verified', 1)->select();
        }
        if(!$ret){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(null,10006)];
        }
        #if(is_null($password) || $user->mPassword!=md5($password)){
        if(is_null($password) || $user->mPassword!=$password){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(null,10003)];
        }
        # set session
        $_SESSION['user']=$user->getData();
        #return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($user->getData())];
        $data = $user->getData();
        $data['country_flag']=NationalFlag::getUrl($data['country']);
        unset($data['password']);
        $couponNum = Coupon::getCouponNumByUid($data['id']);
        $data['coupon_num'] = $couponNum;
        if($this->_POST('os') == 'android') {
            $data = ['user' => $data];
        }
        //登录送代金券(只送一次)
        Coupon::loginLottery();
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($data)];
    }

    private $easemob_salt='hw%#(d86sd{]/d';

    public function registerAction(){
        $phone=$this->_POST('phone',"",10011);
        if(!UserVerify::isValidPhone($phone)){
            //不是手机号
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'not valid phone'], 10025)];
        }
        $phone = UserVerify::toShortPhoneNum($phone);
        $user=new User();
        $ret=$user->addWhere("phone",$phone)->addWhere('valid','valid')->select();
        if(!$ret){
            $user->mPhone=$phone;
            $user->mPhoneVerified=0;
            $user->mEmailVerified=0;
            if(isset($_SESSION['easemob_anonymous'])){
                //之前有过匿名聊天，继续使用匿名时期的聊天账号
                $user->mEasemobUsername=$_SESSION['easemob_anonymous']['username'];
                $user->mEasemobPassword=$_SESSION['easemob_anonymous']['password'];
                $anonymous=new EasemobAnonymous();
                $anonymous->addWhere('session_id',session_id())->delete();
                unset($_SESSION['easemob_anonymous']);
            }else{
                $user->mEasemobUsername="user_".md5($this->easemob_salt."$phone".mt_rand(5, 100));
                $user->mEasemobPassword=md5($this->easemob_salt.session_id().time());
                Easemob::getInstance()->createUser($user->mEasemobUsername,$user->mEasemobPassword);
            }
            $user->mCreateTime = time();
            $user->mLastUpdateTime = time();
            $user->mEmail = '';
            $user->mAvatarUrl = '/winphp/metronic/media/image/avatar.png';
            $user->mSource = $_SERVER['HTTP_USER_AGENT'];
            $ret2=$user->save();
            if(!$ret2){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['can\'t insert user'],99999)];
            }
            //TODO 暂时注视掉by @boshen，等到新版找回密码开放之后再封掉
        //} else {
            //return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['phone has used'], 10032)];
        }

        # 发送短信验证码
        #$code = UserVerify::sendPhoneVerify($user);
        $result = UserVerify::sendPhoneVerify($user);
        $success = $result['success'];
        if(!$success){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($result)];
        }
        # set session
        $_SESSION['user']=$user->getData();
        # 返回验证码，客户端可以用来自动填充
        #return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['code'=>$code])];
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($result)];
    }

    private function _domobResponse($idfa) {
        $appid = '905480305';
        $domobUrl = 'http://e.domob.cn/track/ow/api/postback?appId='.$appid.'&';
        $domobKey = '266d3f3b90a5771ed6b3a2d3526a397f';
        $pr = new PromoteChannel;
        $pr = $pr->addWhere('ifa', $idfa)->addWhere('appid', $appid)->addWhere('active_time', 0, '>')->select();
        if($pr) { // 已进行过上报, 不再重复上报
        } else {
            $pr = new PromoteChannel;
            $pr = $pr->addWhere('ifa', $idfa)->addWhere('appid', $appid)->select();
            if($pr) { // 正常上报
                $pr->mActiveTime = time();
                $pr->mActiveIp = Utils::getClientIP();
                $pr->save();
                $domobUrl .= implode('&', [
                    "udid=$pr->mMac",
                    //"ma=".($mac = (strpos($pr->mMac, ":") ? md5($pr->mMac) : $pr->mMac)),
                    "ifa=$pr->mIfa",
                    //"oid=$pr->mOid",
                    "sign=".md5(sprintf("%s,%s,%s,%s,%s,%s", $appid, $pr->mMac, '', $pr->mIfa, '', $domobKey)),
                    "clktime=$pr->mClickTime",
                    "acttime=$pr->mActiveTime",
                    "ip=$pr->mActiveIp",
                    "appVersion=2.2",
                ]);
                $randSeed = rand(0, 99);
                if($randSeed < 100) {
                    $res = Utils::curlGet($domobUrl);
                }
            } else {} // 设备点击记录不存在
        }
    }

    public function resendPhoneVerifyAction(){
        $user=User::getCurrentUser();
        $ret=UserVerify::sendPhoneVerify($user);
        if(!$ret){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($ret, 10022)];
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($ret)];
    }
    ////和resendRegisterVerifyAction/registerAction是一对
    public function confirmRegisterVerifyAction(){
        $user=User::getCurrentUser();
        $ret=UserVerify::receivePhoneVerify($user);
        if(!$ret){
            //验证失败
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'varify fail'],99999)];
        }
        $user->mPhoneVerified=1;
        if($user->save()){
            # return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'user has been phoneVerified']),10024];
            $_SESSION['user']=$user->getData();
        }
        #return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'varify success'])];
        $data = $user->getData();
        # 接口中隐藏password
        #$data['password'] = null;
        $register = ($data['name'] && $data['password']) ? 1 : 0;
        unset($data['password']);
        $data['country_flag']=NationalFlag::getUrl($data['country']);
        $couponNum = Coupon::getCouponNumByUid($data['id']);
        $data['coupon_num'] = $couponNum;
        if($_SESSION['promote_idfa']) {
            $promote = new PromoteChannel;
            $promote->addWhere('ifa', $_SESSION['promote_idfa'])->update(['user_id'=>$user->mId]);
            $this->_domobResponse($_SESSION['promote_idfa']);
            unset($_SESSION['promote_idfa']);
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['user'=>$data,'register'=>$register])];
    }
    
    //需要么？用来改用户手机号
    public function changePhoneAction(){
        $user=User::getCurrentUser();
        $password=$this->_POST('password',"",10012);
        if($this->_POST("use_md5","")!='1'){
            $password=md5($password);
        }
        $new_phone=$this->_POST('new_phone',"",99999);
        if(!$user->mPassword==$password){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],99999)];
        }
        $_SESSION['changePhone']=['new_phone'=>$new_phone];
        #UserVerify::sendPhoneVerify($user);
        $ret=UserVerify::sendPhoneVerify($user);
        if(!$ret){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([], 10021)];
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([])];
    }
    //和reVerifyAction是一对，提出修改手机号之后，在老手机上确认，新手机就生效
    public function confirmChangePhoneAction(){
        $user=User::getCurrentUser();
        UserVerify::receivePhoneVerify($user);
        if(!$_SESSION['changePhone']){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'nothing to confirm'],99999)];
        }
        $user->mPhone=$_SESSION['changePhone']['new_phone'];
        $user->save();
        # update session info
        $_SESSION['user']=$user->getData();
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([])];
    }
    
    public function updatePasswordAction(){
        $password=$this->_POST('password',"",10012);
        $new_password=$this->_POST('new_password',"",10012);
        if($this->_POST("use_md5","")!='1'){
            $password=md5($password);
            $new_password=md5($new_password);
        }
        $user=User::getCurrentUser();
        if($user->mPassword!=$password){
            //验证失败
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'old password wrong'],99999)];
        }
        if(!UserVerify::isValidPassword($new_password)){
            //密码不够强
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'new password not strong enough'],'10028')];
        }
        $user->mPassword=$new_password;
        if(!$user->save()){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'can\'t save user']),99999];
        }
        # update session info
        $_SESSION['user']=$user->getData();
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([])];
    }

    public function updateAction(){
        $user=User::getCurrentUser();
        if(!is_null($this->_POST('name'))){
            if(!$user->mName){
                $user->mName=$this->_POST('name');
            }
        }
        if(!is_null($this->_POST('password'))){
            $password=$this->_POST('password');
            if($this->_POST("use_md5","")!='1'){
                $password=md5($password);
            }
            if(!UserVerify::isValidPassword($password)){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'new password not strong enough'],'10028')];
            }
            $user->mPassword=$password;
        }
        if(!is_null($this->_POST('phone1'))){
            $user->mPhone1=$this->_POST('phone1');
        }
        if(!is_null($this->_POST('address'))){
            $user->mAddress=$this->_POST('address');
        }
        if(!is_null($this->_POST('email'))){
            $user->mEmail=$this->_POST('email');
        }
        if(!is_null($this->_POST('nick'))){
            $user->mNick=$this->_POST('nick');
        }
        if(!is_null($this->_POST('city'))){
            $user->mCity=$this->_POST('city');
        }
        if(!is_null($this->_POST('province'))){
            $user->mProvince=$this->_POST('province');
        }
        if(!is_null($this->_POST('country'))){
            $user->mCountry=$this->_POST('province');
        }
        if(!is_null($this->_POST('gender'))){
            $user->mGender=$this->_POST('gender');
        }
        if(!is_null($this->_POST('birthday'))){
            $user->mBirthday=$this->_POST('birthday');
        }
        if(!is_null($this->_POST('married'))){
            $user->mMarried=$this->_POST('married');
        }
        if(!is_null($this->_POST('year_income'))){
            $user->mYearIncome=$this->_POST('year_income');
        }
        if(!is_null($this->_POST('interests'))){
            $user->mInterests=$this->_POST('interests');
        }
        if(!is_null($this->_POST('id_num'))){
            $user->mIdNum=$this->_POST('id_num');
        }
        $user->mLastUpdateTime=time();
        $ret = $user->save();
        if(!$ret){
            $userEx = new User;
            $userEx = $userEx->addWhere('name', $user->mName)->select();
            if($userEx) {
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'can\'t save user'],'10029')];
            } else {
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'can\'t save user'],'10026')];
            }
        }
        # update session info
        $_SESSION['user']=$user->getData();
        $data = $user->getData();
        $data['country_flag']=NationalFlag::getUrl($data['country']);
        unset($data['password']);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'update success','user'=>$data])];
    }

    public function uploadHeadAction(){
        $paths=FileUtil::uploadFile(isset($_FILES['avatar'])?$_FILES['avatar']:$_POST['avatar'],PUBLIC_IMAGE_BASE,["png",'jpg','jpeg','gif'],PUBLIC_IMAGE_URI);
        $user=User::getCurrentUser();
        $user->mAvatarUrl=$paths[0];
        $user->mLastUpdateTime=time();
        if(!$user->save()){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'can\'t save user'],99999)];
        }
        # update session info
        $_SESSION['user']=$user->getData();
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'upload head success', 'pic'=>$user->mAvatarUrl])];
    }

    public function personalCenterAction(){
        $user=User::getCurrentUser();
        # update session info
        $_SESSION['user']=$user->getData();
        $data = $user->getData();
        $data['country_flag']=NationalFlag::getUrl($data['country']);
        unset($data['password']);
        $couponNum = Coupon::getCouponNumByUid($data['id']);
        $data['coupon_num'] = $couponNum;
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['user'=>$data])];
    }

    public function logoutAction() {
        $_SESSION = [];
        session_unset();
        session_destroy();
        setcookie(session_name(), null, -1);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([], 0)];
    }

    //绑定手机号 已登录状态下操作
    // by boshen@20141212
    public function bindPhoneAction(){
        $phone=$this->_POST('phone',"",10011);
        if(!UserVerify::isValidPhone($phone)){
            //不是手机号
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'not valid phone'], 10025)];
        }
        $phone = UserVerify::toShortPhoneNum($phone);
        $user=new User();
        $ret=$user->addWhere("phone",$phone)->addWhere('valid','valid')->select();
        if(empty($ret)){
            $user = User::getCurrentUser();
            $user->mPhone = $phone;
            //发送短信验证码
            $result = UserVerify::sendPhoneVerify($user);
            $success = $result['success'];
            if(!$success){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'get verify code fail'], 10033)];
            }
            $_SESSION['changePhone']=['new_phone'=>$phone];
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'success', 'wait_time'=>60], 0)];
        } else {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['phone has used'], 10032)];
        }
    }

    //和bindPhoneAction是一对，用于第三方登陆用户绑定手机号码（保存绑定关系）
    //by boshen@20141212
    public function confirmBindPhoneAction(){
        $user=User::getCurrentUser();
        UserVerify::receivePhoneVerify($user);
        if(!$_SESSION['changePhone']){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'nothing to confirm'],99999)];
        }
        $phone = $_SESSION['changePhone']['new_phone'];
        if( empty($phone) || !UserVerify::isValidPhone($phone) ) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'phone number invalid'],99999)];
        }

        $user->mPhone = $phone;
        $user->mPhoneVerified = 1;
        $user->mLastUpdateTime = time();
        $user->save();
        # update session info
        $_SESSION['user']=$user->getData();
        unset($_SESSION['changePhone']);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([])];
    }

    //第三方登陆回调接口
    //by boshen@20141213
    public function thirdPlatformLoginAction() {
        $third_platform_type = $this->_POST('third_platform_type', '');
        //目前只能支持微信、微博
        if(!in_array($third_platform_type, array('weixin', 'weibo'))) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],10030)];
        }

        $weixin_id = $weibo_id = '';
        if( 'weixin' == $third_platform_type ) {
            $code = $this->_POST('code');
            $token = Login::getWeixinAccesstokenByCode($code);
            if( isset($token['errcode']) ) {
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],10034)];
            }
            $wx_accesstoken = $token['access_token'];
            $wx_refreshtoken = $token['refresh_token'];
            $wx_openid = $token['openid'];
            $user = Login::getWeixinUserInfo($token['access_token'], $token['openid']);
            if( isset($user['errcode']) ) {
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],10035)];
            }
            $unick = $weixin_id = $user['nickname'];
            $avatar_url = $user['headimgurl'];
        } elseif( 'weibo' == $third_platform_type ) {
            $token = $this->_POST('token');
            if( is_string($token) ) {
                $token = json_decode($token, true);
            }
            //var_dump($token); exit;
            if( !is_array($token) || !isset($token['uid']) ) {
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],10036)];
            }
            $wx_accesstoken = $token['access_token'];
            $wx_refreshtoken = '';
            $wx_openid = $token['uid'];
            $user = Login::getWeiboUserInfo($wx_accesstoken, $wx_openid);
            if( !is_array($user) || !isset($user['idstr']) ) {
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],10035)];
            }
            $unick = $weibo_id = $user['screen_name'];
            $avatar_url = $user['avatar_large'];
        }

        if(empty($wx_openid)) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],10031)];
        }

        $avatar_url = FileUtil::uploadImageByUrl($avatar_url);
        if(!$avatar_url){
            return ['json:',AppUtils::returnValue(['upload error'],99999)];
        }

        $now_time = time();
        $user=new User();
        $ret=$user->addWhere("wx_openid", $wx_openid)->addWhere('third_platform_type', $third_platform_type)->select();
        if(empty($ret)){
            $row = array();
            $row['name'] = ucfirst($third_platform_type).'_'.$unick;
            $row['phone'] = '';
            $row['password'] = md5($now_time.$wx_accesstoken.mt_rand(234561, 798621));
            $row['nick'] = $row['name'];
            $row['weixin_id'] = $weixin_id;
            $row['weibo_id'] = $weibo_id;
            $row['create_time'] = $now_time;
            $row['last_update_time'] = $now_time;
            $row['wx_openid'] = $wx_openid;
            $row['avatar_url'] = $avatar_url;
            $row['third_platform_type'] = $third_platform_type;
            $row['wx_accesstoken'] = $wx_accesstoken;
            $row['wx_createtime'] = $now_time;
            $row['wx_refreshtoken'] = $wx_refreshtoken;
            if(isset($_SESSION['easemob_anonymous'])){
                //之前有过匿名聊天，继续使用匿名时期的聊天账号
                $row['easemob_username'] = $_SESSION['easemob_anonymous']['username'];
                $row['easemob_username'] = $_SESSION['easemob_anonymous']['password'];
                $anonymous=new EasemobAnonymous();
                $anonymous->addWhere('session_id',session_id())->delete();
                unset($_SESSION['easemob_anonymous']);
            } else {
                $row['easemob_username'] = "user_".md5($this->easemob_salt.$unick);
                $row['easemob_password'] = md5($this->easemob_salt.session_id().$now_time);
            }
            $row['source'] = $_SERVER['HTTP_USER_AGENT'];;
            $user_id = $user->insert($row);
            if(!$user_id){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['can\'t insert user'],99999)];
            }
            $row['id'] = $user_id;
            $data = $row;

            //创建环信账号
            Easemob::getInstance()->createUser($row['easemob_username'], $row['easemob_password']);
            
            //多盟激活注册转化
            if($_SESSION['promote_idfa']) {
                $promote = new PromoteChannel;
                $promote->addWhere('ifa', $_SESSION['promote_idfa'])->update(['user_id'=>$row['id']]);
                $this->_domobResponse($_SESSION['promote_idfa']);
                unset($_SESSION['promote_idfa']);
            }
        } else {
            $data = $ret->getData();
        }

        $_SESSION['user'] = $data;
        $data['country_flag']=NationalFlag::getUrl($data['country']);
        $couponNum = Coupon::getCouponNumByUid($data['id']);
        $data['coupon_num'] = $couponNum;
        unset($data['password']);
        unset($data['wx_openid']);
        unset($data['wx_accesstoken']);
        unset($data['wx_refreshtoken']);
        if($this->_POST('os') == 'android') {
            $data = ['user' => $data];
        }

        //登录送代金券(只送一次)
        Coupon::loginLottery();

        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($data)];
    }

   //忘记密码，找回step1
    public function forgetPasswordAction() {
        $phone=$this->_POST('phone',"",10011);
        if(!UserVerify::isValidPhone($phone)){
            //不是手机号
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'not valid phone'], 10025)];
        }
        $phone = UserVerify::toShortPhoneNum($phone);
        $user=new User();
        $user=$user->addWhere("phone",$phone)->addWhere('valid','valid')->select();
        if(empty($user)) {
            return ["json:",AppUtils::returnValue([], 10051)];
        }

        $result = UserVerify::sendPhoneVerify($user);
        $success = $result['success'];
        if(!$success){
            return ["json:",AppUtils::returnValue($result, 10021)];
        }

        return ["json:",AppUtils::returnValue($result)];
    }

    //找回密码step2
    public function confirmForgetPasswordCodeAction() {
        $phone=$this->_POST('phone',"",10011);
        if(!UserVerify::isValidPhone($phone)){
            //不是手机号
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'not valid phone'], 10025)];
        }
        $phone = UserVerify::toShortPhoneNum($phone);
        $user=new User();
        $user=$user->addWhere("phone",$phone)->addWhere('valid','valid')->select();
        if(empty($user)) {
            return ["json:",AppUtils::returnValue([], 10051)];
        }

        $ret=UserVerify::receivePhoneVerify($user);
        if( !$ret ) {
            return ["json:",AppUtils::returnValue([], 10023)];
        }

        //记录验证的时间，五分钟之内必须验证完毕
        $_SESSION['forgetPasswordConfirmedTime'] = time();

        return ["json:",AppUtils::returnValue([], 0)];
    }

    //找回密码step3
    //重置密码
    public function resetPasswordAction() {
        $now_time = time();
        //没有重置密码时间，或者超过5分钟失效
        if( !isset($_SESSION['forgetPasswordConfirmedTime']) 
            || $now_time - $_SESSION['forgetPasswordConfirmedTime'] > 300 ) {
            return ["json:",AppUtils::returnValue([], 10052)];
        }

        $password = $this->_POST('password');
        $password = md5($password);
        $repeatPassword = $this->_POST('repeatPassword');
        $repeatPassword = md5($repeatPassword);
        if( $password != $repeatPassword ) {
            return ["json:",AppUtils::returnValue([], 10053)];
        }

        $phone=$this->_POST('phone',"",10011);
        if(!UserVerify::isValidPhone($phone)){
            //不是手机号
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'not valid phone'], 10025)];
        }
        $phone = UserVerify::toShortPhoneNum($phone);
        $user=new User();
        $user=$user->addWhere("phone",$phone)->addWhere('valid','valid')->select();
        if(empty($user)) {
            return ["json:",AppUtils::returnValue([], 10051)];
        }

        //var_dump($user, $password); exit;
        $data = array( 'password'=>$password, 'last_update_time'=>$now_time );
        $user->setDataMerge($data)->save();

        unset($_SESSION['forgetPasswordConfirmedTime']);

        return ["json:",AppUtils::returnValue([], 0)];
    }

}
