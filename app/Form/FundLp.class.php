<?php

class Form_FundLp extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'field-index-base','label'=>'认购人基本情况','type'=>'seperator'],
                ['name'=>'id','label'=>'认购人ID','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$entity) {
                    $entity = new Model_Entity;
                    $entity->addWhere('id', $model->getData('entity_id'));
                    $entity->select();
                    if (!$entity->mId) {
                        $entity = new Model_Entity;
                    }
                    return $model->getData('id');
                }],
                ['name'=>'entity_id','label'=>'募资主体ID','type'=>'choosemodel','model'=>'Model_Entity','default'=>null,'required'=>false,'field'=>function($model)use(&$entity) {
                    return $entity->getData('name');
                }],
                ['name'=>'_entity_cate','label'=>'募资主体类型','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$entity){
                    return $entity->getData('cate');
                }],
                ['name'=>'_entity_currency','label'=>'募资主体资金货币','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$entity){
                    return $entity->getData('currency');
                }],
                ['name'=>'subscriber','label'=>'认购人','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'subscriber_code','label'=>'认购人代码','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'subscriber_controller','label'=>'认购人实际控制人','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'partner_type','label'=>'合伙人类型','type'=>'choice','choices'=>Model_FundLp::getPartnerTypeChoices(),'default'=>null,'required'=>false],
                ['name'=>'subscriber_bg','label'=>'认购人背景','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'subscriber_org','label'=>'认购人组织形式','type'=>'selectInput','choices'=>Model_FundLp::getSubscriberOrgChoices(),'default'=>null,'required'=>false],
                ['name'=>'cert_type','label'=>'证照类型','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'cert_no','label'=>'证照文件号码','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'contact_info','label'=>'联系人信息','type'=>'message','default'=>null,'required'=>false,'field'=>function($model) {
                    $list = json_decode($model->getData('contact_info'));
                    if ($list) {
                        $output = '';
                        foreach($list as $li) {
                            $output .= $li . "\n";
                        }
                    }
                    return $output;
                }],
                ['name'=>'mail_province','label'=>'邮寄所在省份','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'create_time','label'=>'创建时间','type'=>'datetime','default'=>time(),'required'=>false,'readonly'=>true,'field'=>function($model){
                    return date('Ymd H:i:s', $model->getData('create_time'));
                }],
                ['name'=>'field-index-todo','label'=>'Todo','type'=>'seperator'],
                ['name'=>'aic_pending','label'=>'工商待办事项','type'=>'choice','choices'=>Model_FundLp::getHaveNotChoices(),'default'=>null,'required'=>false],
                ['name'=>'aic_memo','label'=>'工商备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'undetermined','label'=>'待定事项','type'=>'choice','choices'=>Model_FundLp::getHaveNotChoices(),'default'=>null,'required'=>false],
                ['name'=>'undetermined_memo','label'=>'待定事项备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'communication','label'=>'待沟通事项','type'=>'choice','choices'=>Model_FundLp::getYesNoChoices(),'default'=>null,'required'=>false],
                ['name'=>'communication_memo','label'=>'待沟通事项备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'field-index-date-and-amount','label'=>'认购时间及出资','type'=>'seperator'],
                ['name'=>'join_turn','label'=>'进入批次','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'sign_lpa_date','label'=>'签署LPA日期','type'=>'date','default'=>null,'required'=>false,'field'=>function($model) {
                    if ($model->getData('sign_lpa_date')) {
                        return date('Ymd', $model->getData('sign_lpa_date'));
                    }
                }],
                ['name'=>'subscriber_delivery_date','label'=>'本认购人交割日期','type'=>'date','default'=>null,'required'=>false,'field'=>function($model) {
                    if ($model->getData('subscriber_delivery_date')) {
                        return date('Ymd', $model->getData('subscriber_delivery_date'));
                    }
                }],
                ['name'=>'subscribe_currency','label'=>'认缴货币','type'=>'choice','choices'=>Model_Project::getCurrencyChoices(),'default'=>null,'required'=>false],
                ['name'=>'subscribe_currency_memo','label'=>'认缴货币备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'subscribe_amount','label'=>'认缴金额','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'paid_currency','label'=>'实缴货币','type'=>'choice','choices'=>Model_Project::getCurrencyChoices(),'default'=>null,'required'=>false],
                ['name'=>'paid_amount','label'=>'实缴金额','type'=>'message','default'=>null,'required'=>false,'field'=>function($model) {
                    $list = json_decode($model->getData('paid_amount'));
                    if ($list) {
                        $output = '';
                        foreach($list as $li) {
                            $output .= $li . "\n";
                        }
                    }
                    return $output;
                }],
                ['name'=>'latest_paid_date','label'=>'最新实缴日期','type'=>'date','default'=>null,'required'=>false,'field'=>function($model) {
                    if ($model->getData('latest_paid_date')) {
                        return date('Ymd', $model->getData('latest_paid_date'));
                    }
                }],
                ['name'=>'field-index-law-files','label'=>'认购及变更法律文件情况','type'=>'seperator'],
                ['name'=>'subscribe_pdf','label'=>'认购文件PDF','type'=>'choice','choices'=>Model_FundLp::getDocOptionChoices(),'default'=>null,'required'=>false],
                ['name'=>'subscribe_doc','label'=>'认购文件原件','type'=>'choice','choices'=>Model_FundLp::getCompleteChoices(),'default'=>null,'required'=>false],
                ['name'=>'subscribe_file_memo','label'=>'认购文件原件备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'gb_sign','label'=>'GP&管理人已章','type'=>'choice','choices'=>Model_FundLp::getYesNoChoices(),'default'=>null,'required'=>false],
                ['name'=>'aic_material','label'=>'工商变更资料提供','type'=>'choice','choices'=>Model_FundLp::getYesNoChoices(),'default'=>null,'required'=>false],
                ['name'=>'side_letter','label'=>'SideLetter','type'=>'choice','choices'=>Model_FundLp::getHaveNotChoices(),'default'=>null,'required'=>false],
                ['name'=>'side_letter_detail','label'=>'SideLetter主要内容','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'mfn','label'=>'MFN','type'=>'choice','choices'=>Model_FundLp::getHaveNotChoices(),'default'=>null,'required'=>false],
                ['name'=>'lpac','label'=>'LPAC','type'=>'choice','choices'=>Model_FundLp::getHaveNotChoices(),'default'=>null,'required'=>false],
                ['name'=>'lpac_commission','label'=>'LPAC委任书','type'=>'choice','choices'=>Model_FundLp::getHaveNotChoices(),'default'=>null,'required'=>false],
                ['name'=>'entrust_agreement','label'=>'委托管理协议','type'=>'choice','choices'=>Model_FundLp::getCompleteChoices(),'default'=>null,'required'=>false],
                ['name'=>'bank_entrustment','label'=>'银行托管信息页','type'=>'choice','choices'=>Model_FundLp::getCompleteChoices(),'default'=>null,'required'=>false],
                ['name'=>'no_entrustment','label'=>'不托管协议','type'=>'choice','choices'=>Model_FundLp::getCompleteChoices(),'default'=>null,'required'=>false],
                ['name'=>'share_transfer','label'=>'有无份额转让','type'=>'choice','choices'=>Model_FundLp::getHaveNotChoices(),'default'=>null,'required'=>false],
                ['name'=>'share_transfer_memo','label'=>'份额转让备注','type'=>'message','class'=>'with_date','default'=>null,'required'=>false],
                ['name'=>'share_entrustment','label'=>'是否有代持','type'=>'choice','choices'=>Model_FundLp::getHaveNotChoices(),'default'=>null,'required'=>false],
                ['name'=>'share_entrust_agreement','label'=>'代持协议','type'=>'choice','choices'=>Model_FundLp::getHaveNotChoices(),'default'=>null,'required'=>false],
                ['name'=>'share_entrustment_memo','label'=>'代持备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'is_exit','label'=>'是否已退伙','type'=>'choice','choices'=>Model_FundLp::getIsExitChoices(),'default'=>null,'required'=>false],
                ['name'=>'exit_file','label'=>'退伙文件','type'=>'choice','choices'=>Model_FundLp::getCompleteChoices(),'default'=>null,'required'=>false],
                ['name'=>'other_agreement','label'=>'其他协议','type'=>'choice','choices'=>Model_FundLp::getHaveNotChoices(),'default'=>null,'required'=>false],
                ['name'=>'other_agreement_main','label'=>'其他协议主要内容','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'subscribe_doc_memo','label'=>'认购交易文件备注','type'=>'message','class'=>'with_date','default'=>null,'required'=>false],
                ['name'=>'change_memo','label'=>'基金变更备注','type'=>'message','class'=>'with_date','default'=>null,'required'=>false],
                ['name'=>'field-index-program-compliance','label'=>'认购程序性及合规文件','type'=>'seperator'],
                ['name'=>'kyc_file','label'=>'KYC文件','type'=>'choice','choices'=>Model_FundLp::getDocOptionChoices(),'default'=>null,'required'=>false],
                ['name'=>'kyc_file_memo','label'=>'KYC文件备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'fatca_crs','label'=>'FATCA/CRS','type'=>'choice','choices'=>Model_FundLp::getDocOptionChoices(),'default'=>null,'required'=>false],
                ['name'=>'w_form','label'=>'w8/9税表','type'=>'choice','choices'=>Model_FundLp::getDocOptionChoices(),'default'=>null,'required'=>false],
                ['name'=>'capital_from','label'=>'资金来源说明','type'=>'choice','choices'=>Model_FundLp::getCompleteChoices(),'default'=>null,'required'=>false],
                ['name'=>'sa','label'=>'SA','type'=>'choice','choices'=>Model_FundLp::getCompleteChoices(),'default'=>null,'required'=>false],
                ['name'=>'risk_evaluate_date','label'=>'类型确认及风险评估时间','type'=>'date','default'=>null,'required'=>false,'help'=>'三年内有效','field'=>function($model) {
                    if ($model->getData('risk_evaluate_date')) {
                        return date('Ymd', $model->getData('risk_evaluate_date'));
                    }
                }],
                ['name'=>'investor_type','label'=>'投资者类型确认','type'=>'choice','choices'=>Model_FundLp::getInvestorTypeChoices(),'default'=>null,'required'=>false],
                ['name'=>'investor_through','label'=>'投资者穿透特殊情况','type'=>'choice','choices'=>Model_FundLp::getYesNoChoices(),'default'=>null,'required'=>false],
                ['name'=>'investor_through_memo','label'=>'投资者穿透特殊情况备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'through_num','label'=>'在本募资主体中穿透人数','type'=>'number','default'=>null,'required'=>false],
                ['name'=>'through_num_memo','label'=>'穿透人数备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'check_num_date','label'=>'核查人数时间','type'=>'date','default'=>null,'required'=>false,'field'=>function($model) {
                    if ($model->getData('check_num_date')) {
                        return date('Ymd', $model->getData('check_num_date'));
                    }
                }],
                ['name'=>'investor_type_cert','label'=>'投资者类型证明','type'=>'choice','choices'=>Model_FundLp::getCompleteChoices(),'default'=>null,'required'=>false],
                ['name'=>'investor_appropriateness','label'=>'投资者适当性匹配','type'=>'choice','choices'=>Model_FundLp::getDocOptionChoices(),'default'=>null,'required'=>false],
                ['name'=>'subscribe_fillin','label'=>'认购册填写','type'=>'choice','choices'=>Model_FundLp::getDocOptionChoices(),'default'=>null,'required'=>false],
                ['name'=>'subscribe_memo','label'=>'认购册备注','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'coolingoff_period','label'=>'冷静期及回访','type'=>'choice','choices'=>Model_FundLp::getCoolingoffPeriodChoices(),'default'=>null,'required'=>false],
                ['name'=>'_compliance_list','label'=>'合规要求清单','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$entity) {
                    $list = json_decode($entity->getData('compliance_list'));
                    if ($list) {
                        $output = '';
                        foreach($list as $li) {
                            $output .= $li . "\n";
                        }
                    }
                    return $output;
                }],
                ['name'=>'compliance_check','label'=>'核对合规要求','type'=>'choice','choices'=>Model_FundLp::getComplianceCheckChoices(),'default'=>null,'required'=>false],
                ['name'=>'subscribe_compliance_memo','label'=>'认购程序及合规备注','type'=>'message','class'=>'with_date','default'=>null,'required'=>false],
                ['name'=>'_filing_list','label'=>'所需filing文件清单','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$entity) {
                    $list = json_decode($entity->getData('filing_list'));
                    if ($list) {
                        $output = '';
                        foreach($list as $li) {
                            $output .= $li . "\n";
                        }
                    }
                    return $output;
                }],
                ['name'=>'filling_list_check','label'=>'核对filing清单','type'=>'choice','choices'=>Model_FundLp::getFillingListCheckChoices(),'default'=>null,'required'=>false],
                ['name'=>'filling_memo','label'=>'filing备注','type'=>'message','class'=>'with_date','default'=>null,'required'=>false],
                ['name'=>'field-index-mailing','label'=>'邮寄情况','type'=>'seperator'],
                ['name'=>'mail_matter','label'=>'当期邮寄事项','type'=>'message','class'=>'with_date','default'=>null,'required'=>false],
                ['name'=>'gp_mailed','label'=>'GP是否寄出','type'=>'choice','choices'=>Model_FundLp::getMailStatusChoices(),'default'=>null,'required'=>false],
                ['name'=>'gp_mailed_detail','label'=>'GP邮寄详情','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'lp_mailed','label'=>'LP是否寄出','type'=>'choice','choices'=>Model_FundLp::getMailStatusChoices(),'default'=>null,'required'=>false],
                ['name'=>'lp_mailed_detail','label'=>'LP邮寄详情','type'=>'textarea','default'=>null,'required'=>false],
                ['name'=>'gp_received','label'=>'GP是否已收到','type'=>'choice','choices'=>Model_FundLp::getMailStatusChoices(),'default'=>null,'required'=>false],
                ['name'=>'mail_receive_date','label'=>'收悉日期','type'=>'date','default'=>null,'required'=>false,'field'=>function($model) {
                    if ($model->getData('mail_receive_date')) {
                        return date('Ymd', $model->getData('mail_receive_date'));
                    }
                }],
                ['name'=>'mailing_memo','label'=>'邮寄备注','type'=>'textarea','default'=>null,'required'=>false],
            ];
        }
        return self::$fieldsMap;
    }

    public function __construct() {
        parent::__construct(self::getFieldsMap());
    }
}
