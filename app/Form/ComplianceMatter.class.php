<?php

class Form_ComplianceMatter extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'事项ID','type'=>'rawText','default'=>null,'field'=>function($model)use(&$entity) {
                    $entity = new Model_Entity;
                    $entity->addWhere('id', $model->getData('entity_id'));
                    $entity->select();
                    return $model->getData('id');
                }],
                ['name'=>'entity_id','label'=>'基金','type'=>'choosemodel','model'=>'Model_Entity','required'=>true,'default'=>(isset($_GET['entity_id'])?$_GET['entity_id']:null),'field'=>function($model)use(&$entity) {
                    return $entity->getData('name');
                }],
                ['name'=>'_fund_description','label'=>'基金描述','type'=>'rawText','field'=>function($model)use(&$entity) {
                    return $entity->getData('description');
                }],
                ['name'=>'limit_source','label'=>'限制来源','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'category','label'=>'事项归类','type'=>'selectInput','choices'=>Model_ComplianceMatter::getCategoryChoices(),'default'=>null,'required'=>false],
                ['name'=>'sub_cate','label'=>'事项小类','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'scene','label'=>'场景','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'requirement','label'=>'具体要求','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false],
                ['name'=>'expiry','label'=>'有效期','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'action_req','label'=>'动作要求','type'=>'choice','choices'=>Model_ComplianceMatter::getActionReqChoices(),'default'=>null,'required'=>false],
                ['name'=>'action_target','label'=>'动作对象','type'=>'choice','choices'=>Model_ComplianceMatter::getActionTargetChoices(),'default'=>null,'required'=>false],
                ['name'=>'terms_from','label'=>'条款来源','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false],
                ['name'=>'terms_raw','label'=>'条款原文','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false],
                ['name'=>'_legal_person','label'=>'法务负责人','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$entity){
                    $person = 'legal_person';
                    $members = Model_Member::listAll();
                    return isset($members[$entity->getData($person)]) ? $members[$entity->getData($person)]->mName : $entity->getData($person);
                }],
                ['name'=>'_finance_person','label'=>'财务负责人','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$entity){
                    $person = 'finance_person';
                    $members = Model_Member::listAll();
                    return isset($members[$entity->getData($person)]) ? $members[$entity->getData($person)]->mName : $entity->getData($person);
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
