<?php
include_once ROOT_PATH.'/lib/AliCaptcha/aliyun-php-sdk-core/Config.php';
use afs\Request\V20180112 as Afs;

class IndexController extends Page_Admin_Base{
    
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
    }

    public function indexAction(){
        return array("admin/index.tpl",array('url'=>$_GET['url']));
        //return array("redirect: /admin/Project");
    }

    protected function _checkCaptcha() {
        //YOUR ACCESS_KEY、YOUR ACCESS_SECRET请替换成您的阿里云accesskey id和secret  
        $iClientProfile = DefaultProfile::getProfile("cn-hangzhou", ALIYUN_ACCESS_KEY, ALIYUN_SECRECT);
        $client = new DefaultAcsClient($iClientProfile);
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", "afs", "afs.aliyuncs.com");
        //
        $request = new Afs\AuthenticateSigRequest();
        $request->setSessionId($_POST['csessionid']);// 必填参数，从前端获取，不可更改，android和ios只变更这个参数即可，下面参数不变保留xxx
        $request->setToken($_POST['nc_token']);// 必填参数，从前端获取，不可更改
        $request->setSig($_POST['sig']);// 必填参数，从前端获取，不可更改
        $request->setScene($_POST['scene']);// 必填参数，从前端获取，不可更改
        $request->setAppKey(ALIYUN_CAPTCHA_APPKEY);//必填参数，后端填写
        $request->setRemoteIp(Utils::getClientIP());//必填参数，后端填写
        //
        $response = $client->getAcsResponse($request);//返回code 100表示验签通过，900表示验签失败
        //print_r($response);
        return $response && ($response->Code == 100);
    }
    
    public function loginAction(){
        if($_POST){
            $admin=$this->valid($_POST['name'],$_POST['password']);
            if($admin && $this->_checkCaptcha()) {
                $_SESSION['admin']=$admin;
                return array("redirect:".(isset($_REQUEST['url'])?$_REQUEST['url']:$this->getUrlPrefix()."/index"));
            }else{
                return array("redirect:".$_SERVER['REQUEST_URI']);
            }
        }
        return array("admin/login.tpl",array('url'=>isset($_GET['url'])?$_GET['url']:'','captchaKey'=>ALIYUN_CAPTCHA_APPKEY));
    }
    public function logoutAction(){
        unset($_SESSION['admin']);
        return array("redirect:".$this->getUrlPrefix()."/index/login");
    }
    public function changepasswordAction(){
        if($_POST&&isset($_POST['password'])&&isset($_POST['new_password'])){
            $admin=$this->valid($_SESSION['admin']['name'],$_POST['password']);
            if($admin){
                $tbl = new DBTable("admin");
                $tbl->addWhere("id",$_SESSION['admin']['id'])->update(['password'=>md5($_POST['new_password'])]);
                $admin=$tbl->addWhere("id",$_SESSION['admin']['id'])->select();
                $_SESSION['admin']=$admin;
                return array("redirect:".(isset($_REQUEST['url'])?$_REQUEST['url']:$this->getUrlPrefix()."/index/logout"));
            }else{
                return array("redirect:".$_SERVER['REQUEST_URI'].
                    (strpos($_SERVER['REQUEST_URI'],"?")===false?"?":"&").
                    "msg=password%20error"
                );
            }
        }
        return array("admin/changepassword.tpl",array('url'=>$_GET['url'],'msg'=>$_GET['msg']));
    }

    private function valid($name, $password){
        //for test
        //return array('name'=>$username, 'privilege'=>1);
        $password = md5($password);
        $tbl = new DBTable("admin");
        $tbl->addWhere('name', $name);
        $tbl->addWhere('password', $password);
        $tbl->addWhere('valid', 'valid');
	try {
        return $tbl->select();
	} catch (SystemException $e) {
		var_dump($e);
		exit;
	}
    }
    
    public function uploadAction(){
        $paths=FileUtil::uploadFile($_FILES['file'],PUBLIC_IMAGE_BASE,["png",'jpg','jpeg','gif'],PUBLIC_IMAGE_URI);
        
        return ['json:',AppUtils::returnValue($paths,0)];
    }
    public function ckuploadAction(){
        $paths=FileUtil::uploadFile($_FILES['upload'],PUBLIC_IMAGE_BASE,["png",'jpg','jpeg','gif'],PUBLIC_IMAGE_URI);
        $callback = $_REQUEST["CKEditorFuncNum"];
        return ["text:<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($callback,'".$paths[0]."','');</script>"];
        //return ['json:',AppUtils::returnValue($paths,0)];
    }

}
