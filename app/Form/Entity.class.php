<?php

class Form_Entity extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'name','label'=>'名称','type'=>'text','default'=>null,'required'=>true,'help'=>'填入准确全称'],
                ['name'=>'register_country','label'=>'注册国/省','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'description','label'=>'描述','type'=>'text','default'=>null,'required'=>false,'help'=>'示例“人民币早期一期主基金”，“美元专项基金SPV”'],
                ['name'=>'tp','label'=>'类型','type'=>'selectInput','choices'=>Model_Entity::getTpChoices(),'required'=>false,'help'=>'可问财务同事'],
                ['name'=>'co_investment','label'=>'co-investment','type'=>'choice','choices'=>[['是','是'],['否','否']],'default'=>'否','required'=>false,'help'=>'主基金都不是，非主基金的和财务同事确认。'],
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
