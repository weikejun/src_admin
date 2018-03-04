<?php
class CsController extends Page_Admin_Base {
    private $_kfKey = 'aimeizhuyi.kefu';
    private $easemob_salt='hw%#(d8*]/d';

    public function bindModelEvent() {
        $this->model->on("after_insert", function($model) {
            $class = get_class($model);
            $curModel = new $class();
            $curModel = $curModel->addWhere("id", $model->mId)->select();
            if(!$curModel){
                return false;
            }
            $curModel->mEasemobUsername = "buyer_" . md5($this->easemob_salt . $curModel->mName);
            $curModel->mEasemobPassword = md5($this->easemob_salt . $curModel->mPassword . time());
            if(Easemob::getInstance()->createUser($curModel->mEasemobUsername, $curModel->mEasemobPassword)) {
                $curModel->save();
            }
        });
    }

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Buyer();
        $this->model->addWhere('qq', $this->_kfKey)->orderBy("id","desc");
        $this->bindModelEvent();
        
        $this->form=new Form(array(
            array('name'=>'name','label'=>'用户名','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'password','label'=>'密码重置','type'=>"password",'default'=>null,'required'=>false,),
            array('name'=>'password_again','label'=>'密码确认','type'=>"password",'default'=>null,'required'=>false,'validator'=>function($values){
                if(!$values['password']||
                    ($values['password']&&md5($values['password_again'])==$values['password'])){
                    return true;
                }else{
                    return "please retype the password";
                }
            }),
            array('name'=>'email','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'status','type'=>"hidden",'default'=>'be','required'=>true,),
            array('name'=>'head','label'=>'头像','type'=>"simpleFile",'default'=>null,'required'=>false,),
            array('name'=>'wexin','label'=>'微信/qq','type'=>"hidden",'default'=>$this->_kfKey,'required'=>true,),
            array('name'=>'qq','label'=>'微信/qq','type'=>"hidden",'default'=>$this->_kfKey,'required'=>true,),
            array('name'=>'easemob_username','label'=>'环信用户名(系统自动填写)','readonly'=>true,'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'easemob_password','label'=>'环信密码(系统自动填写)','readonly'=>true,'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'create_time','label'=>'注册时间','type'=>"datetime",'default'=>null,'required'=>true,'readonly'=>true),
        ));
        $this->list_display=array(
            ['label'=>'客服ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'用户名','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'头像','field'=>function($model){
                if(!$model->mHead) {
                    $model->mHead = '/winphp/metronic/media/image/avatar.png';
                }
                return "<img src='$model->mHead' width='32px' />";
            }],
            'email',
            ['label'=>'创建时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
        );

        $this->single_actions=[
            ['target'=>'_blank','label'=>'聊天记录','action'=>function($model){
                return '/admin/EasemobMsg?target='.$model->mEasemobUsername;
            }],
        ];
        
    }
}


