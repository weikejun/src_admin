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
                ['name'=>'limit_source_type','label'=>'限制来源类型','type'=>'selectInput','choices'=>Model_ComplianceMatter::getLimitSourceTypeChoices(),'default'=>null,'required'=>false],
                ['name'=>'limit_source_memo','label'=>'限制来源备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'entity_id','label'=>'限制来源的基金','type'=>'choosemodel','model'=>'Model_Entity','required'=>false,'default'=>null,'field'=>function($model)use(&$entity) {
                    return $entity->getData('name');
                }],
                ['name'=>'_fund_description','label'=>'基金描述','type'=>'rawText','field'=>function($model)use(&$entity) {
                    return $entity->getData('description');
                }],
                ['name'=>'category','label'=>'事项归类','type'=>'choice','choices'=>Model_ComplianceMatter::getCategoryChoices(),'default'=>null,'required'=>false],
                ['name'=>'sub_cate','label'=>'事项小类','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'constrained_entitys','label'=>'相关受限实体','type'=>'selectInput','choices'=>Model_ComplianceMatter::getContrainedEntitysChoices(),'default'=>null,'required'=>false],
                ['name'=>'scene','label'=>'适用场景','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'field-index-requirement','label'=>'具体要求','type'=>'seperator'],
                ['name'=>'terms_raw','label'=>'条款序号及原文','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false],
                ['name'=>'requirement','label'=>'具体要求','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,'field'=>function($model) {
                    if ($model->getData('requirement')) {
                        return "<pre class='no_trim'>".$model->getData('requirement')."</pre>";
                    }
                }],
                ['name'=>'action_req','label'=>'动作要求','type'=>'selectInput','choices'=>Model_ComplianceMatter::getActionReqChoices(),'default'=>null,'required'=>false],
                ['name'=>'action_target','label'=>'动作对象','type'=>'selectInput','choices'=>Model_ComplianceMatter::getActionTargetChoices(),'default'=>null,'required'=>false,'help'=>'为特定LP的，写LP全称或实际控制人'],
                ['name'=>'action_freq','label'=>'动作频率','type'=>'selectInput','choices'=>Model_ComplianceMatter::getActionFreqChoices(),'default'=>null,'required'=>false],
                ['name'=>'field-index-potence','label'=>'效力','type'=>'seperator'],
                ['name'=>'potence','label'=>'效力情况','type'=>'choice','choices'=>Model_ComplianceMatter::getPotenceChoices(),'default'=>null,'required'=>false],
                ['name'=>'expiry','label'=>'有效期截止时间','type'=>'date','default'=>null,'required'=>false,'field'=>function($model) {
                    if ($model->getData('expiry')) {
                        return date('Ymd', $model->getData('expiry'));
                    }
                }],
                ['name'=>'expiry_memo','label'=>'有效期备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'field-index-member','label'=>'相关同事','type'=>'seperator'],
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
