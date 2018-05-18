<?php
class CompanyController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportToCsvAction;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        WinRequest::mergeModel(array(
            'controllerText'=>"目标公司",
            'tableWrap' => "2048px",
        ));
        $this->model=new Model_Company();
        $this->model->orderBy('update_time', 'DESC');

        $this->form=new Form(array(
            array('name'=>'name','label'=>'公司全称','type'=>"text",'default'=>null,'required'=>true,'help'=>'我是一个说明'),
            array('name'=>'short','label'=>'项目名称','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'bussiness','label'=>'所属行业','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'init_manager','label'=>'项目初始负责人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'current_manager','label'=>'项目当前负责人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'legal_person','label'=>'法务负责人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'director','label'=>'董事','type'=>"text", 'default'=>'无董事席位','required'=>false,),
            array('name'=>'director_turn','label'=>'董事委派轮次','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'director_status','label'=>'董事状态','type'=>"choice",'choices'=>[['不适用','不适用'],['在职','在职'],['取消原席位','取消原席位'],['待工商登记','待工商登记']], 'default'=>'不适用','required'=>true,),
            array('name'=>'filling_keeper','label'=>'文件Filing保管人','type'=>"text",'default'=>null,'required'=>false),
            array('name'=>'total_stock','label'=>'总股数','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime", 'default'=>time(),'required'=>false,'auto_update'=>true, 'readonly'=>true),
        ));
        $shareholding = 0;
        $projectCache = new Model_Project;
        $this->list_display=array(
            ['label'=>'公司ID','field'=>function($model)use(&$projectCache,&$shareholding){
                $project = new Model_Project;
                $project->orderBy('id', 'DESC')->limit(1);
                $project = $this->_getResource($model->mId, 'Project', $project, 'company_id');
                if (is_array($project)) {
                    $projectCache = $project[0];
                }
                $project = new Model_Project;
                $findField = 'company_id';
                $project->addComputedCol('SUM(stocknum_new)', 'total_shareholding');
                $project->addWhere($findField, $model->mId);
                $project->groupBy($findField);
                $project->setCols($findField);
                $project->select();
                $data = $project->getData();
                $shareholding = isset($data['total_shareholding']) ? $data['total_shareholding'] : 0;
                return $model->mId;
            }],
            ['label'=>'公司名称','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'所属行业','field'=>function($model){
                return $model->mBussiness;
            }],
            ['label'=>'总股数','field'=>function($model){
                return number_format($model->mTotalStock);
            }],
            ['label'=>'投资记录','field'=>function($model){
                $project = new Model_Project;
                $project->addWhere('company_id', $model->mId);
                return "<div class=data_item><a href='/admin/project?__filter=".urlencode("name|company_id=$model->mName")."'> ".$project->count()." </a><a class=item_op href='/admin/project?action=read&company_id=$model->mId'> +新增 </a></div>";
            }],
            ['label'=>'当前轮次','field'=>function($model)use(&$projectCache){
                return "<div class=data_item><a href='/admin/project?__filter=".urlencode("id=$projectCache->mId")."'> $projectCache->mTurn </a><a class=item_op href='/admin/project?action=read&id=$projectCache->mId'> +复制 </a></div>";
            }],
            ['label'=>'当前估值','field'=>function($model)use(&$projectCache){
                return number_format($projectCache->mPreMoney + $projectCache->mFinancingAmount);
            }],
            ['label'=>'当前合计持股','field'=>function($model)use(&$shareholding){
                return number_format($shareholding);
            }],
            ['label'=>'当前合计持股比例','field'=>function($model)use(&$shareholding){
                return sprintf("%.2f%%", $shareholding / $model->mTotalStock * 100);
            }],
            ['label'=>'当前持股价值','field'=>function($model)use(&$shareholding, &$projectCache){
                return number_format($shareholding / $model->mTotalStock * ($projectCache->mPreMoney + $projectCache->mFinancingAmount));
            }],
            ['label'=>'项目初始负责人','field'=>function($model){
                return $model->mInitManager;
            }],
            ['label'=>'项目当前负责人','field'=>function($model){
                return $model->mCurrentManager;
            }],
            ['label'=>'法务部人员','field'=>function($model){
                return $model->mLegalPerson;
            }],
            ['label'=>'董事姓名','field'=>function($model){
                return $model->mDirector;
            }],
            ['label'=>'董事状态','field'=>function($model){
                return $model->mDirectorStatus;
            }],
            ['label'=>'委派轮次','field'=>function($model){
                return $model->mDirectorTurn;
            }],
            ['label'=>'观察员','field'=>function($model)use(&$projectCache){
                return $projectCache->mObserver;
            }],
            ['label'=>'信息权','field'=>function($model)use(&$projectCache){
                return $projectCache->mInfoRight;
            }],
            ['label'=>'信息权门槛','field'=>function($model)use(&$projectCache){
                return $projectCache->mInfoRightThreshold;
            }],
            ['label'=>'文件Filling保管人','field'=>function($model){
                return $model->mFillingKeeper;
            }],
        );

        $this->single_actions=[
            /*['label'=>'投资记录','action'=>function($model){
                return '/admin/project?__filter='.urlencode('company_id='.$model->mId);
            }],*/
        ];

        $this->multi_actions=array(
            array('label'=>'导出csv','required'=>false,'action'=>'/admin/company/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        );

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'公司名称','paramName'=>'name','fusion'=>true]),
        );
    }
}


