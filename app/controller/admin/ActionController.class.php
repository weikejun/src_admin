<?php
class ActionController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_Action();
        $this->model->orderBy('name', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"动作权限管理",
        ));

        $this->form=new Form(array(
            array('name'=>'name','label'=>'动作名称','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'description','label'=>'动作说明','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'permission_id','label'=>'权限ID','type'=>"choosemodel",'model'=>'Model_Permission','default'=>null,'required'=>true,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>time(),'required'=>false),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime",'readonly'=>true,'default'=>time(),'required'=>false,'auto_update'=>true,'auto_update'=>true),
        ));
        $this->list_display=[
            ['label'=>'动作名称','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'动作说明','field'=>function($model){
                return $model->mDescription;
            }],
            ['label'=>'权限名','field'=>function($model){
                $finder = new Model_Permission();
                $perm = $finder->addWhere('id', $model->mPermissionId)->select();
                if($perm) {
                    return $perm->mName;
                }
                return "<i>（权限出错）</i>";
            }],
            ['label'=>'创建时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
            ['label'=>'更新时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mUpdateTime);
            }],
        ];
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'访问名称','paramName'=>'name','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'权限名','paramName'=>'name|permission_id','foreignTable'=>'Model_Permission','fusion'=>true]),
        );
    }
}


