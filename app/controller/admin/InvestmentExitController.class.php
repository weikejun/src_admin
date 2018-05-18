<?php
class InvestmentExitController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        WinRequest::mergeModel(array(
            'controllerText'=>"退出记录",
        ));
        $this->model=new Model_InvestmentExit();
        $this->model->orderBy('update_time', 'DESC');

        function setCompanyId($model) {
            $project = new Model_Project;
            $project->mId = $model->mProjectId;
            $project->select();
            if($project->mCompanyId) {
                $model->addWhere('id', $model->mId)->update(['company_id'=>[$project->mCompanyId, DBTable::NO_ESCAPE]]);
            }
        };

        $this->model->on('after_update', function($model) {
            setCompanyId($model);
        });
        $this->model->on('after_insert', function($model) {
            setCompanyId($model);
        });

        $this->form=new Form(array(
            array('name'=>'project_id','label'=>'交易ID','type'=>"choosemodel",'model'=>'Model_Project','default'=>$_GET['project_id'],'required'=>true,'show'=>'id'),
            array('name'=>'company_id','label'=>'公司ID','type'=>"hidden",'default'=>0,'required'=>false),
            array('name'=>'amount','label'=>'退出金额','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'currency','label'=>'计价货币','type'=>"choice",'choices'=>[['USD','USD'],['RMB','RMB'],['HKD','HKD']], 'default'=>'USD','required'=>true,),
            array('name'=>'exit_way','label'=>'退出方式','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'exit_num','label'=>'退出股数','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'exit_time','label'=>'退出日期','type'=>"date",'default'=>null,'null'=>true,),
            array('name'=>'memo','label'=>'退出备注','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime","readonly"=>'true','default'=>time(),'null'=>false,'auto_update'=>true),
        ));
        $projectCache = new Model_project;
        $companyCache = new Model_Company;
        $this->list_display=array(
            ['label'=>'记录ID','field'=>function($model)use(&$projectCache,&$companyCache){
                $projectCache->mId = $model->mProjectId;
                $projectCache->select();
                $companyCache->mId = $model->mCompanyId;
                $companyCache->select();
                return $model->mId;
            }],
            ['label'=>'交易ID','field'=>function($model){
                return $model->mProjectId;
            }],
            ['label'=>'项目名称','field'=>function($model)use(&$companyCache){
                return $companyCache->mShort;
            }],
            ['label'=>'退出金额','field'=>function($model){
                return number_format($model->mAmount,2) . ' ' . $model->mCurrency;
            }],
            ['label'=>'退出轮次','field'=>function($model)use(&$projectCache){
                return $projectCache->mTurn;
            }],
            ['label'=>'退出方式','field'=>function($model){
                return $model->mExitWay;
            }],
            ['label'=>'退出股数','field'=>function($model){
                return number_format($model->mExitNum);
            }],
            ['label'=>'退出日期','field'=>function($model){
                return date('Y-m-d H:i:s',$model->mExitTime);
            }],
            ['label'=>'退出备注','field'=>function($model){
                return $model->mMemo;
            }],
            ['label'=>'更新时间','field'=>function($model){
                return date('Y-m-d H:i:s',$model->mUpdateTime);
            }],
        );

        $this->single_actions=[
            /*['label'=>'投资记录','action'=>function($model){
                return '/admin/project?__filter='.urlencode('project_id='.$model->mProjectId);
            }],*/
        ];

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'交易ID','paramName'=>'project_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'公司ID','paramName'=>'company_id','fusion'=>false]),
        );
    }
}


