<?php

class Form_KnowledgeChecklist extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'version','label'=>'版本','type'=>'text','default'=>null,'required'=>true,'placeholder'=>'唯一标识，比如“交易文件审阅要求v20181025”，可修改','validator'=>new Form_UniqueValidator(new Model_KnowledgeChecklist, 'version')],
                ['name'=>'list_info','label'=>'清单说明','type'=>'textarea','required'=>false],
                ['name'=>'content','label'=>'清单内容','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,'field'=>function($model) {
                    if ($model->getData('content')) {
                        return "<pre class='no_trim'>".$model->getData('content')."</pre>";
                    }
                }],
                ['name'=>'operator','label'=>'创建人','type'=>'text','default'=>Model_Admin::getCurrentAdmin()->mName,'required'=>true,'readonly'=>true],
                ['name'=>'update_time','label'=>'更新时间','type'=>'datetime','readonly'=>'true','default'=>time(),'null'=>false,'auto_update'=>true,'field'=>function($model){
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
