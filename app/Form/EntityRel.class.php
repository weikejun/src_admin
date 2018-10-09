<?php

class Form_EntityRel extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'parent_id','label'=>'父主体','type'=>'choosemodel','model'=>'Model_Entity','required'=>true,'default'=>(isset($_GET['parent_id'])?$_GET['parent_id']:null),'field'=>function($model) {
                    $entity = new Model_Entity;
                    $entity->addWhere('id', $model->getData('parent_id'));
                    $entity->select();
                    return $entity->getData('name');
                }],
                ['name'=>'sub_id','label'=>'子主体','type'=>'choosemodel','model'=>'Model_Entity','required'=>true,'default'=>(isset($_GET['sub_id'])?$_GET['sub_id']:null),'field'=>function($model) {
                    $entity = new Model_Entity;
                    $entity->addWhere('id', $model->getData('sub_id'));
                    $entity->select();
                    return $entity->getData('name');
                },'validator'=>function($values) {
                    if ($values['parent_id'] == $values['sub_id']) {
                        return '父子主体不能相同';
                    }
                    $rel = new Model_EntityRel;
                    $rel->addWhere('parent_id', $values['parent_id']);
                    $rel->addWhere('sub_id', $values['sub_id']);
                    if ($rel->count()) {
                        return '关系已经存在';
                    }
                    return true;
                }],
                ['name'=>'create_time','label'=>'更新时间','type'=>'datetime','readonly'=>'true','default'=>time(),'null'=>false,'field'=>function($model){
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
