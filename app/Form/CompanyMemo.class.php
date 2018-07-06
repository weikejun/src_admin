<?php

class Form_CompanyMemo extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'备忘ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'company_id','label'=>'项目简称','type'=>'choosemodel','model'=>'Model_Company','default'=>$_GET['company_id'],'required'=>true,'show'=>'short', 'field'=>function($model) {
                    $company = new Model_Company;
                    $company->addWhere('id', $model->getData('company_id'));
                    $company->select();
                    return $company->getData('short');
                }],
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
