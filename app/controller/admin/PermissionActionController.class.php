<?php
class PermissionActionController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_PermissionAction();
        $this->model->orderBy('create_time', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"权限组配置",
        ));

        $this->form=new Form(array(
            array('name'=>'permission_id','label'=>'权限组','type'=>"choosemodelMulti",'model'=>'Model_Permission','default'=>null,'required'=>true,),
            array('name'=>'action_id','label'=>'权限','type'=>"choosemodelMulti",'model'=>'Model_Action','default'=>null,'required'=>true,),
            //array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>time(),'required'=>false),
        ));
        $this->list_display=[
            ['label'=>'权限组名','field'=>function($model){
                $finder = new Model_Permission();
                $perm = $finder->addWhere('id', $model->mPermissionId)->select();
                if($perm) {
                    return $perm->mName;
                }
                return "<i>（权限出错）</i>";
            }],
            ['label'=>'权限名','field'=>function($model){
                $finder = new Model_Action();
                $perm = $finder->addWhere('id', $model->mActionId)->select();
                if($perm) {
                    return $perm->mName;
                }
                return "<i>（权限出错）</i>";
            }],
            ['label'=>'创建时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
        ];
        $this->list_filter=array(
            new Page_Admin_TextForeignFilter(['name'=>'权限组','paramName'=>'name|permission_id','foreignTable'=>'Model_Permission','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'权限','paramName'=>'name|action_id','foreignTable'=>'Model_Action','fusion'=>true]),
        );
        $this->single_actions_default = ['delete'=>true,'edit'=>false];
    }

    public function _create(){
        unset($_REQUEST['id']);
        $actionIds = $_REQUEST['action_id'];
        $permIds = $_REQUEST['permission_id'];
        if($actionIds && $permIds) {
            for($i = 0; $i < count($actionIds); $i++) {
                for($j = 0; $j < count($permIds); $j++) {
                    $model = new Model_PermissionAction;
                    $model->setData([
                        'action_id' => $actionIds[$i],
                        'permission_id' => $permIds[$j],
                        'create_time' => time(),
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


