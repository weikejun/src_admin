<?php

class Form_ContractTerm extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'编号','type'=>'hidden','default'=>null,'required'=>false,],
                //['name'=>'status','label'=>'审核状态','type'=>'text','default'=>'未审核','required'=>false,],
                ['name'=>'trade_doc','label'=>'交易文件','type'=>'selectInput','choices'=>Model_ContractTerm::getTradeDocChoices(),'default'=>null,'required'=>false,],
                ['name'=>'term','label'=>'所属条款','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'term_detail','label'=>'条款具体事项','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'field-index-standard','label'=>'源码标准及关注点','type'=>'seperator'],
                ['name'=>'standard','label'=>'源码标准及关注点','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,],
                ['name'=>'field-index-permission','label'=>'权限说明','type'=>'seperator'],
                ['name'=>'permission','label'=>'权限说明','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,],
                ['name'=>'field-index-lawyer_reminder','label'=>'律师的提醒事项','type'=>'seperator'],
                ['name'=>'lawyer_reminder','label'=>'律师的提醒事项','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,],
                ['name'=>'field-index-our_reason','label'=>'我方的主要理由','type'=>'seperator'],
                ['name'=>'our_reason','label'=>'我方的主要理由','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,],
                ['name'=>'field-index-opp_reason','label'=>'对方的主要理由','type'=>'seperator'],
                ['name'=>'opp_reason','label'=>'对方的主要理由','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,],
                ['name'=>'field-index-terms_rmb','label'=>'标准条款-RMB','type'=>'seperator'],
                ['name'=>'terms_rmb','label'=>'标准条款-RMB','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,],
                ['name'=>'field-index-terms_usd','label'=>'标准条款-USD','type'=>'seperator'],
                ['name'=>'terms_usd','label'=>'标准条款-USD','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,],
                ['name'=>'field-index-exceptional','label'=>'特殊情况处理','type'=>'seperator'],
                ['name'=>'exceptional','label'=>'特殊情况处理','type'=>'message','rows'=>10,'class'=>'with_date with_name','field'=>function($model) {
                    $memos = json_decode($model->getData('exceptional'));
                    $memos = $memos ? $memos : [];
                    $output = ',';
                    foreach($memos as $i => $memo) {
                        $output .= $memo . "<br />";
                    }
                    return $output;
                }],
                ['name'=>'field-index-compromise','label'=>'折中方案','type'=>'seperator'],
                ['name'=>'compromise','label'=>'折中方案','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,],
                ['name'=>'field-index-compromise_rmb','label'=>'折中方案示范条款-RMB','type'=>'seperator'],
                ['name'=>'compromise_rmb','label'=>'折中方案示范条款-RMB','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,],
                ['name'=>'field-index-compromise_usd','label'=>'折中方案示范条款-USD','type'=>'seperator'],
                ['name'=>'compromise_usd','label'=>'折中方案示范条款-USD','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,],
                ['name'=>'field-index-baseline','label'=>'底线方案','type'=>'seperator'],
                ['name'=>'baseline','label'=>'底线方案','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,],
                ['name'=>'field-index-baseline_rmb','label'=>'底线方案示范条款-RMB','type'=>'seperator'],
                ['name'=>'baseline_rmb','label'=>'底线方案示范条款-RMB','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,],
                ['name'=>'field-index-baseline_usd','label'=>'底线方案示范条款-USD','type'=>'seperator'],
                ['name'=>'baseline_usd','label'=>'底线方案示范条款-USD','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false,],
                ['name'=>'field-index-other_case','label'=>'其他可参考方案','type'=>'seperator'],
                ['name'=>'other_case','label'=>'其他可参考方案','type'=>'message','rows'=>10,'class'=>'with_date with_name','field'=>function($model) {
                    $memos = json_decode($model->getData('other_case'));
                    $memos = $memos ? $memos : [];
                    $output = ',';
                    foreach($memos as $i => $memo) {
                        $output .= $memo . "<br />";
                    }
                    return $output;
                }],
                ['name'=>'field-index-other_term','label'=>'其他可参考条款','type'=>'seperator'],
                ['name'=>'other_term','label'=>'其他可参考条款','type'=>'message','rows'=>10,'class'=>'with_date with_name','field'=>function($model) {
                    $memos = json_decode($model->getData('other_term'));
                    $memos = $memos ? $memos : [];
                    $output = ',';
                    foreach($memos as $i => $memo) {
                        $output .= $memo . "<br />";
                    }
                    return $output;
                }],
                ['name'=>'field-index-other_detail','label'=>'其他说明或要注意的问题','type'=>'seperator'],
                ['name'=>'other_detail','label'=>'其他说明或要注意的问题','type'=>'message','rows'=>10,'class'=>'with_date with_name','field'=>function($model) {
                    $memos = json_decode($model->getData('other_detail'));
                    $memos = $memos ? $memos : [];
                    $output = ',';
                    foreach($memos as $i => $memo) {
                        $output .= $memo . "<br />";
                    }
                    return $output;
                }],
                ['name'=>'field-index-memo','label'=>'备注','type'=>'seperator'],
                ['name'=>'memo','label'=>'备注','type'=>'message','rows'=>10,'class'=>'with_date with_name','field'=>function($model) {
                    $memos = json_decode($model->getData('memo'));
                    $memos = $memos ? $memos : [];
                    $output = ',';
                    foreach($memos as $i => $memo) {
                        $output .= $memo . "<br />";
                    }
                    return $output;
                }],
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

    public function __construct($check) {
        if ($check) {
            self::getFieldsMap();
            self::$fieldsMap[] = ['name'=>'status','label'=>'审核状态','type'=>'choice','choices'=>Model_ContractTerm::getStatusChoices(),'default'=>'已审核','required'=>false];
        }
        parent::__construct(self::getFieldsMap());
    }

}
