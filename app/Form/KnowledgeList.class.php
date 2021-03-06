<?php

class Form_KnowledgeList extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'知识ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'cate_id','label'=>'大类名称','type'=>'choosemodel','model'=>'Model_KnowledgeCate','default'=>null,'required'=>false,'field'=>function($model) {
                    $cate = new Model_KnowledgeCate;
                    $cate->addWhere('id', $model->getData('cate_id'));
                    $cate->select();
                    return $cate->mName;
                }],
                ['name'=>'name','label'=>'知识名称','type'=>'text','default'=>null,'required'=>true,],
                ['name'=>'content','label'=>'知识内容','type'=>'textarea','default'=>null,'required'=>false,'rows'=>10],
                ['name'=>'reference','label'=>'参考资料','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'memo','label'=>'备注','type'=>'message','class'=>'with_date','field'=>function($model) {
                    $memos = json_decode($model->getData('memo'));
                    $memos = $memos ? $memos : [];
                    $output = '';
                    foreach($memos as $i => $memo) {
                        $output .= $memo . "\n";
                    }
                    return $output;
                }],
                ['name'=>'field-index-create','label'=>'创建信息','type'=>'seperator'],
                ['name'=>'operator','label'=>'创建人','type'=>'text','default'=>Model_Admin::getCurrentAdmin() ? Model_Admin::getCurrentAdmin()->mName : null,'required'=>true,'readonly'=>true],
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
