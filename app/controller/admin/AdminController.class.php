<?php
class AdminController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Admin();
        $this->model->orderBy('create_time', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"系统用户",
        ));
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            /*
            array('type'=>'text',"name"=>"name","label"=>'name',"required"=>true,'class'=>'wide'),
            array('type'=>'text',"name"=>"url","label"=>'url',"required"=>true,'class'=>'wide'),
            array('type'=>'text',"name"=>"img","label"=>'img','class'=>'wide'),
            array('type'=>'text',"name"=>"tags","label"=>'tags','class'=>'wide'),
            array('type'=>'text',"name"=>"status","label"=>'status','default'=>1,'class'=>'wide'),
            array('type'=>'text',"name"=>"ctime","label"=>'ctime','default'=>time(),'class'=>'wide'),
             */

            array('name'=>'name','label'=>'用户名','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'default'=>null,'null'=>false,),
            array('name'=>'password','label'=>'密码','type'=>"password",'default'=>null,'required'=>false,),
            array('name'=>'password_again','label'=>'密码确认','type'=>"password",'default'=>null,'required'=>false,'validator'=>function($values){
                if(!$values['password']||
                    ($values['password']&&md5($values['password_again'])==$values['password'])){
                    return true;
                }else{
                    return "please retype the password";
                }
            }),
            array('name'=>'valid','label'=>'有效状态',"choices"=>array(array('valid',"有效"),array('invalid',"无效"),), 'type'=>"choice",'default'=>'valid','null'=>false,),
        ));
        $this->list_display=array(
            ['label'=>'id','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'用户名','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'状态','field'=>function($model){
                return $model->mValid;
            }],
            ['label'=>'创建时间','field'=>function($model){
                return date('Y-m-d H:i:s',$model->mCreateTime);
            }],
        );

        $this->single_actions=[
            ['label'=>'权限','action'=>function($model){
                return '/admin/adminGroup?__filter='.urlencode('admin_id='.$model->mId);
            }],
        ];

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'用户ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'用户名','paramName'=>'name','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>'创建时间','paramName'=>'create_time']),
        );
    }
    public function display_ctime($modelData){
        return strftime("%Y-%m-%d",$modelData->mCreateTime);
    }
    public function sugAction(){
        $model=new Admin();
        $wd=$_GET['wd'];
        $admins=$model->addWhere("name","$wd%","like")->limit(0,10)->find();
        if(!$admins){
            $admins=[];
        }
        $data=array_map(function($admin){
            return $admin->mName;
        },$admins);
        return ["jsonp:",["json"=>$data]];
    }

}


