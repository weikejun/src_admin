<?php
class RolePermissionController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_RolePermission();
        $this->model->orderBy('id', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"角色权限",
        ));

        $this->form=new Form(array(
            array('name'=>'group_id','label'=>'角色','type'=>"choosemodelMulti",'model'=>'Model_Group','default'=>null,'required'=>false,),
            //array('name'=>'admin_id','label'=>'用户ID','type'=>"choosemodel",'model'=>'Model_Admin','default'=>null,'required'=>false,),
            array('name'=>'permission_id','label'=>'权限组','type'=>"choosemodelMulti",'model'=>'Model_Permission','default'=>null,'required'=>true,),
        ));
        $this->list_display=array(
            /*['label'=>'用户','field'=>function($model){
                if(!empty($model->mAdminId)) {
                    $admin = new Model_Admin();
                    $ret = $admin->addWhere("id", $model->mAdminId)->select();
                    return ($ret ? $admin->mName : '(id='.$model->mAdminId.')' );
                }
            }],*/
            ['label'=>'角色','field'=>function($model){
                if(!empty($model->mGroupId)) {
                    $group = new Model_Group();
                    $ret = $group->addWhere("id", $model->mGroupId)->select();
                    return ($ret ? ($group->mName) : '(id='.$model->mGroupId.')' );
                }
            }],
            ['label'=>'权限组','field'=>function($model){
                $permission = new Model_Permission();
                $ret = $permission->addWhere("id", $model->mPermissionId)->select();
                return ($ret ? ($permission->mName) : '(id='.$model->mPermissionId.')' );
            }],
        );

        $this->list_filter=array(
            //new Page_Admin_TextFilter(['name'=>'用户ID','paramName'=>'admin_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'角色ID','paramName'=>'group_id','fusion'=>false,'hidden'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>'权限ID','paramName'=>'permission_id','fusion'=>false,'hidden'=>true,'class'=>'keep-all']),
            //new Page_Admin_TextForeignFilter(['name'=>'用户名','paramName'=>'name|admin_id','foreignTable'=>'Model_Admin','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'角色名','paramName'=>'name|group_id','foreignTable'=>'Model_Group','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'权限名','paramName'=>'name|permission_id','foreignTable'=>'Model_Permission','fusion'=>true]),
        );

        //$this->search_fields=array('name', 'id');
        $this->single_actions_default = ['delete'=>true,'edit'=>false];
    }

    public function _create(){
        unset($_REQUEST['id']);
        $groupIds = $_REQUEST['group_id'];
        $permIds = $_REQUEST['permission_id'];
        if($groupIds && $permIds) {
            for($i = 0; $i < count($groupIds); $i++) {
                for($j = 0; $j < count($permIds); $j++) {
                    $model = new Model_RolePermission;
                    $model->setData([
                        'group_id' => $groupIds[$i],
                        'permission_id' => $permIds[$j],
                    ]);
                    $model->save();
                }
            }
            return true;
        }
        $this->assign("__is_new",true);
        $this->assign("form",$this->form);
        return false;
    }
}


