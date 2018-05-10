<?php
class SystemLogController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new SystemLog();
        $this->model->orderBy("create_time","DESC");
        WinRequest::mergeModel(array(
            'controllerText'=>"系统日志",
        ));
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(

        ));
        $this->list_display=array(
            ['label'=>'操作人','field'=>function($model){
                $admin = $this->_getResource($model->mOperatorId, 'Admin', new Admin, 'id');
                return $admin->mName;
            }],
            ['label'=>'操作IP','field'=>function($model){
                return $model->mOperatorIP;
            }],
            ['label'=>'资源','field'=>function($model){
                return $model->mResource.":".$model->mResId;
            }],
            ['label'=>'动作','field'=>function($model){
                return $model->mAction;
            }],
            ['label'=>'内容','field'=>function($model){
                return $model->mDetail;
            }],
            ['label'=>'操作时间','field'=>function($model){
                return date('Y-m-d H:i:s',$model->mCreateTime);
            }],
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
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'操作人ID','paramName'=>'operator_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'资源','paramName'=>'resource','fusion'=>false]),
        );

        $this->hide_action_new = true;
        
        //$this->search_fields=array('admin_id','buyer_id','user_id','log');
    }
}




