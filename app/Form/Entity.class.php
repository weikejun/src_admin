<?php

class Form_Entity extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'主体ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'name','label'=>'名称','type'=>'text','default'=>null,'required'=>true,'help'=>'填入准确全称','validator'=>new Form_UniqueValidator(new Model_Entity, 'name')],
                ['name'=>'_hold_company','label'=>'持股企业','type'=>'rawText','field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('entity_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->setCols('company_id');
                    $project->groupBy('company_id');
                    $deals = $project->find();
                    $companyIds = [];
                    foreach($deals as $i => $deal) {
                        $companyIds[] = $deal->getData('company_id');
                    }
                    return '<a target="_blank" href="/admin/company?__filter='.urlencode('id='.implode($companyIds, ',')).'">'.count($companyIds).'</a>';
                }],
                ['name'=>'_invest_num','label'=>'投资记录','type'=>'rawText','field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('entity_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    return '<a href="/admin/project?__filter='.urlencode('entity_id='.$model->getData('id')).'">'.$project->count().'</a>';
                }],
                ['name'=>'_exit_num','label'=>'退出记录','type'=>'rawText','field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('exit_entity_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    return '<a href="/admin/project?__filter='.urlencode('exit_entity_id='.$model->getData('id')).'">'.$project->count().'</a>';
                }],
                ['name'=>'register_country','label'=>'注册国/省','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'description','label'=>'描述','type'=>'text','default'=>null,'required'=>false,'help'=>'示例“人民币早期一期主基金”，“美元专项基金SPV”'],
                ['name'=>'cate','label'=>'类型','type'=>'selectInput','choices'=>Model_Entity::getCateChoices(),'required'=>false],
                ['name'=>'tp','label'=>'类别','type'=>'selectInput','choices'=>Model_Entity::getTpChoices(),'required'=>false,'help'=>'可问财务同事'],
                ['name'=>'org_type','label'=>'组织形式','type'=>'selectInput','choices'=>Model_Entity::getOrgTypeChoices(),'required'=>false],
                ['name'=>'co_investment','label'=>'co-investment','type'=>'choice','choices'=>Model_Entity::getCoInvestmentChoices(),'default'=>'否','required'=>false,'help'=>'主基金都不是，非主基金的和财务同事确认。'],
                ['name'=>'currency','label'=>'资金货币','type'=>'choice','choices'=>Model_Project::getCurrencyChoices(),'default'=>'USD','required'=>false,],
                ['name'=>'memo','label'=>'备注','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'update_time','label'=>'更新时间','type'=>'datetime','readonly'=>'true','default'=>time(),'null'=>false,'auto_update'=>true,'field'=>function($model){
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
