<?php

class Form_DealDecision extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'意见ID','type'=>'rawText'],
                ['name'=>'project_id','label'=>'交易ID','type'=>'choosemodel','model'=>'Model_Project','default'=>isset($_GET['project_id'])?$_GET['project_id']:'','required'=>true, 'show'=>'id'],
                ['name'=>'_company_short','label'=>Form_Company::getFieldViewName('short'),'type'=>'rawText','field'=>function($model)use(&$project,&$company) {
                    $project = new Model_Project;
                    $project->addWhere('id', $model->getData('project_id'));
                    $project->select();
                    if ($project->mId) {
                        $company = new Model_Company;
                        $company->addWhere('id', $project->getData('company_id'));
                        $company->select();
                        return $company->mId ? $company->getData('short') : '';
                    }
                }],
                ['name'=>'_project_turn_sub','label'=>Form_Project::getFieldViewName('turn_sub'),'type'=>'rawText','field'=>function($model)use(&$project,&$company) {
                    return $project->mId ? $project->getData('turn_sub') : '';
                }],
                ['name'=>'_project_ts_decision_amount','label'=>Form_Project::getFieldViewName('ts_decision_amount'),'type'=>'rawText','field'=>function($model)use(&$project,&$company) {
                    return $project->mId ? $project->getData('ts_decision_amount') : '';
                }],
                ['name'=>'_project_ts_ratio','label'=>Form_Project::getFieldViewName('ts_ratio'),'type'=>'rawText','field'=>function($model)use(&$project,&$company) {
                    return $project->mId ? $project->getData('ts_ratio') : '';
                }],
                ['name'=>'partner','label'=>'审批人','type'=>'choosemodel','model'=>'Model_Member','default'=>null,'required'=>true,'field'=>function($model) {
                    return Model_Member::getNameById($model->getData('partner')).':'.Model_Member::getEmailById($model->getData('partner'));
                }],
                ['name'=>'decision','label'=>'投决意见','type'=>'choice','choices'=>Model_DealDecision::getDecisionChoices(),'default'=>null,'required'=>false,],
                ['name'=>'memo','label'=>'备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'ip','label'=>'来源IP','type'=>'text','default'=>null,'readonly'=>true,'required'=>false],
                ['name'=>'sign_key','label'=>'校验码','type'=>'text','readonly'=>true,'default'=>Model_DealDecision::signData(),'required'=>true],
                ['name'=>'expiration','label'=>'审批时间','type'=>'datetime','default'=>null,'field'=>function($model){
                    if ($model->getData('expiration'))
                        return date('Ymd H:i:s', $model->getData('expiration'));
                }],
                ['name'=>'update_time','label'=>'更新时间','type'=>'datetime','readonly'=>'true','default'=>null,'auto_update'=>true,'field'=>function($model){
                    return date('Ymd H:i:s', $model->getData('update_time'));
                }],
                ['name'=>'create_time','label'=>'创建时间','type'=>'datetime','readonly'=>'true','default'=>time(),'null'=>false,'field'=>function($model){
                    return date('Ymd H:i:s', $model->getData('create_time'));
                }],
            ];
        }
        return self::$fieldsMap;
    }

    public function __construct() {
        parent::__construct(self::getFieldsMap());
    }

}
