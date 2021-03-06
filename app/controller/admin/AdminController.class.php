<?php
class AdminController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_Admin();
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
            array('name'=>'real_name','label'=>'真实姓名','type'=>"text",'default'=>null,'required'=>true,'placeholder'=>'请填写全名'),
            array('name'=>'password','label'=>'密码','type'=>"password",'default'=>null,'required'=>false,'validator'=>function($values) {
                if (!isset($values['id']) || !$values['id']) { // 新增检查，更新不检查
                    if (!$values['password']) {
                        return '请输入密码';
                    }
                }
                return true;
            }),
            array('name'=>'valid','label'=>'有效状态',"choices"=>array(array('valid',"有效"),array('invalid',"无效"),), 'type'=>"choice",'default'=>'valid','null'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'default'=>time(),'readonly'=>true,),
        ));
        $this->list_display=array(
            ['label'=>'id','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'用户名','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'真实姓名','field'=>function($model){
                return $model->mRealName;
            }],
            ['label'=>'状态','field'=>function($model){
                return $model->mValid;
            }],
            ['label'=>'创建时间','field'=>function($model){
                return date('Y-m-d H:i:s',$model->mCreateTime);
            }],
        );

        $this->single_actions=[
            ['label'=>'角色','action'=>function($model){
                return '/admin/adminGroup?__filter='.urlencode('admin_id='.$model->mId);
            }],
        ];

        $this->search_fields = ['id', 'name', 'real_name'];

        $this->single_actions_default = ['edit'=>true,'delete'=>false];
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

    /*
     * 重载_delete()方法
     */
    public function _delete() {
        $this->model->addWhere('id', $_REQUEST['id'])->update(['valid'=>['"invalid"', DBTable::NO_ESCAPE]]);
    }
}


