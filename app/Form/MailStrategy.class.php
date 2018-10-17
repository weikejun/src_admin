<?php

class Form_MailStrategy extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'策略ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'name','label'=>'策略名称','type'=>'text','default'=>null,'required'=>true,'validator'=>new Form_UniqueValidator(new Model_MailStrategy, 'name'),'readonly'=>true],
                ['name'=>'mail_to','label'=>'收件人','type'=>'textarea','default'=>'{%$company->partner%};{%$company->manager%};{%$company->finance_person%};{%$company->legal_person%}','required'=>false,'placeholder'=>'收件人之间请用英文“;”分隔','help'=>'默认发送交易项目组成员'],
                ['name'=>'mail_cc','label'=>'抄送','type'=>'textarea','default'=>null,'required'=>false,'placeholder'=>'收件人之间请用英文“;”分隔'],
                ['name'=>'title','label'=>'邮件标题','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'content','label'=>'邮件内容','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'condition','label'=>'条件说明','type'=>'jsonArray','required'=>false],
                ['name'=>'cycle','label'=>'周期说明','type'=>'jsonArray','required'=>false],
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

    protected function _filter($value) {
        return $value;
    }
}
