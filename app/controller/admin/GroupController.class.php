<?php
class GroupController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_Group();
        WinRequest::mergeModel(array(
            'controllerText'=>"角色管理",
        ));

        $this->form=new Form(array(
            array('name'=>'角色名','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'角色说明','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'创建时间','type'=>"datetime",'default'=>time(),'readonly'=>true,),
        ));
        $this->list_display=array(
            ['label'=>'角色ID','field'=>function($model) {
                return $model->mId;
            }],
            ['label'=>'角色名','field'=>function($model) {
                return $model->mName;
            }],
            ['label'=>'角色说明','field'=>function($model) {
                return $model->mDescription;
            }],
            ['label'=>'创建时间','field'=>function($model) {
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
        );

        $this->single_actions=[
            ['label'=>'权限','action'=>function($model){
                return '/admin/RolePermission?__filter='.urlencode('group_id='.$model->mId);
            }],
        ];
        
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'角色ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'角色名','paramName'=>'name','fusion'=>true]),
        );
    }
}


