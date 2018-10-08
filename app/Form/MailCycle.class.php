<?php

class Form_MailCycle extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'strategy_id','label'=>'所属策略','type'=>'choosemodel','model'=>'Model_MailStrategy','default'=>isset($_GET['strategy_id'])?$_GET['strategy_id']:'','required'=>true,'field'=>function($model){
                    $st = new Model_MailStrategy;
                    $st->addWhere('id', $model->getData('strategy_id'));
                    $st->select();
                    return $st->getData('name');
                }],
                ['name'=>'field','label'=>'起点字段','type'=>'text','default'=>null,'required'=>true],
                ['name'=>'duration','label'=>'间隔时长','type'=>'text','default'=>null,'required'=>true],
                ['name'=>'unit','label'=>'时长单位','type'=>'choice','choices'=>Model_MailCycle::getDurationChoices(),'required'=>true,'default'=>'days'],
                ['name'=>'repeat','label'=>'重复次数','type'=>'text','default'=>1,'required'=>true],
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
