<?php
class EntityPermissionController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_EntityPermission();
        $this->model->orderBy('id', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"基金LP授权",
        ));

        $this->form=new Form(array(
            array('name'=>'admin_id','label'=>'授权用户','type'=>"choosemodelMulti",'model'=>'Model_Admin','default'=>null,'required'=>true,),
            array('name'=>'entity_id','label'=>'授权主体','type'=>"choosemodelMulti",'model'=>'Model_Entity','default'=>null,'required'=>false,'show'=>'name'),
            array('name'=>'lp_id','label'=>'授权LP','type'=>"choosemodelMulti",'model'=>'Model_FundLp','default'=>null,'required'=>false,'show'=>'id'),
            array('name'=>'operator_id','label'=>'操作人','type'=>"hidden",'default'=>Model_Admin::getCurrentAdmin()->mId,'required'=>false,'readonly'=>true),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'default'=>time(),'readonly'=>true,),
        ));
        $this->list_display=array(
            ['label'=>'授权用户','field'=>function($model){
                if(!empty($model->mAdminId)) {
                    $admin = new Model_Admin();
                    $ret = $admin->addWhere("id", $model->mAdminId)->select();
                    return ($ret ? $admin->mName : '(id='.$model->mAdminId.')' );
                }
            }],
            ['label'=>'授权主体','field'=>function($model)use(&$entity, &$lp){
                if (empty($model->mEntityId)
                    && empty($model->mLpId)) {
                    return '所有主体';
                }
                $entityId = $model->getData('entity_id');
                if(!empty($model->mLpId)) {
                    $lp = new Model_FundLp;
                    $lp->mId = $model->mLpId;
                    $lp->select();
                    $entityId = $lp->getData('entity_id');
                }
                if(!empty($entityId)) {
                    $entity = new Model_Entity();
                    $ret = $entity->addWhere("id", $entityId)->select();
                    return ($ret ? ($entity->mName) : '(id='.$entityId.')' );
                }
            }],
            ['label'=>'授权LP','field'=>function($model)use(&$entity,&$lp){
                if (!empty($model->mLpId)) {
                    return 'id='.$model->getData('lp_id').',轮次='.$lp->getData('turn_sub');
                }
                return '所有LP';
            }],
            ['label'=>'操作人','field'=>function($model){
                $opId = $model->getData('operator_id');
                if ($opId == 0) {
                    return '系统自动';
                }

                $finder = new Model_Admin();
                $finder->mId = $opId;
                $finder->select();
                return $finder->getData('name');
            }],
            ['label'=>'创建时间','field'=>function($model){
                return date('Ymd H:i:s', $model->getData('create_time'));
            }],
        );

        $this->list_filter=array(
            new Page_Admin_TextForeignFilter(['name'=>'授权用户','paramName'=>'name|admin_id','foreignTable'=>'Model_Admin','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'授权主体','paramName'=>'name|entity_id','foreignTable'=>'Model_Entity','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'授权LP','paramName'=>'lp_id','fusion'=>false]),
        );

        $this->single_actions_default = ['delete'=>true,'edit'=>false];
        $this->multi_actions[] = ['label'=>'批量删除','required'=>true,'action'=>trim('/admin/EntityPermission?action=delete&id=__ids__'),'pre'=>'return confirm("操作后不可恢复，确认删除选中记录?");'];
    }

    public function _create(){
        unset($_REQUEST['id']);
        $adminIds = $_REQUEST['admin_id'];
        $lpIds = $_REQUEST['lp_id'];
        $entityIds = $_REQUEST['entity_id'];
        if($adminIds) {
            for($i = 0; $i < count($adminIds); $i++) {
                if ($lpIds) {
                    for($j = 0; $j < count($lpIds); $j++) {
                        $model = new Model_EntityPermission;
                        $model->setData([
                            'admin_id' => $adminIds[$i],
                            'lp_id' => $lpIds[$j],
                            'entity_id' => '',
                            'operator_id' => Model_Admin::getCurrentAdmin()->mId,
                            'create_time' => time()
                        ]);
                        $model->save();
                    }
                } elseif ($entityIds) {
                    for($j = 0; $j < count($entityIds); $j++) {
                        $model = new Model_EntityPermission;
                        $model->setData([
                            'admin_id' => $adminIds[$i],
                            'lp_id' => '',
                            'entity_id' => $entityIds[$j],
                            'operator_id' => Model_Admin::getCurrentAdmin()->mId,
                            'create_time' => time()
                        ]);
                        $model->save();
                    }
                } else {
                    $model = new Model_EntityPermission;
                    $model->setData([
                        'admin_id' => $adminIds[$i],
                        'lp_id' => '',
                        'entity_id' => '',
                        'operator_id' => Model_Admin::getCurrentAdmin()->mId,
                        'create_time' => time()
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


