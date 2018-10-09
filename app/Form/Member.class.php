<?php

class Form_Member extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'name','label'=>'姓名','type'=>'text','default'=>null,'required'=>true],
                ['name'=>'mail','label'=>'邮箱','type'=>'text','default'=>null,'required'=>true,'validator'=>new Form_UniqueValidator(new Model_Member, 'mail')],
                ['name'=>'create_time','label'=>'创建时间','type'=>'datetime','readonly'=>'true','default'=>time(),'field'=>function($model){
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
