<?php
class AdminGroupController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_AdminGroup();
        WinRequest::mergeModel(array(
            'controllerText'=>"用户角色",
        ));

        $this->form=new Form(array(
            array('name'=>'admin_id','label'=>'用户ID','type'=>"choosemodel",'model'=>'Model_Admin','default'=>null,'required'=>true,),
            array('name'=>'group_id','label'=>'角色ID','type'=>"choosemodel",'model'=>'Model_Group','default'=>null,'required'=>true,),
        ));

        $this->list_display=array(
            ['label'=>'用户名','field'=>function($model){
                $admin = new Model_Admin();
                $ret = $admin->addWhere("id", $model->mAdminId)->select();
                return ($ret ? $admin->mName : '(id='.$model->mAdminId.')' );
            }],
            ['label'=>'角色名','field'=>function($model){
                $group = new Model_Group();
                $ret = $group->addWhere("id", $model->mGroupId)->select();
                return ($ret ? $group->mName : '(id='.$model->mGroupId.')' );
            }],
        );

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'用户ID','paramName'=>'admin_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'角色ID','paramName'=>'group_id','fusion'=>false]),
            new Page_Admin_TextForeignFilter(['name'=>'用户名','paramName'=>'name|admin_id','foreignTable'=>'Model_Admin','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'角色名','paramName'=>'name|group_id','foreignTable'=>'Model_Group','fusion'=>true]),
        );
    }
}


