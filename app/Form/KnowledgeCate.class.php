<?php

class Form_KnowledgeCate extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'大类ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'name','label'=>'名称','type'=>'text','default'=>null,'required'=>true,'validator'=>new Form_UniqueValidator(new Model_KnowledgeCate, 'name')],
                ['name'=>'_item_num','label'=>'知识点数量','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    $list = new Model_KnowledgeList;
                    $list->addWhere('cate_id', $model->getData('id'));
                    return "<div class=data_item><a href='/admin/knowledgeList?__filter=".urlencode("cate_id=$model->mId")."'> ".$list->count()."</div>";
                }],
                ['name'=>'content','label'=>'知识内容','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'reference','label'=>'参考资料','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'memo','label'=>'备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'operator','label'=>'创建人','type'=>'text','default'=>Model_Admin::getCurrentAdmin()->mName,'required'=>true,'readonly'=>true],
                ['name'=>'update_time','label'=>'更新时间','type'=>'datetime','readonly'=>'true','auto_update'=>true,'default'=>time(),'field'=>function($model){
                    return date('Ymd H:i:s', $model->getData('update_time'));
                }],
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
