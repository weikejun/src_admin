<?php
class UserVerify{
    public static function sendPhoneVerify($user,$operation='verify',$timeout=600){
        //$user can be null
        if(isset($_SESSION['lastSendSMSPhone']) && $_SESSION['lastSendSMSPhone'] == $user->mPhone 
        && isset($_SESSION['lastSendSMS']) && time()-$_SESSION['lastSendSMS']<60){
            //一分钟之内，不允许重新发短信
            return ['success' => false, 'wait_time' => (60 + $_SESSION['lastSendSMS'] - time())];
        }
        $securecode=new Securecode();
        $securecode->addWhere('user_id',$user->mId)->addWhere('operation',$operation)->delete();
        $code=rand(1000,9999);
        #$code=str_pad($code, 4, "0", STR_PAD_LEFT);
        $ret=$securecode->insert([
            'user_id'=>$user->mId,
            'operation'=>$operation,
            'create_time'=>time(),
            'update_time'=>time(),
            'timeout_time'=>time()+$timeout,
            'code'=>$code,
            ]);
        $_SESSION['lastSendSMS'] = time();
        $_SESSION['lastSendSMSPhone'] = $user->mPhone;
        PhoneUtil::sendSMS($user->mPhone, UserVerify::genVerifyContent($code));
        //return ['success' => true, 'wait_time' => 60, 'code' => $code];
        return ['success' => true, 'wait_time' => 60];
    }
    public static function genVerifyContent($code){
        return '您的验证码是'.$code; 
        //return iconv("UTF-8", "GB2312//IGNORE", $content);
    }
    public static function receivePhoneVerify($user,$operation='verify',$timeout=600){
        $code=AppUtils::POST('verify_code',null,99999);
        $securecode=new Securecode();
        $securecode=$securecode->addWhere('user_id',$user->mId)->addWhere('operation',$operation)->orderBy("id", "desc")->select();
        if(!$securecode||$securecode->mCode!=$code){
            throw new ModelAndViewException("phone verify fail",$errorCode,'json:',AppUtils::returnValue(['msg'=>'securecode error'],10023));
        }
        return true;
    }
    public static function isValidPassword($password){
        return strlen($password)>=6;
    }
    public static function isValidPhone($phone){
        return preg_match("/[0-9-+ ]{11,}/",$phone);
    }
    # 保证成功发送短信，将+8615810540853截取为15810540853
    public static function toShortPhoneNum($phone){
        if(strlen($phone) > 11)
            $phone = substr($phone, -11);
        return $phone;
    }
}
