<?php
class RolePermissionController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new RolePermission();
        WinRequest::mergeModel(array(
            'controllerText'=>"角色权限管理",
        ));

        $this->form=new Form(array(
            array('name'=>'group_id','label'=>'角色ID','type'=>"choosemodel",'model'=>'Group','default'=>null,'required'=>false,),
            array('name'=>'admin_id','label'=>'用户ID','type'=>"choosemodel",'model'=>'Admin','default'=>null,'required'=>false,),
            array('name'=>'permission_id','label'=>'权限ID','type'=>"choosemodel",'model'=>'Permission','default'=>null,'required'=>true,),
        ));
        $this->list_display=array(
            ['label'=>'用户','field'=>function($model){
                if(!empty($model->mAdminId)) {
                    $admin = new Admin();
                    $ret = $admin->addWhere("id", $model->mAdminId)->select();
                    return ($ret ? $admin->mName : '(id='.$model->mAdminId.')' );
                }
            }],
            ['label'=>'角色','field'=>function($model){
                if(!empty($model->mGroupId)) {
                    $group = new Group();
                    $ret = $group->addWhere("id", $model->mGroupId)->select();
                    return ($ret ? ('(id='.$model->mGroupId.')' . $group->mName) : '(id='.$model->mGroupId.')' );
                }
            }],
            ['label'=>'权限','field'=>function($model){
                $permission = new Permission();
                $ret = $permission->addWhere("id", $model->mPermissionId)->select();
                return ($ret ? ('(id='.$model->mPermissionId.')' . $permission->mName) : '(id='.$model->mPermissionId.')' );
            }],
        );

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'用户ID','paramName'=>'admin_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'角色ID','paramName'=>'group_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'权限ID','paramName'=>'permission_id','fusion'=>false]),
            new Page_Admin_TextForeignFilter(['name'=>'用户名','paramName'=>'name|admin_id','foreignTable'=>'Admin','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'角色名','paramName'=>'name|group_id','foreignTable'=>'Group','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'权限名','paramName'=>'name|permission_id','foreignTable'=>'Permission','fusion'=>true]),
        );

        //$this->search_fields=array('name', 'id');
    }
}


