<?php

class Form_MailTrigger extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'条件ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'strategy_id','label'=>'所属策略','type'=>'choosemodel','model'=>'Model_MailStrategy','default'=>isset($_GET['strategy_id'])?$_GET['strategy_id']:'','required'=>true,'field'=>function($model){
                    $st = new Model_MailStrategy;
                    $st->addWhere('id', $model->getData('strategy_id'));
                    $st->select();
                    return $st->getData('name');
                }],
                ['name'=>'field','label'=>'引用字段','type'=>'text','default'=>null,'required'=>true],
                ['name'=>'value','label'=>'字段匹配值','type'=>'text','default'=>null,'required'=>true],
                ['name'=>'field_opr','label'=>'匹配运算','type'=>'choice','choices'=>Model_MailTrigger::getFieldOprChoices(),'required'=>true,],
                ['name'=>'logic_opr','label'=>'条件逻辑','type'=>'choice','choices'=>Model_MailTrigger::getLogicOprChoices(),'required'=>true,],
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
