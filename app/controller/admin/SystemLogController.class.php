<?php
class SystemLogController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new SystemLog();
        $this->model->orderBy("id","desc");
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
            array('name'=>'create_time',      'type'=>"datetime",'default'=>null,'null'=>false,),
            array('name'=>'password',      'type'=>"password",'default'=>null,'required'=>true,),
            array('name'=>'password_again','type'=>"password",'default'=>null,'required'=>false,'validator'=>function($values){
                if($values['password'] && $values['password_again']&&md5($values['password_again'])==$values['password']){
                    return true;
                }else{
                    return "please retype the password";
                }
            }),
            array('name'=>'valid',"choices"=>array(array('valid',"有效"),array('invalid',"无效"),), 'type'=>"choice",'default'=>null,'null'=>false,),
             */

            array('name'=>'admin_id','type'=>"choosemodel",'model'=>'Admin','default'=>null,'required'=>true,),
            array('name'=>'buyer_id','type'=>"choosemodel",'model'=>'Buyer','default'=>null,'required'=>false,),
            array('name'=>'user_id','type'=>"choosemodel",'model'=>'User','default'=>null,'required'=>false,),
            array('name'=>'create_time','type'=>"datetime",'default'=>null,'required'=>false,),
            array('name'=>'log',      'type'=>"textarea",'default'=>null,'required'=>true,),

        ));
        $this->list_display=array('id',
            ['label'=>'操作用户','field'=>function($model){
                if(!empty($model->mAdminId)) {
                    $admin = new Admin();
                    $ret = $admin->addWhere("id", $model->mAdminId)->select();
                    return ($ret ? $admin->mName : '(id='.$model->mAdminId.')' );
                }
            }],'user_id','buyer_id',
            array('label'=>'创建时间','field'=>function($log){
                return date("Y-m-d H:i:s", $log->mCreateTime);
            }),
            array('label'=>'log','field'=>function($log){
                return mb_strimwidth($log->mLog,0,40);
            })
        );
        /*
        $this->list_filter=array(
            new Admin_SiteTagsFilter()
        );
        $this->inline_admin=array(
            new Page_Admin_InlineSiteModule($this,'site_id'),
        );
        $this->multi_actions=array(
            array('label'=>'添加到module','action'=>'javascript:add_to_module();return false;'),
        );*/
        
        $this->search_fields=array('admin_id','buyer_id','user_id','log');
    }
}




