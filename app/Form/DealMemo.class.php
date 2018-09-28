<?php

class Form_DealMemo extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'备忘ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'project_id','label'=>'交易ID','type'=>'choosemodel','model'=>'Model_Project','default'=>isset($_GET['project_id'])?$_GET['project_id']:'','required'=>true, 'show'=>'id'],
                ['name'=>'title','label'=>'备忘事项','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'content','label'=>'备忘内容','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'operator','label'=>'创建人','type'=>'text','default'=>Model_Admin::getCurrentAdmin()->mName,'required'=>true,'readonly'=>true],
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
