<?php
class ItemPermissionController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_ItemPermission();
        $this->model->orderBy('id', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"交易记录授权",
        ));

        $this->form=new Form(array(
            array('name'=>'admin_id','label'=>'授权用户','type'=>"choosemodel",'model'=>'Model_Admin','default'=>null,'required'=>true,),
            array('name'=>'company_id','label'=>'授权项目','type'=>"choosemodel",'model'=>'Model_Company','default'=>null,'required'=>false,'show'=>'short'),
            array('name'=>'project_id','label'=>'授权交易','type'=>"choosemodel",'model'=>'Model_Project','default'=>null,'required'=>false,'show'=>'id'),
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
            ['label'=>'授权项目','field'=>function($model)use(&$company, &$project){
                $companyId = $model->getData('company_id');
                if(!empty($model->mProjectId)) {
                    $project = new Model_Project;
                    $project->mId = $model->mProjectId;
                    $project->select();
                    $companyId = $project->getData('company_id');
                }
                if(!empty($companyId)) {
                    $company = new Model_Company();
                    $ret = $company->addWhere("id", $companyId)->select();
                    return ($ret ? ($company->mShort) : '(id='.$companyId.')' );
                }
            }],
            ['label'=>'授权交易','field'=>function($model)use(&$company,&$project){
                if (!empty($model->mProjectId)) {
                    return 'id='.$model->getData('project_id').',轮次='.$project->getData('turn_sub');
                }
                return '所有交易';
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
            new Page_Admin_TextForeignFilter(['name'=>'授权项目','paramName'=>'name|company_id','foreignTable'=>'Model_Company','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'授权交易','paramName'=>'project_id','fusion'=>false]),
        );

        //$this->search_fields=array('name', 'id');
    }
}


