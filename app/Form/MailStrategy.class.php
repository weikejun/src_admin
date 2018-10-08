<?php

class Form_MailStrategy extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'策略ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'name','label'=>'策略名称','type'=>'text','default'=>null,'required'=>true,'validator'=>new Form_UniqueValidator(new Model_MailStrategy, 'name')],
                ['name'=>'mail_to','label'=>'收件人','type'=>'textarea','default'=>'$_current_partner$;$_current_manager$;$_current_finance_person$;$_current_legal_person$','required'=>false,'placeholder'=>'收件人之间请用英文“;”分隔','help'=>'默认发送交易项目组成员'],
                ['name'=>'mail_cc','label'=>'抄送','type'=>'textarea','default'=>null,'required'=>false,'placeholder'=>'收件人之间请用英文“;”分隔'],
                ['name'=>'title','label'=>'邮件标题','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'content','label'=>'邮件内容','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'_trigger','label'=>'触发字段','type'=>'rawText','required'=>false,'field'=>function($model) {
                    $cons = new Model_MailTrigger;
                    $cons->addWhere('strategy_id', $model->getData('id'));
                    $cons->orderBy('id', 'ASC');
                    $cons = $cons->find();
                    $consStr = '';
                    foreach($cons as $i => $con) {
                        $consStr .= $con->getData('logic_opr') . $con->getData('field') . $con->getData('field_opr') . $con->getData('value');
                    }
                    return trim($consStr, '&').' <a target="_blank" href="/admin/mailTrigger?__filter='.urlencode('stratey_id='.$model->getData('id')).'">列表 </a><a target="_blank" href="/admin/mailTrigger?action=read&strategy_id='.$model->getData('id').'">添加+</a>';
                }],
                ['name'=>'_cycle','label'=>'发送周期','type'=>'rawText','required'=>false,'field'=>function($model) {
                    $cons = new Model_MailCycle;
                    $cons->addWhere('strategy_id', $model->getData('id'));
                    $cons->orderBy('id', 'ASC');
                    $cons = $cons->find();
                    $consStr = '';
                    foreach($cons as $i => $con) {
                        $consStr .= $con->getData('field') . '+' . $con->getData('duration') . $con->getData('unit') . '重复' . $con->getData('repeat') . '次';
                    }
                    return trim($consStr, '&').' <a target="_blank" href="/admin/mailCycle?__filter='.urlencode('stratey_id='.$model->getData('id')).'">列表 </a><a target="_blank" href="/admin/mailCycle?action=read&strategy_id='.$model->getData('id').'">添加+</a>';
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
