<?php

class Form_Company extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'企业ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'name','label'=>'目标企业','type'=>'text','default'=>null,'required'=>true,'help'=>'填入企业融资平台准确全称'],
                ['name'=>'short','label'=>'项目简称','type'=>'text','default'=>null,'required'=>true,'help'=>'填入项目唯一简称，后续变动可此处修改。'],
                ['name'=>'_deal_num','label'=>'交易次数','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    return "<div class=data_item><a href='/admin/project?__filter=".urlencode("name|company_id=$model->mName")."'> ".$project->count()." </a><!--a class=item_op href='/admin/project?action=read&company_id=$model->mId'> +新增 </a--></div>";
                }],
                ['name'=>'_stocknum_all','label'=>'最新企业总股数','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->orderBy('id', 'DESC');
                    $project->select();
                    return $project->getData('stocknum_all');
                }],
                ['name'=>'_company_character','label'=>'当前目标企业性质','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->orderBy('id', 'DESC');
                    $project->select();
                    return $project->getData('company_character');
                }],
                ['name'=>'bussiness','label'=>'所属行业','type'=>'selectInput','choices'=>Model_Company::getBussinessChoices(),'required'=>true,],
                ['name'=>'bussiness_change','label'=>'主营行业变化','type'=>'selectInput','choices'=>[['未变化','未变化']],'required'=>false],
                ['name'=>'region','label'=>'所属地域','type'=>'selectInput','choices'=>Model_Company::getRegionChoices(),'required'=>true],
                ['name'=>'field-index-financing','label'=>'融资信息','type'=>'seperator'],
                ['name'=>'_latest_post_moeny','label'=>'最新估值','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->orderBy('id', 'DESC');
                    $project->limit(1);
                    $project->select();
                    return $project->getData('post_money');
                }],
                ['name'=>'_first_invest_turn','label'=>'首次投时轮次归类','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->orderBy('id', 'ASC');
                    $project->select();
                    return $project->getData('turn');
                }],
                ['name'=>'_latest_invest_turn','label'=>'最新轮次归类','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    // TODO: 是否需要考虑交易状态或者交易类型？比如退出不计算
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->addWhere('deal_type', '源码退出');
                    $project->orderBy('id', 'DESC');
                    $project->select();
                    return $project->getData('turn');
                }],
                ['name'=>'_financing_no','label'=>'源码投后融资轮次','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->setCols('turn_sub');
                    $project->groupBy('turn_sub');
                    $project = $project->find();
                    if ($project) {
                        return count($project) - 1;
                    }
                }],
                ['name'=>'_first_company_period','label'=>'首次投时企业阶段','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->orderBy('id', 'ASC');
                    $project->select();
                    return $project->getData('company_period');
                }],
                ['name'=>'_latest_company_period','label'=>'最新企业阶段','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->orderBy('id', 'DESC');
                    $project->select();
                    return $project->getData('company_period');
                }],
                ['name'=>'field-index-enterexit','label'=>'源码投退信息','type'=>'seperator'],
                ['name'=>'_captable','label'=>'投退CapTable','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    return '<a href="javascript:void 0">查看</a>';
                }],
                ['name'=>'_first_close_date','label'=>'首次投资交割日期','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->orderBy('id', 'ASC');
                    $project->select();
                    return $project->getData('close_date') ? date('Ymd', $project->getData('close_date')) : '';
                }],
                ['name'=>'_have_exit','label'=>'是否发生过退出','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->addWhere('deal_type', '源码退出');
                    return $project->count() ? '是' : '否';
                }],
                ['name'=>'_lastest_shareholding_sum','label'=>'最新各主体合计持股数','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->setCols('deal_type');
                    $project->addComputedCol('SUM(stocknum_get)', 'stock_num');
                    $project->groupBy('deal_type');
                    $project->find();
                    $stockNum = 0;
                    foreach($project->getData() as $data) {
                        if ($data['deal_type'] == '源码退出') {
                            $stockNum -= $data['stock_num'];
                        } else {
                            $stockNum += $data['stock_num'];
                        }
                    }
                    return $stockNum;
                }],
                ['name'=>'_lastest_shareholding_ratio_sum','label'=>'最新各主体合计股比','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->setCols('deal_type');
                    $project->addComputedCol('SUM(stocknum_get)', 'stock_num');
                    $project->groupBy('deal_type');
                    $project->find();
                    $stockNum = 0;
                    foreach($project->getData() as $data) {
                        if ($data['deal_type'] == '源码退出') {
                            $stockNum -= $data['stock_num'];
                        } else {
                            $stockNum += $data['stock_num'];
                        }
                    }
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->orderBy('id', 'DESC');
                    $project->limit(1);
                    $project->select();
                    return $project->getData('stocknum_all') 
                        ? sprintf("%.2f%%", $stockNum / $project->getData('stocknum_all') * 100) 
                        : '0.00%';
                }],
                ['name'=>'_hold_status','label'=>'源码持有状态','type'=>'rawText','required'=>false,'field'=>function($model){
                    // TODO: 逻辑需要补充
                    return '算法不明确，待讨论';
                }],
                ['name'=>'_multi_entity_invest','label'=>'是否多主体投过','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->setCols('entity_id');
                    $project->groupBy('entity_id');
                    $project = $project->find();
                    return count($project) > 1 ? '是' : '否';
                }],
                ['name'=>'_multi_entity_hold','label'=>'当前是否多主体持股','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    return '算法待补充';
                }],
                ['name'=>'_multi_currency_entity_invest','label'=>'是否被美元+人民币主体投过','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->setCols('entity_id');
                    $project->groupBy('entity_id');
                    $project = $project->find();
                    return '算法待补充';
                }],
                ['name'=>'_multi_currency_entity_hold','label'=>'当前是否被美元+人民币主体持有','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    return '算法待补充';
                }],
                ['name'=>'_entity_odi','label'=>'源码主体ODI','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    return '算法不明确，待讨论';
                }],
                ['name'=>'field-index-govern','label'=>'企业治理','type'=>'seperator'],
                ['name'=>'_director_turn','label'=>'董事委派轮次','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    return '算法不明确，待讨论';
                }],
                ['name'=>'_director_name','label'=>'最新源码董事姓名','type'=>'rawText','default'=>'无董事席位','required'=>false,'field'=>function($model){
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->orderBy('id', 'DESC');
                    $project->select();
                    return $project->getData('our_board_person');
                }],
                ['name'=>'_director_status','label'=>'最新源码董事状态','type'=>'rawText','required'=>false,'field'=>function($model){
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->orderBy('id', 'DESC');
                    $project->select();
                    return $project->getData('our_board_status');
                }],
                ['name'=>'_observer','label'=>'最新源码观察员','type'=>'rawText','required'=>false,'field'=>function($model){
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->orderBy('id', 'DESC');
                    $project->select();
                    return $project->getData('observer');
                }],
                ['name'=>'_holder_veto','label'=>'最新股东会Veto','type'=>'rawText','required'=>false,'field'=>function($model){
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->orderBy('id', 'DESC');
                    $project->select();
                    return $project->getData('holder_veto');
                }],
                ['name'=>'_board_veto','label'=>'最新董事会Veto','type'=>'rawText','required'=>false,'field'=>function($model){
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->orderBy('id', 'DESC');
                    $project->select();
                    return $project->getData('board_veto');
                }],
                ['name'=>'field-index-return','label'=>'源码投资回报','type'=>'seperator'],
                ['name'=>'_invest_amount','label'=>'历史总投资金额','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->addWhere('deal_type', '源码退出', '<>');
                    $project->addComputedCol('SUM(our_amount)','total_amount');
                    $project->setCols('company_id');
                    $project->select();
                    $data = $project->getData();
                    return number_format($data['total_amount']);
                }],
                ['name'=>'_hold_value','label'=>'当前持股账面价值','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    return '算法待补充';
                }],
                ['name'=>'_hold_return_rate','label'=>'在管投资回报倍数','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    return '算法待补充';
                }],
                ['name'=>'_exit_amount','label'=>'已退出合同金额','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    return '算法待补充';
                }],
                ['name'=>'_exit_amount_cost','label'=>'已退出金额对应成本','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    return '算法待补充';
                }],
                ['name'=>'_exit_return_rate','label'=>'已退出部分回报率','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    return '算法待补充';
                }],
                ['name'=>'field-index-staff','label'=>'当前项目组成员','type'=>'seperator'],
                ['name'=>'partner','label'=>'主管合伙人','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'manager','label'=>'项目负责人','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'legal_person','label'=>'法务负责人','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'finance_person','label'=>'财务负责人','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'field-index-filing','label'=>'工商及Filing','type'=>'seperator'],
                ['name'=>'_aic_status','label'=>'人民币项目工商','type'=>'rawText','required'=>false,'field'=>function($model){
                    return '算法待补充';
                }],
                ['name'=>'_filing_status','label'=>'Filing是否完整','type'=>'rawText','required'=>false,'field'=>function($model){
                    return '算法待补充';
                }],
                ['name'=>'filling_keeper','label'=>'文件Filing保管人','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'field-index-memo','label'=>'备注及未决事项','type'=>'seperator'],
                ['name'=>'_pending_detail','label'=>'未决事项说明','type'=>'rawText','required'=>false,'field'=>function($model){
                    return '算法待补充';
                }],
                ['name'=>'memo','label'=>'备注','type'=>'textarea','required'=>false],
                ['name'=>'update_time','label'=>'更新时间','type'=>'datetime','default'=>time(),'required'=>false,'auto_update'=>true,'readonly'=>true,'field'=>function($model){
                    return date('Ymd H:i:s', $model->getData('update_time'));
                }],
            ];
        }
        return self::$fieldsMap;
    }

    public function __construct() {
        parent::__construct(self::getFieldsMap());
    }
}
