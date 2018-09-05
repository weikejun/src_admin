<?php
class PermissionController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_Permission();
        $this->model->orderBy('create_time', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"权限组",
        ));

        $this->form=new Form(array(
            array('name'=>'name','label'=>'权限组名','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'description','label'=>'权限组说明','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'default'=>time(),'null'=>false,'readonly'=>true,'auto_update'=>true),
        ));
        $this->list_display=array(
            ['label'=>'权限组ID','field'=>function($model) {
                return $model->mId;
            }],
            ['label'=>'权限组名','field'=>function($model) {
                return $model->mName;
            }],
            ['label'=>'权限组说明','field'=>function($model) {
                return $model->mDescription;
            }],
            ['label'=>'创建时间','field'=>function($model) {
                return date("Y-m-d H:i:s", $model->mCreateTime);
            }],
        );
        
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'权限组ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'权限组名','paramName'=>'name','fusion'=>true]),
        );

        $this->single_actions=[
            ['label'=>'已分配角色','action'=>function($model){
                return '/admin/rolePermission?__filter='.urlencode('permission_id='.$model->mId);
            }],
            ['label'=>'包含权限集','action'=>function($model){
                return '/admin/PermissionAction?__filter='.urlencode('name|permission_id='.$model->mName);
            }],
        ];
    }
    public function display_ctime($modelData){
        return strftime("%Y-%m-%d",$modelData->mCreateTime);
    }   

}


