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
            array('name'=>'admin_id','label'=>'用户','type'=>"choosemodelMulti",'model'=>'Model_Admin','default'=>null,'required'=>true,),
            array('name'=>'group_id','label'=>'角色','type'=>"choosemodelMulti",'model'=>'Model_Group','default'=>null,'required'=>true,),
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
                return ($ret ? '<a href="/admin/RolePermission?__filter='.urlencode('group_id='.$group->mId).'">'.$group->mName.'</a>' : '(id='.$model->mGroupId.')' );
            }],
        );

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'用户ID','paramName'=>'admin_id','fusion'=>false,'hidden'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>'角色ID','paramName'=>'group_id','fusion'=>false,'hidden'=>true,'class'=>'keep-all']),
            new Page_Admin_TextForeignFilter(['name'=>'用户名','paramName'=>'name|admin_id','foreignTable'=>'Model_Admin','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'角色名','paramName'=>'name|group_id','foreignTable'=>'Model_Group','fusion'=>true]),
        );

        $this->single_actions_default = ['delete'=>true,'edit'=>false];
    }

    public function _create(){
        unset($_REQUEST['id']);
        $groupIds = $_REQUEST['group_id'];
        $adminIds = $_REQUEST['admin_id'];
        if($groupIds && $adminIds) {
            for($i = 0; $i < count($groupIds); $i++) {
                for($j = 0; $j < count($adminIds); $j++) {
                    $model = new Model_AdminGroup;
                    $model->setData([
                        'group_id' => $groupIds[$i],
                        'admin_id' => $adminIds[$j],
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


