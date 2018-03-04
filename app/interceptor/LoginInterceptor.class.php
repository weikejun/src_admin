<?php

class LoginInterceptor extends Interceptor{
    public function beforeAction(){
        global $IS_DEBUG;
        // 该cookie暂时用来测试用
        #if($IS_DEBUG && $_COOKIE  && $_COOKIE['PHPSESSID'] == '32cc2b0e680815a34810fa6ec31dbd68'){
        if(false){
            $user=new User();
            $user=$user->addWhere("name","liuyuan")->select();
        	WinRequest::mergeModel(array('user'=>$user));
            return;
        }
        $model=WinRequest::getModel();
        $executeInfo=$model['executeInfo'];
        if(!$_SESSION['user'] && 
            $this->needLogin($executeInfo)
        ){
            #throw new ModelAndViewException("not login",1,"json:",AppUtils::returnValue(['msg'=>'not login'],'90001'));
            throw new ModelAndViewException("not login",1,AppUtils::GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'not login'],'90001'));
        }elseif($_SESSION['user'] && $this->needLogin($executeInfo) && 1!=$_SESSION['user']['phone_verified'] && !in_array($_SESSION['user']['third_platform_type'], array('weixin', 'weibo'))){
            //没验证
            #throw new ModelAndViewException("not login",1,"json:",AppUtils::returnValue(['msg'=>'not verified'],'90002'));
            throw new ModelAndViewException("not login",1,AppUtils::GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'not verified'],'90002'));
        } else{
        	//WinRequest::mergeModel(array('user'=>$_SESSION['user']));
        	WinRequest::mergeModel(array('user'=>User::getCurrentUser()));
        }
    }
    public function needLogin($executeInfo){
        $notAuthActions=[
            ['User','loginAction'],
            ['User', 'thirdPlatformLoginAction'],
            ['User','registerAction'],
            ['User','forgetPasswordAction'],
            ['User','confirmForgetPasswordCodeAction'],
            ['User','resetPasswordAction'],
        #    ['User','registerConfirmAction'],
            ['User','confirmRegisterVerifyAction'],
        #    ['User','resendVerifyAction'],
            ['User','resendPhoneVerifyAction'],
            ['Live','listAction'],
            ['Live','showAction'],
            ['Live','stocksAction'],
            ['Stock','showAction'],
            ['Stock','detailAction'],
            ['Stock','commentListAction'],
            ['StockComment','listAction'],
            ['Order','notifyAction'],
            ['Order','notifyClientAction'],
            ['Order','notifyRefundAction'],
            ['Order','wxNotifyAction'],
            ['Order','callbackAction'],
            ['Easemob','anonymousAction'],
            ['Buyer','infoAction'],
            ['Feedback','stockAction'],
            ['BuyerState','detailAction'],
            ['BuyerState','commentListAction'],
        ];
        foreach($notAuthActions as $notAuthAction){
            if($executeInfo['controllerName']==$notAuthAction[0]
                &&$executeInfo['methodName']==$notAuthAction[1]){
                return false;
            }
        }
        return true;
    }
    public function afterAction(){
    }
}
