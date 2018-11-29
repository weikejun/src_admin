<?php

class Form_ControllerActual extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'name','label'=>'名称','type'=>'text','default'=>null,'required'=>true],
                ['name'=>'_fund','label'=>'认购参与','type'=>'rawText','field'=>function($model) {
                    $fund = new Model_FundLp;
                    $fund->addWhere('status', 'valid');
                    $fund->addWhere('subscriber_controller', $model->getData('id'));
                    return '<a target="_blank" href="/admin/fundLp?__filter='.urlencode('name|subscriber_controller='.$model->getData('name')).'">'.$fund->count().'</a>';
                }],
                ['name'=>'description','label'=>'认购人背景简介','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'contact','label'=>'联系人','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'contact_info','label'=>'联系人信息','type'=>'message','default'=>null,'required'=>false,'field'=>function($model) {
                    $infos = json_decode($model->getData('contact_info'));
                    $infos = $infos ? $infos : [];
                    $output = '';
                    foreach($infos as $i => $info) {
                        $output .= $info . "<br />";
                    }
                    return $output;
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
