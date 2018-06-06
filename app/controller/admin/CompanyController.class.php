<?php
class CompanyController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        WinRequest::mergeModel(array(
            'controllerText'=>"目标企业",
            'tableWrap' => "3072px",
        ));
        $this->model=new Model_Company();
        $this->model->orderBy('update_time', 'DESC');

        $this->form=new Form(array(
            ['name'=>'name','label'=>'目标企业','type'=>"text",'default'=>null,'required'=>true,'help'=>'填入企业融资平台准确全称'],
            ['name'=>'short','label'=>'项目简称','type'=>"text",'default'=>null,'required'=>true,'help'=>'填入项目唯一简称，后续变动可此处修改。'],
            ['name'=>'_total_stock','label'=>'最新企业总股数','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_company_character','label'=>'当前目标企业性质','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'bussiness','label'=>'所属行业','type'=>"selectInput",'choices'=>Model_Company::getBussinessChoices(),'required'=>true,],
            ['name'=>'bussiness_change','label'=>'主营行业变化','type'=>'selectInput','choices'=>[['未变化','未变化']],'required'=>false],
            ['name'=>'region','label'=>'所属地域','type'=>'selectInput','choices'=>Model_Company::getRegionChoices(),'required'=>true],
            ['name'=>'field-index-financing','label'=>'融资信息','type'=>'seperator'],
            ['name'=>'_latest_value','label'=>'最新估值','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_first_invest_turn','label'=>'首次投时轮次归类','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_latest_invest_turn','label'=>'最新轮次归类','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_financing_no','label'=>'投后融资轮次','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_first_company_period','label'=>'首次投时企业阶段','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_latest_company_period','label'=>'最新企业阶段','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'field-index-enterexit','label'=>'源码投退信息','type'=>'seperator'],
            ['name'=>'_captable','label'=>'投退CapTable','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_first_close_date','label'=>'首次投资交割日期','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_have_exit','label'=>'是否发生过退出','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_lastest_shareholding_sum','label'=>'最新各主体合计持股数','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_lastest_shareholding_ratio_sum','label'=>'最新各主体合计股比','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_hold_status','label'=>'持有状态','type'=>"rawText",'required'=>false,],
            ['name'=>'_multi_entity_invest','label'=>'是否多主体投过','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_multi_entity_hold','label'=>'当前是否多主体持股','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_multi_currency_entity_invest','label'=>'是否被美元+人民币主体投过','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_multi_currency_entity_hold','label'=>'当前是否被美元+人民币主体持有','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_entity_odi','label'=>'源码主体ODI','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'field-index-govern','label'=>'企业治理','type'=>'seperator'],
            ['name'=>'_director_turn','label'=>'董事委派轮次','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_director_name','label'=>'最新源码董事姓名','type'=>"rawText", 'default'=>'无董事席位','required'=>false,],
            ['name'=>'_director_status','label'=>'最新源码董事状态','type'=>"rawText",'required'=>false,],
            ['name'=>'_observer','label'=>'最新源码观察员','type'=>"rawText",'required'=>false,],
            ['name'=>'_holder_veto','label'=>'最新股东会Veto','type'=>"rawText",'required'=>false,],
            ['name'=>'_board_veto','label'=>'最新董事会Veto','type'=>"rawText",'required'=>false,],
            ['name'=>'field-index-return','label'=>'源码投资回报','type'=>'seperator'],
            ['name'=>'_invest_amount','label'=>'历史总投资金额','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_hold_value','label'=>'当前持股账面价值','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_hold_return_rate','label'=>'在管投资回报倍数','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_exit_amount','label'=>'已退出合同金额','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_exit_amount_cost','label'=>'已退出金额对应成本','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'_exit_return_rate','label'=>'已退出部分回报率','type'=>"rawText", 'default'=>null,'required'=>false,],
            ['name'=>'field-index-staff','label'=>'当前项目组成员','type'=>'seperator'],
            ['name'=>'partner','label'=>'主管合伙人','type'=>"text", 'default'=>null,'required'=>false,],
            ['name'=>'manager','label'=>'项目负责人','type'=>"text", 'default'=>null,'required'=>false,],
            ['name'=>'legal_person','label'=>'法务负责人','type'=>"text", 'default'=>null,'required'=>false,],
            ['name'=>'finance_person','label'=>'财务负责人','type'=>"text", 'default'=>null,'required'=>false,],
            ['name'=>'field-index-filing','label'=>'工商及Filing','type'=>'seperator'],
            ['name'=>'_aic_status','label'=>'人民币项目工商','type'=>"rawText",'required'=>false,],
            ['name'=>'_filing_status','label'=>'Filing是否完整','type'=>"rawText",'required'=>false,],
            ['name'=>'filling_keeper','label'=>'文件Filing保管人','type'=>"text",'default'=>null,'required'=>false],
            ['name'=>'field-index-memo','label'=>'备注及未决事项','type'=>'seperator'],
            ['name'=>'_pending_detail','label'=>'未决事项说明','type'=>"rawText",'required'=>false,],
            ['name'=>'memo','label'=>'备注','type'=>"textarea",'required'=>false],
            ['name'=>'update_time','label'=>'更新时间','type'=>"datetime", 'default'=>time(),'required'=>false,'auto_update'=>true, 'readonly'=>true],
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
            ['label'=>'交易记录','field'=>function($model){
                $project = new Model_Project;
                $project->addWhere('company_id', $model->mId);
                return "<div class=data_item><a href='/admin/project?__filter=".urlencode("name|company_id=$model->mName")."'> ".$project->count()." </a><a class=item_op href='/admin/project?action=read&company_id=$model->mId'> +新增 </a></div>";
            }],
            ['label'=>'当前轮次','field'=>function($model)use(&$projectCache){
                if ($projectCache->mId)
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
            ['label'=>'审阅','action'=>function($model){
                return '/admin/systemLog/diff?resource=company&res_id='.$model->mId;
            }],
        ];

        $this->single_actions_default = [
            'delete' => false,
            'edit' => true,
        ];

        $this->multi_actions=array(
            array('label'=>'导出csv','required'=>false,'action'=>'/admin/company/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        );

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'公司ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'公司名称','paramName'=>'name','fusion'=>true]),
        );
    }
}


