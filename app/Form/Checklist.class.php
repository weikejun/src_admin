<?php

class Form_Checklist extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'version','label'=>'版本','type'=>'text','default'=>null,'required'=>true,'placeholder'=>'唯一标识，比如“合规要求v20181025”，不可修改','validator'=>new Form_UniqueValidator(new Model_Checklist, 'version'),'readonly'=>true],
                ['name'=>'field','label'=>'字段类型','type'=>'choice','choices'=>Model_Checklist::getFieldChoices(),'required'=>true],
                ['name'=>'content','label'=>'清单内容','type'=>'message','default'=>null,'required'=>false,'field'=>function($model) {
                    $list = json_decode($model->getData('content'));
                    if ($list) {
                        $output = '';
                        foreach($list as $li) {
                            $output .= $li . "\n";
                        }
                    }
                    return $output;
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
