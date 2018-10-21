<?php

class Form_MailList extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'邮件ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'status','label'=>'发送状态','type'=>'choice','choices'=>Model_MailList::getStatusChoices(),'required'=>true,'default'=>'待发送','readonly'=>true,'field'=>function($model){
                    if ($model->getData('status') == '待发送') {
                        return $model->getData('status').' <a href="/admin/mailList/send?id='.$model->getData('id').'">发送</a>';
                    }
                    return htmlspecialchars($model->getData('status'));
                }],
                ['name'=>'title','label'=>'邮件标题','type'=>'text','default'=>null,'required'=>true,'field'=>function($model) {
                    return htmlspecialchars($model->getData('title'));
                }],
                ['name'=>'content','label'=>'邮件内容','type'=>'textarea','default'=>null,'required'=>true,'field'=>function($model){
                    return strip_tags($model->getData('content'));
                }],
                ['name'=>'strategy_id','label'=>'策略ID','type'=>'choosemodel','model'=>'Model_MailStrategy','default'=>isset($_GET['strategy_id'])?$_GET['strategy_id']:'','required'=>true,'field'=>function($model){
                    $st = new Model_MailStrategy;
                    $st->addWhere('id', $model->getData('strategy_id'));
                    $st->select();
                    return htmlspecialchars($st->getData('name'));
                }],
                ['name'=>'ref','label'=>'触发资源','type'=>'choice','choices'=>Model_MailStrategy::getRefChoices(),'required'=>true,],
                ['name'=>'ref_id','label'=>'资源ID','type'=>'text','default'=>isset($_GET['ref_id'])?$_GET['ref_id']:'','required'=>true],
                ['name'=>'mail_to','label'=>'收件人','type'=>'textarea','default'=>null,'required'=>true],
                ['name'=>'mail_cc','label'=>'抄送','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'create_type','label'=>'创建方式','type'=>'hidden','default'=>'手动','required'=>true],
                ['name'=>'expect_time','label'=>'预计发送时间','type'=>'datetime','default'=>null,'field'=>function($model){
                    if ($model->getData('expect_time')) {
                        return date('Ymd H:i:s', $model->getData('expect_time'));
                    }
                }],
                ['name'=>'send_time','label'=>'实际发送时间','type'=>'datetime','readonly'=>'true','default'=>null,'field'=>function($model){
                    if ($model->getData('send_time')) {
                        return date('Ymd H:i:s', $model->getData('send_time'));
                    }
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

    protected function _filter($value) {
        return $value;
    }
}
