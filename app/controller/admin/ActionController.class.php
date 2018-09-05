<?php
class ActionController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_Action();
        $this->model->orderBy('create_time', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"权限",
        ));

        $this->form=new Form(array(
            array('name'=>'name','label'=>'权限名','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'description','label'=>'权限说明','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>time(),'required'=>false),
        ));
        $this->list_display=[
            ['label'=>'权限ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'权限名','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'权限说明','field'=>function($model){
                return $model->mDescription;
            }],
            ['label'=>'创建时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
        ];
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'权限名','paramName'=>'name','fusion'=>true]),
        );
        $this->single_actions=[
            ['label'=>'所属权限组','action'=>function($model){
                return '/admin/PermissionAction?__filter='.urlencode('name|action_id='.$model->mName);
            }],
        ];
    }
}


