<?php

class Form_Seperator2Field extends Form_Field{
    public function __construct($config){
        parent::__construct($config);
    }

    public function to_text() {
        return $this->to_html(false);
    }

    public function to_html($is_new){
        $html = "<div class='control-seperator2'><div><i>".str_pad("", 500, "*")."</i><div class='seperator2-label'><i>".htmlspecialchars($this->label)."</i></div></div></div>";
        return $html;
    }

    public function head_css() {
        $css=<<<EOF
<style>
.control-seperator2 {width:100%;overflow:hidden;color:#ccc;padding-top:5px;}
.seperator2-label {color:grey;font:14px bold;position:relative;bottom:20px;text-align:center;}
.seperator2-label i {background-color:#fff !important;padding:0 8px;}
@media print {
    .seperator2-label i {background-color:#fff !important;padding:0 8px;-webkit-print-color-adjust: exact;}
}
</style>
EOF;
        return $css;
    }
}

class Form_Project extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'交易ID','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$deals,&$company){
                    $deal = new Model_Project;
                    $deal->addWhere('status', 'valid');
                    $deal->addWhere('company_id', $model->getData('company_id'));
                    $deal->addWhere('close_date', '0', '>');
                    $deal->orderBy('close_date', 'DESC');
                    $deals = $deal->find();
                    $company = new Model_Company;
                    $company->addWhere('id', $model->getData('company_id'));
                    $company->select();
                    return $model->getData('id');
                }],
                ['name'=>'status','label'=>'数据状态','type'=>'hidden','default'=>'valid','required'=>true,],
                ['name'=>'company_id','label'=>'目标企业','type'=>'choosemodel','model'=>'Model_Company','default'=>isset($_GET['company_id'])?$_GET['company_id']:'','required'=>true,'field'=>function($model)use(&$company) {
                    return $company->mName;
                }],
                ['name'=>'_company_short','label'=>'项目简称','type'=>'rawText','required'=>false,'field'=>function($model)use(&$company) {
                    return $company->mShort;
                }],
                ['name'=>'_company_id','label'=>'企业ID','type'=>'rawText','required'=>false,'field'=>'company_id','field'=>function($model){
                    return $model->getData('company_id');
                }],
                ['name'=>'first_financing','label'=>'企业是否首次融资','type'=>'choice','choices'=>Model_Project::getFirstFinancingChoices(),'required'=>true,],
                ['name'=>'company_period','label'=>'目标企业阶段','type'=>'selectInput','choices'=>Model_Project::getCompanyPeriodChoices(),'required'=>false,],
                ['name'=>'company_character','label'=>'目标企业性质','type'=>'selectInput','choices'=>Model_Project::getCompanyCharacterChoices(),'required'=>false,],
                ['name'=>'item_status','label'=>'整理状态','type'=>'choice','choices'=>Model_Project::getItemStatusChoices(),'required'=>true,'default'=>'待完成'],
                ['name'=>'field-index-status','label'=>'本轮交易状态','type'=>'seperator'],
                ['name'=>'decision_date','label'=>'决策日期','type'=>'date','default'=>null,'required'=>false,'help'=>'TS日期（优先）、IC决策日期、投资部告知的大致日期，尽量精确到月','field'=>function($model){
                    if ($model->getData('decision_date')) {
                        return date('Ymd', $model->getData('decision_date'));
                    }
                }],
                ['name'=>'longstop_date','label'=>'LongStopDate','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false],
                ['name'=>'proj_status','label'=>'交易状态','type'=>'choice','choices'=>Model_Project::getProjStatusChoices(),'required'=>false,],
                ['name'=>'kickoff_date','label'=>'签约日期','type'=>'date','default'=>null,'required'=>false,'field'=>function($model){
                    if ($model->getData('kickoff_date')) {
                        return date('Ymd', $model->getData('kickoff_date'));
                    }
                }],
                ['name'=>'close_date','label'=>'交割日期','type'=>'date','default'=>null,'class'=>'fin-check','field'=>function($model){
                    if ($model->getData('close_date')) {
                        return date('Ymd', $model->getData('close_date'));
                    }
                }],
                ['name'=>'count_captable','label'=>'是否计入Captable','type'=>'choice','choices'=>Model_Project::getCountCaptableChoices(),'default'=>'N','required'=>false],
                ['name'=>'field-index-status','label'=>'本轮交易进度','type'=>'seperator'],
                ['name'=>'active_deal','label'=>'active项目进度','type'=>'choice','choices'=>Model_Project::getStandardYesNoChoices(),'required'=>false,'default'=>'否'],
                ['name'=>'loan_schedule','label'=>'借款进度','type'=>'selectInput','choices'=>Model_Project::getLoanScheduleChoices(),'required'=>false],
                ['name'=>'trade_file_schedule','label'=>'交易文件进度','type'=>'selectInput','choices'=>Model_Project::getTradeFileScheduleChoices(),'required'=>false],
                ['name'=>'expect_sign_date','label'=>'预计签约日期','type'=>'date','default'=>null,'help'=>'须填写，与提醒邮件关联','field'=>function($model){
                    if ($model->getData('expect_sign_date')) {
                        return date('Ymd', $model->getData('expect_sign_date'));
                    }
                }],
                ['name'=>'expect_pay_schedule','label'=>'预计交割付款安排','type'=>'textarea','default'=>null,'required'=>false,],
                ['name'=>'trade_schedule_memo','label'=>'交易进度其他说明','type'=>'textarea','default'=>null,'required'=>false,],
                ['name'=>'trade_schedule_todo','label'=>'To Do','type'=>'textarea','default'=>null,'required'=>false,],
                ['name'=>'field-index-base','label'=>'本轮交易基本信息','type'=>'seperator'],
                ['name'=>'deal_type','label'=>'本轮交易类型','type'=>'choice','choices'=>Model_Project::getDealTypeChoices(),'required'=>false,],
                ['name'=>'turn_sub','label'=>'企业所处轮次','type'=>'text','default'=>null,'required'=>false,'help'=>'按交易文件的界定填写，示范“A3”、“B+”'],
                ['name'=>'turn','label'=>'企业轮次归类','type'=>'choice','choices'=>Model_Project::getTurnChoices(),'required'=>false,],
                ['name'=>'new_follow','label'=>'项目新老类型','type'=>'choice','choices'=>Model_Project::getNewFollowChoices(),'required'=>false,],
                ['name'=>'enter_exit_type','label'=>'源码投退类型','type'=>'choice','choices'=>Model_Project::getEnterExitTypeChoices(),'required'=>false,],
                ['name'=>'other_enter_exit_type','label'=>'其他投资人投退类型','type'=>'choice','choices'=>Model_Project::getOtherEnterExitTypeChoices(),'required'=>false,],
                ['name'=>'dilution_rate','label'=>'本轮稀释比例','type'=>'text','default'=>null,'required'=>false,'help'=>'“本轮新股融资总额”除以“企业投后估值”', 'field'=>function($model) {
                    if (strpos($model->getData('deal_type'), '企业融资') !== false
                        && $model->getData('post_money')
                        && !$model->getData('dilution_rate')) {
                        return number_format($model->getData('financing_amount')/$model->getData('post_money')*100, 2).'%';
                    }
                    return $model->getData('dilution_rate');
                }],
                ['name'=>'raw_stock_memo','label'=>'老股转让情况备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'default'=>null,'required'=>false,'input'=>'textarea'],
                ['name'=>'deal_memo','label'=>'本轮交易方案备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'default'=>null,'required'=>false,'input'=>'textarea'],
                ['name'=>'field-index-value','label'=>'企业估值及每股单价','type'=>'seperator'],
                ['name'=>'value_currency','label'=>'估值计价货币','type'=>'choice','choices'=>Model_Project::getCurrencyChoices(),'required'=>false,],
                ['name'=>'pre_money','label'=>'企业投前估值','type'=>'number','required'=>false,'field'=>function($model){
                    return $model->getData('value_currency') . ' ' . number_format($model->getData('pre_money'), 2);
                }],
                ['name'=>'financing_amount','label'=>'本轮新股融资总额','type'=>'number','default'=>null,'required'=>false,'help'=>'仅为新股融资金额，不包括老股金额','field'=>function($model){
                    return $model->getData('value_currency') . ' ' . number_format($model->getData('financing_amount'), 2);
                }],
                ['name'=>'post_money','label'=>'企业投后估值','type'=>'number','required'=>false,'help'=>'（1）值为“企业投前估值“+“本轮新股融资总金额“；若有打折等情况影响估值计算，与财务确认；<br />（2）企业若本轮未发生融资则写上轮估值。','field'=>function($model) {
                    /*
                    if ($model->getData('post_money')) {
                        return $model->getData('value_currency') . ' ' . number_format($model->getData('post_money'), 2);
                    }
                    return $model->getData('value_currency') . ' ' . number_format($model->getData('pre_money') + $model->getData('financing_amount'), 2);
                     */
                    if ($model->getData('post_money'))
                        return $model->getData('value_currency') . ' ' . number_format($model->getData('post_money'), 2);
                }],
                ['name'=>'_stock_price','label'=>'企业每股单价','type'=>'rawText','required'=>false,'help'=>'本轮“企业投后估值“除以本轮“企业投后总股数”','field'=>function($model)use(&$stockPrice){
                    $postMoney = $model->getData('pre_money') + $model->getData('financing_amount');
                    if ($model->getData('post_money')) {
                        $postMoney = $model->getData('post_money');
                    }
                    if ($model->getData('stocknum_all')) {
                        $stockPrice = $postMoney/$model->getData('stocknum_all');
                        return $model->getData('stocknum_all') ? $model->getData('value_currency') . ' ' . number_format($stockPrice, 2) : false;
                    }
                }],
                ['name'=>'value_change','label'=>'与上轮估值比','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'placeholder'=>'自动计算字段，如不填写则预览、列表可见','help'=>'本轮“企业每股单价“除以”企业上一轮每股单价“；1X指平价未增资。','field'=>function($model)use(&$deals, &$stockPrice) {
                    if ($model->getData('value_change')) {
                        return $model->getData('value_change');
                    }
                    $preStockPrice = 0;
                    foreach($deals as $i => $deal) {
                        if ($deal->getData('id') < $model->getData('id')
                            && $deal->getData('turn_sub') != $model->getData('turn_sub')) {
                            $prePostMoney = $deal->getData('pre_money') + $deal->getData('financing_amount');
                            if ($deal->getData('post_money')) {
                                $prePostMoney = $deal->getData('post_money');
                            }
                            $preStockPrice = $prePostMoney/$deal->getData('stocknum_all');
                            break;
                        } 
                    }
                    if ($preStockPrice) {
                        return number_format($stockPrice/$preStockPrice,2);
                    }
                }],
                ['name'=>'field-index-plan','label'=>'源码投资方案','type'=>'seperator'],
                ['name'=>'affiliate_transaction','label'=>'是否关联交易','type'=>'choice','choices'=>Model_Project::getStandardYesNoChoices(),'required'=>false,],
                ['name'=>'new_old_stock','label'=>'源码购新股老股','type'=>'choice','choices'=>Model_Project::getNewOldStockChoices(),'required'=>false,],
                ['name'=>'invest_currency','label'=>'源码投资计价货币','type'=>'choice','choices'=>Model_Project::getInvestCurrencyChoices(),'required'=>false,],
                ['name'=>'entity_id','label'=>'源码投资主体','type'=>'choosemodel','model'=>'Model_Entity','default'=>isset($_GET['entity_id'])&&$_GET['entity_id']?$_GET['entity_id']:0,'required'=>false,'field'=>function($model){
                    $entity = Page_Admin_Base::getResource($model->getData('entity_id'), 'Model_Entity', new Model_Entity);
                    return $entity ? $entity->getData('name') : false;
                }],
                ['name'=>'our_amount','label'=>'源码合同投资金额','type'=>'number','default'=>null,'required'=>false,'field'=>function($model){
                    return $model->getData('invest_currency') . ' ' . number_format($model->getData('our_amount'), 2);
                }],
                ['name'=>'stocknum_get','label'=>'投时持本轮股数','type'=>'number','default'=>null,'required'=>false,'field'=>function($model){
                    return number_format($model->getData('stocknum_get'));
                },'help'=>'本轮未投写“0”'],
                ['name'=>'ts_ratio','label'=>'TS/决策口径占比','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'_stock_ratio','label'=>'投时持股比例','type'=>'rawText','readonly'=>true,'default'=>'','required'=>false,'field'=>function($model){
                    if ($model->getData('stocknum_all')) {
                        return sprintf('%.2f%%', $model->getData('stocknum_get') / $model->getData('stocknum_all') * 100);
                    }
                }],
                ['name'=>'invest_turn','label'=>'本主体购股轮次','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'stock_property','label'=>'股权属性','type'=>'choice','choices'=>Model_Project::getStockPropertyChoices(),'required'=>false,],
                ['name'=>'_invest_stock_price','label'=>'投资时每股单价','type'=>'rawText','readonly'=>true,'default'=>'','required'=>false,'field'=>function($model){
                    if ($model->getData('stocknum_get')) {
                        return $model->getData('invest_currency') . ' ' . sprintf('%.2f', $model->getData('our_amount') / $model->getData('stocknum_get'));
                    }
                }],
                ['name'=>'pay_amount','label'=>'源码实际支付投资金额','type'=>'number','default'=>'','required'=>false,'field'=>function($model){
                    if (is_numeric($model->getData('pay_amount'))) {
                        return $model->getData('invest_currency') . ' ' . number_format($model->getData('pay_amount'), 2);
                    }
                }],
                ['name'=>'amount_memo','label'=>'金额备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'committee_view','label'=>'投决意见','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'field-index-loan','label'=>'源码借款或源码CB','type'=>'seperator'],
                ['name'=>'loan_cb','label'=>'源码借款或CB','type'=>'choice','choices'=>Model_Project::getStandardOptionChoices(),'required'=>false,],
                ['name'=>'loan_currency','label'=>'借款计价货币','type'=>'choice','choices'=>Model_Project::getCurrencyChoices(),'required'=>false,],
                ['name'=>'loan_type','label'=>'借款类型','type'=>'choice','choices'=>Model_Project::getLoanTypeChoices(),'required'=>false,],
                ['name'=>'loan_entity_id','label'=>'源码出借主体','type'=>'choosemodel','model'=>'Model_Entity','default'=>0,'required'=>false,'field'=>function($model) {
                    $entity = Page_Admin_Base::getResource($model->getData('loan_entity_id'), 'Model_Entity', new Model_Entity);
                    return $entity ? $entity->getData('name') : false;
                }],
                ['name'=>'loan_amount','label'=>'源码借款合同金额','type'=>'number','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'field'=>function($model){
                    return $model->getData('loan_currency') . ' ' . number_format($model->getData('loan_amount'), 2);
                }],
                ['name'=>'loan_sign_date','label'=>'借款合同签署日期','type'=>'date','default'=>null,'required'=>false,],
                ['name'=>'loan_end_date','label'=>'借款到期日','type'=>'date','default'=>null,'required'=>false,],
                ['name'=>'loan_process','label'=>'借款处理','type'=>'choice','choices'=>Model_Project::getLoanProcessChoices(),'required'=>false,],
                ['name'=>'loan_memo','label'=>'借款备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'field-index-otherinvestor','label'=>'非源码投资人投资方案','type'=>'seperator'],
                ['name'=>'other_investor','label'=>'本轮非源码投资人','type'=>'choice','choices'=>Model_Project::getOtherInvestorChoices(),'required'=>false,],
                ['name'=>'other_investor_summary','label'=>'其他主要投资人金额与比例','type'=>'textarea','default'=>null,'required'=>false,'help'=>'选一两个填即可。'],
                ['name'=>'field-index-exit','label'=>'源码退出方案及详情','type'=>'seperator'],
                ['name'=>'has_exit','label'=>'源码是否有退出','type'=>'choice','choices'=>Model_Project::getStandardOptionChoices(),'required'=>false,],
                ['name'=>'exit_currency','label'=>'源码退出计价货币','type'=>'choice','choices'=>Model_Project::getCurrencyChoices(),'required'=>false,],
                ['name'=>'exit_type','label'=>'源码退出方式','type'=>'choice','choices'=>Model_Project::getExitTypeChoices(),'required'=>false,],
                ['name'=>'exit_profit','label'=>'退出盈亏情况','type'=>'choice','choices'=>Model_Project::getExitProfitChoices(),'required'=>false,],
                ['name'=>'exit_entity_id','label'=>'源码退出主体','type'=>'choosemodel','model'=>'Model_Entity','default'=>0,'required'=>false,'field'=>function($model){
                    $entity = Page_Admin_Base::getResource($model->getData('exit_entity_id'), 'Model_Entity', new Model_Entity);
                    return $entity ? $entity->getData('name') : false;
                }],
                /*
                ['name'=>'exit_company_value','label'=>'源码退出时企业估值','type'=>'number','required'=>false,'field'=>function($model){
                    return $model->getData('exit_currency') . ' ' . number_format($model->getData('exit_company_value'), 2);
                }],
                 */
                ['name'=>'_exit_company_stock_price','label'=>'源码退出时企业每股单价','type'=>'rawText','required'=>false,'field'=>function($model){
                    if ($model->getData('stocknum_all'))
                        return $model->getData('exit_currency') . ' ' . number_format($model->getData('post_money')/$model->getData('stocknum_all'), 2);
                }],
                ['name'=>'exit_stock_number','label'=>'源码退出的股数','type'=>'number','required'=>false,'field'=>function($model){
                    return number_format($model->getData('exit_stock_number'));
                }],
                ['name'=>'exit_amount','label'=>'源码本次退出合同金额','type'=>'number','required'=>false,'field'=>function($model){
                    return $model->getData('exit_currency') . ' ' . number_format($model->getData('exit_amount'), 2);
                }],
                ['name'=>'_exit_stock_price','label'=>'源码退出的每股单价','type'=>'rawText','required'=>false,'field'=>function($model){
                    if ($model->getData('exit_stock_number')) {
                        return $model->getData('exit_currency') . ' ' . sprintf('%.2f', $model->getData('exit_amount') / $model->getData('exit_stock_number'));
                    }
                }],
                ['name'=>'_exit_stock_ratio','label'=>'源码退出的Post比例','type'=>'rawText','required'=>false,'field'=>function($model){
                    if ($model->getData('stocknum_all')) {
                        return sprintf('%.2f%%', $model->getData('exit_stock_number') / $model->getData('stocknum_all') * 100);
                    }
                }],
                ['name'=>'exit_turn','label'=>'源码售股轮次','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'exit_stock_property','label'=>'源码股权出售的属性','type'=>'choice','choices'=>Model_Project::getStockPropertyChoices(),'required'=>false,],
                ['name'=>'exit_receive_amount','label'=>'源码本次退出实收金额','type'=>'number','default'=>null,'required'=>false,'field'=>function($model){
                    return $model->getData('exit_currency') . ' ' . number_format($model->getData('exit_receive_amount'), 2);
                }],
                ['name'=>'_exit_return_rate','label'=>'源码本次退出回报倍数（gross）','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$deals){
                    if (strpos($model->getData('deal_type'), '源码退') === false) {
                        return;
                    }
                    foreach($deals as $i => $deal) {
                        if ($deal->getData('close_date')
                            && strpos($deal->getData('deal_type'), '源码投') !== false
                            && $deal->getData('invest_turn') == $model->getData('exit_turn')) {
                            $exitStock = $model->getData('exit_stock_number');
                            $costPrice = $deal->getData('our_amount') / $deal->getData('stocknum_get');
                            if (!$costPrice) {
                                return '股权购买成本无记录';
                            } else {
                                return sprintf('%.2f%%', ($model->getData('exit_amount') / ($exitStock * $costPrice) - 1) * 100);
                            }
                        }
                    }
                }],
                ['name'=>'exit_memo','label'=>'源码退出备注','type'=>'text','default'=>null,'required'=>false,'input'=>'textarea'],
                ['name'=>'field-index-shareholding','label'=>'本轮Post企业股权结构','type'=>'seperator'],
                ['name'=>'stocknum_all','label'=>'本轮企业总股数','type'=>'number','default'=>null,'required'=>false,'help'=>'交割后的股数或注册资本','field'=>function($model){
                    return number_format($model->getData('stocknum_all'));
                }],
                ['name'=>'captable_memo','label'=>'Captable备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'field-seperator-shareholding-team','label'=>'创始人及团队本轮','type'=>'seperator2'],
                ['name'=>'_main_founders','label'=>'最主要创始人','type'=>'rawText','required'=>false,'field'=>function($model)use(&$company){
                    if ($model->getData('company_id')) {
                        return $company->getData('main_founders');
                    }
                }],
                ['name'=>'shareholding_founder','label'=>'最主要创始人股数','type'=>'number','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'field'=>function($model){
                    return is_numeric($model->getData('shareholding_founder')) ? number_format($model->getData('shareholding_founder')) : $model->getData('shareholding_founder');
                }],
                ['name'=>'_shareholding_ratio_founder','label'=>'最主要创始人股比','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    if ($model->getData('stocknum_all')) {
                        return sprintf('%.2f%%', $model->getData('shareholding_founder') / $model->getData('stocknum_all') * 100);
                    }
                }],
                ['name'=>'shareholding_cofounders','label'=>'co-founders股数','type'=>'number','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'field'=>function($model){
                    return is_numeric($model->getData('shareholding_cofounders')) ? number_format($model->getData('shareholding_cofounders')) : $model->getData('shareholding_cofounders');
                }],
                ['name'=>'shareholding_member','label'=>'co-founders持股比例','type'=>'hidden','required'=>false], // 兼容老数据
                ['name'=>'_shareholding_member','label'=>'co-founders持股比例','type'=>'rawText','required'=>false,'field'=>function($model) {
                    if ($model->getData('stocknum_all') && $model->getData('shareholding_cofounders')) {
                        return number_format($model->getData('shareholding_cofounders')/$model->getData('stocknum_all')*100,2).'%';
                    }
                    return $model->getData('shareholding_member');
                }],
                ['name'=>'shareholding_esop','label'=>'ESOP股数','type'=>'number','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'field'=>function($model){
                    return is_numeric($model->getData('shareholding_esop')) ? number_format($model->getData('shareholding_esop')) : $model->getData('shareholding_esop');
                }],
                ['name'=>'_shareholding_ratio_esop','label'=>'ESOP比例','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    if ($model->getData('stocknum_all')) {
                        return sprintf('%.2f%%', $model->getData('shareholding_esop') / $model->getData('stocknum_all') * 100);
                    }
                }],
                ['name'=>'field-seperator-shareholding-entity','label'=>'源码各主体本轮统计','type'=>'seperator2'],
                ['name'=>'_shareholding_turn_sum','label'=>'截止本轮源码合计持股数','type'=>'rawText','default'=>null,'required'=>false,'help'=>'源码各轮次各主体的合计数，扣除了退出的。','field'=>function($model)use(&$deals,&$shareholdingSum){
                    $stockNum = 0;
                    if (!$model->getData('close_date')) {
                        return '未交割';
                    }
                    foreach($deals as $i => $deal) {
                        if ($deal->getData('close_date') > $model->getData('close_date')) {
                            continue;
                        }
                        if ($deal->getData('deal_type') == '源码退出') {
                            $stockNum -= $deal->getData('exit_stock_number');
                        } elseif (strpos($deal->getData('deal_type'), '源码投') !== false) {
                            $stockNum += $deal->getData('stocknum_get');
                        }
                    }
                    $shareholdingSum = $stockNum;
                    return number_format($stockNum);
                }],
                ['name'=>'_shareholding_ratio_turn_sum','label'=>'截止本轮源码合计持股比','type'=>'rawText','default'=>null,'required'=>false,'help'=>'源码各轮次各主体的合计比例，扣除了退出的。','field'=>function($model)use(&$deals,&$shareholdingSum){
                    $dataList = [];
                    if (!$model->getData('close_date')) {
                        return '未交割';
                    }
                    foreach($deals as $i => $dataItem) {
                        if ($dataItem->getData('close_date') > $model->getData('close_date')) {
                            continue;
                        }
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $i => $deal) {
                        return sprintf('%.2f%%', $shareholdingSum / $deal->getData('stocknum_all') * 100);
                    }
                }],
                ['name'=>'field-index-shareholding-latest','label'=>'源码最新持股情况','type'=>'seperator'],
                ['name'=>'field-seperator-shareholding-our','label'=>'源码所持本轮次股权最新','type'=>'seperator2'],
                ['name'=>'_stocknum_new','label'=>'本主体最新持本轮股数','type'=>'rawText','default'=>null,'required'=>false,'help'=>'源码本主体投时持有本轮股数“减去已转让本轮股权股数。','field'=>function($model)use(&$deals,&$stockNumNew){
                    $stockNum = 0;
                    foreach($deals as $i => $deal) {
                        if ($model->getData('entity_id') && $deal->getData('entity_id') == $model->getData('entity_id') && strpos($deal->getData('deal_type'), '源码投') !== false && $deal->getData('invest_turn') == $model->getData('invest_turn')) {
                            $stockNum += $deal->getData('stocknum_get');
                        }
                        if ($model->getData('entity_id') && $deal->getData('exit_entity_id') == $model->getData('entity_id') && strpos($deal->getData('deal_type'), '源码退出') !== false && $deal->getData('exit_turn') == $model->getData('invest_turn')) {
                            $stockNum -= $deal->getData('exit_stock_number');
                        }
                    }
                    $stockNumNew = $stockNum;
                    return number_format($stockNum);
                }],
                ['name'=>'_shareholding_ratio','label'=>'本主体最新持本轮股比','type'=>'rawText','default'=>null,'required'=>false,'help'=>'源码本主体最新持有本轮股数“除以”最新企业总股数“','field'=>function($model)use(&$deals,&$stockNumNew){
                    $dataList = [];
                    foreach($deals as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $i => $deal) {
                        if ($deal->getData('stocknum_all')) {
                            return sprintf('%.2f%%', $stockNumNew / $deal->getData('stocknum_all') * 100);
                        }
                    }
                }],
                ['name'=>'field-seperator-shareholding-entity','label'=>'源码各主体最新情况统计','type'=>'seperator2'],
                ['name'=>'_shareholding_sum','label'=>'源码各主体合计持股数','type'=>'rawText','default'=>null,'required'=>false,'help'=>'源码各轮次各主体的合计数，扣除了退出的。','field'=>function($model)use(&$deals,&$shareholdingSum){
                    $stockNum = 0;
                    foreach($deals as $i => $deal) {
                        if ($deal->getData('deal_type') == '源码退出') {
                            $stockNum -= $deal->getData('exit_stock_number');
                        } elseif (strpos($deal->getData('deal_type'), '源码投') !== false) {
                            $stockNum += $deal->getData('stocknum_get');
                        }
                    }
                    $shareholdingSum = $stockNum;
                    return number_format($stockNum);
                }],
                ['name'=>'_shareholding_ratio_sum','label'=>'源码各主体合计股比','type'=>'rawText','default'=>null,'required'=>false,'help'=>'源码各轮次各主体的合计比例，扣除了退出的。','field'=>function($model)use(&$deals,&$shareholdingSum){
                    $dataList = [];
                    foreach($deals as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $i => $deal) {
                        if ($deal->getData('stocknum_all'))
                            return sprintf('%.2f%%', $shareholdingSum / $deal->getData('stocknum_all') * 100);
                    }
                }],
                ['name'=>'field-index-term-investorlimit','label'=>'核心条款：对本轮投资人限制','type'=>'seperator'],
                ['name'=>'invest_competitor_limit','label'=>'投资人投资竞品限制','type'=>'choice','choices'=>Model_Project::getStandardOptionChoices(),'required'=>false,],
                ['name'=>'stock_transfer_limit','label'=>'投资人股权转让竞品限制','type'=>'choice','choices'=>Model_Project::getStandardOptionChoices(),'required'=>false,],
                ['name'=>'stock_transfer_limit_other','label'=>'投资人股权转让其他限制','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'limit_other','label'=>'源码其他限制/责任备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'field-index-term-founderlimit','label'=>'核心条款：对创始人限制','type'=>'seperator'],
                ['name'=>'founder_transfer_limit','label'=>'创始人股转限制','type'=>'choice','choices'=>Model_Project::getStandardOptionChoices(),'required'=>false,],
                ['name'=>'founder_transfer_limit_abstract','label'=>'创始人股转限制简述','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'founder_vesting','label'=>'对创始人Vesting','type'=>'choice','choices'=>Model_Project::getStandardOptionChoices(),'required'=>false,],
                ['name'=>'founder_vesting_expiration','label'=>'创始人Vesting期限','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'help'=>'写月数，示范“48个月”'],
                ['name'=>'founder_vesting_memo','label'=>'创始人Vesting备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'founder_bussiness_limit','label'=>'创始人竞业限制','type'=>'choice','choices'=>Model_Project::getStandardOptionChoices(),'required'=>false,],
                ['name'=>'founder_limit_memo','label'=>'创始人限制备忘','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false],
                ['name'=>'field-index-management','label'=>'核心条款：企业治理','type'=>'seperator'],
                ['name'=>'board_number','label'=>'公司董事席位数','type'=>'text','default'=>null,'required'=>false,'help'=>'示范“3”'],
                ['name'=>'our_board','label'=>'源码董事会席位','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false,],
                ['name'=>'our_board_person','label'=>'源码董事姓名','type'=>'text','default'=>null,'required'=>false,'help'=>'填入全名，若已退则写“***退出席位”'],
                ['name'=>'our_board_status','label'=>'源码董事状态','type'=>'choice','choices'=>Model_Project::getOurBoardStatusChoices(),'required'=>false,],
                ['name'=>'our_board_register','label'=>'源码董事登记','type'=>'choice','choices'=>Model_Project::getOurBoardRegisterChoices(),'required'=>false,],
                ['name'=>'observer','label'=>'源码观察员','type'=>'choice','choices'=>Model_Project::getObserverChoices(),'required'=>false,],
                ['name'=>'supervisor','label'=>'源码监事','type'=>'choice','choices'=>Model_Project::getSupervisorChoices(),'required'=>false,],
                ['name'=>'holder_veto','label'=>'源码股东会veto','type'=>'choice','choices'=>Model_Project::getStandardVetoChoices(),'required'=>false,],
                ['name'=>'board_veto','label'=>'源码董事会veto','type'=>'choice','choices'=>Model_Project::getStandardVetoChoices(),'required'=>false],
                ['name'=>'veto_memo','label'=>'Veto备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'field-index-inforight','label'=>'核心条款：信息权','type'=>'seperator'],
                ['name'=>'info_right','label'=>'源码信息权','type'=>'choice','choices'=>Model_Project::getStandardRightChoices(),'required'=>false,],
                ['name'=>'info_right_threshold','label'=>'信息权门槛','type'=>'selectInput','choices'=>[['门槛无特别约定','门槛无特别约定']],'required'=>false,],
                ['name'=>'info_right_memo','label'=>'信息权说明','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,],
                ['name'=>'check_right','label'=>'源码检查权','type'=>'choice','choices'=>Model_Project::getStandardRightChoices(),'required'=>false,],
                ['name'=>'audit_right','label'=>'源码审计权','type'=>'choice','choices'=>Model_Project::getStandardRightChoices(),'required'=>false,],
                ['name'=>'field-index-right','label'=>'核心条款：本轮轮次优先权','type'=>'seperator'],
                ['name'=>'field-seperator-warrant','label'=>'Warrant','type'=>'seperator2'],
                ['name'=>'warrant','label'=>'源码是否有Warrant','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false],
                ['name'=>'warrant_memo','label'=>'Warrant备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'others_warrant','label'=>'其他投资人是否有Warrant','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false],
                ['name'=>'others_warrant_memo','label'=>'其他投资人Warrant备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'field-seperator-preright','label'=>'优先认购权、优先购买权及共售权','type'=>'seperator2'],
                ['name'=>'preemptive','label'=>'优先认购权','type'=>'choice','choices'=>Model_Project::getStandardRightChoices(),'required'=>false,],
                ['name'=>'preemptive_memo','label'=>'优先认购权备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'excess_preemptive','label'=>'超额优先认购权','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false,],
                ['name'=>'pri_assignee','label'=>'对创始人优先受让权','type'=>'choice','choices'=>Model_Project::getStandardRightChoices(),'required'=>false,],
                ['name'=>'sell_together','label'=>'对创始人共售权','type'=>'choice','choices'=>Model_Project::getStandardRightChoices(),'required'=>false,],
                ['name'=>'pri_common_stock','label'=>'对非创始人普通股优先权','type'=>'choice','choices'=>Model_Project::getStandardRightChoices(),'required'=>false,],
                ['name'=>'field-seperator-buyback','label'=>'回购权及QIPO','type'=>'seperator2'],
                ['name'=>'buyback_right','label'=>'回购权','type'=>'choice','choices'=>Model_Project::getStandardRightChoices(),'required'=>false,],
                ['name'=>'buyback_obligor','label'=>'回购义务人','type'=>'selectInput','choices'=>Model_Project::getBuybackObligorChoices(),'required'=>false,],
                ['name'=>'buyback_standard','label'=>'回购金额计算标准','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,],
                ['name'=>'buyback_date','label'=>'本轮投资人可回购时间','type'=>'date','default'=>null,'required'=>false,'help'=>'大致时间即可','field'=>function($model){
                    return $model->getData('buyback_date') ? date('Ymd', $model->getData('buyback_date')) : false;
                }],
                ['name'=>'buyback_memo','label'=>'回购权备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'qualified_ipo_period','label'=>'QIPO简述','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false],
                ['name'=>'field-seperator-antidilution','label'=>'反稀释权','type'=>'seperator2'],
                ['name'=>'anti_dilution','label'=>'反稀释权','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false,],
                ['name'=>'anti_dilution_way','label'=>'反稀释方法','type'=>'choice','choices'=>Model_Project::getAntiDilutionWayChoices(),'required'=>false],
                ['name'=>'field-seperator-dragalong','label'=>'拖售权','type'=>'seperator2'],
                ['name'=>'drag_along','label'=>'拖售权','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false],
                ['name'=>'drag_along_veto','label'=>'源码对拖售权独立Veto','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false],
                ['name'=>'drag_along_memo','label'=>'拖售权备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'field-seperator-liquidation','label'=>'优先清算权','type'=>'seperator2'],
                ['name'=>'liquidation_preference','label'=>'优先清算权','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false],
                ['name'=>'liquidation_preference_way','label'=>'优先清算权方法','type'=>'choice','choices'=>Model_Project::getLiquidationPreferenceWayChoices(),'required'=>false],
                ['name'=>'liquidation_preference_memo','label'=>'优先清算权备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'field-seperator-register','label'=>'登记、分红、对赌','type'=>'seperator2'],
                ['name'=>'register_right','label'=>'登记权','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false],
                ['name'=>'dividends_preference','label'=>'（优先）分红权','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false],
                ['name'=>'dividends_preference_memo','label'=>'（优先）分红权备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'valuation_adjustment','label'=>'源码对赌/估值调整','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false],
                ['name'=>'valuation_adjustment_memo','label'=>'源码对赌/估值调整简述','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'others_valuation_adjustment','label'=>'其他投资人对赌/估值调整','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false],
                ['name'=>'others_valuation_adjustment_memo','label'=>'其他投资人对赌/估值调整简述','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'field-seperator-other','label'=>'源码其他权利','type'=>'seperator2'],
                ['name'=>'most_favored','label'=>'源码是否有最惠国待遇','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false],
                ['name'=>'most_favored_memo','label'=>'最惠国待遇备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'rights_memo','label'=>'源码权利备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'field-index-other-terms','label'=>'交易文件其他重要条款','type'=>'seperator'],
                ['name'=>'delivery_duty','label'=>'重要交割后义务','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'right_changes','label'=>'源码前轮重要权利变化','type'=>'choice','choices'=>Model_Project::getStandard4OptionChoices(),'required'=>false],
                ['name'=>'right_update_record','label'=>'源码前轮权利更新记录','type'=>'choice','choices'=>Model_Project::getRightUpdateChoices(),'required'=>false],
                ['name'=>'right_changes_memo','label'=>'源码前轮权利变化备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'latest_right_changes','label'=>'本轮与上轮投资权利变化','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,],
                ['name'=>'spouse_consent','label'=>'配偶同意函','type'=>'choice','choices'=>Model_Project::getStandard4OptionChoices(),'required'=>false],
                ['name'=>'risk_tip','label'=>'重大风险提示','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'ts_changes','label'=>'与TS比重大变化','type'=>'choice','choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false],
                ['name'=>'ts_changes_memo','label'=>'与TS比重大变化备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'risk_management_view','label'=>'风控保留意见','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'terms_memo','label'=>'条款其他备注','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'good_item','label'=>'好条款摘选','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'input'=>'textarea'],
                ['name'=>'field-index-entity-detail','label'=>'源码投退主体详情','type'=>'seperator'],
                ['name'=>'_entity_type','label'=>'源码投资主体类型','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    $entity = new Model_Entity;
                    $entity->mId = $model->getData('entity_id');
                    $entity->select();
                    return $entity->getData('tp');
                }],
                ['name'=>'_entity_currency','label'=>'源码主体资金币种','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    $entity = new Model_Entity;
                    $entity->mId = $model->getData('entity_id');
                    $entity->select();
                    return $entity->getData('currency');
                }],
                ['name'=>'entity_odi','label'=>'源码主体ODI','type'=>'choice','choices'=>Model_Project::getEntityOdiChoices(),'required'=>false],
                ['name'=>'mirror_hold','label'=>'镜像持股','type'=>'choice','choices'=>Model_Project::getMirrorHoldChoices(),'required'=>false],
                ['name'=>'mirror_hold_ratio','label'=>'镜像持股比例','type'=>'choice','choices'=>Model_Project::getMirrorHoldRatioChoices(),'required'=>false],
                ['name'=>'entrustment','label'=>'是否存在代持情况','type'=>'choice','choices'=>Model_Project::getStandardOptionChoices(),'required'=>false],
                ['name'=>'entrustment_entity_id','label'=>'代持主体','type'=>'choosemodel','model'=>'Model_Entity','default'=>0,'required'=>false,'help'=>'选择填入代持主体全称，如有'],
                ['name'=>'field-index-staff','label'=>'项目组成员','type'=>'seperator'],
                ['name'=>'_current_partner','label'=>'最新主管合伙人','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$company){
                    $members = Model_Member::listAll();
                    if ($company->mPartner) {
                        return $members[$company->mPartner]->mName;
                    }
                }],
                ['name'=>'_current_manager','label'=>'最新项目负责人','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$company){
                    $members = Model_Member::listAll();
                    if ($company->mManager) {
                        return $members[$company->mManager]->mName;
                    }
                }],
                ['name'=>'_current_finance_person','label'=>'最新财务负责人','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$company){
                    $members = Model_Member::listAll();
                    if ($company->mFinancePerson) {
                        return $members[$company->mFinancePerson]->mName;
                    }
                }],
                ['name'=>'_current_legal_person','label'=>'最新法务负责人','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$company){
                    $members = Model_Member::listAll();
                    if ($company->mLegalPerson) {
                        return $members[$company->mLegalPerson]->mName;
                    }
                }],
                ['name'=>'partner','label'=>'本轮主管合伙人','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'manager','label'=>'本轮项目负责人','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'finance_person','label'=>'本轮财务负责人','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'legal_person','label'=>'本轮法务负责人','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'deal_manager','label'=>'本轮交易负责人','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false],
                ['name'=>'law_firm','label'=>'源码委托律所','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false],
                ['name'=>'lawyer_fee','label'=>'律师费','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'field-index-archive','label'=>'Filling及Post','type'=>'seperator'],
                ['name'=>'final_captable','label'=>'FinalCaptalbe','type'=>'choice','choices'=>Model_Project::getStandardArchiveChoices(),'required'=>false],
                ['name'=>'final_word','label'=>'FinalWord','type'=>'choice','choices'=>Model_Project::getStandardArchiveChoices(),'required'=>false],
                ['name'=>'closing_pdf','label'=>'ClosingPDF','type'=>'choice','choices'=>Model_Project::getStandardArchiveChoices(),'required'=>false],
                ['name'=>'closing_original','label'=>'Closing原件','type'=>'choice','choices'=>Model_Project::getStandardArchiveChoices(),'required'=>false],
                ['name'=>'overseas_stockcert','label'=>'境外股票证书','type'=>'choice','choices'=>Model_Project::getStandardArchiveChoices(),'required'=>false],
                ['name'=>'aic_registration','label'=>'人民币项目工商','type'=>'choice','choices'=>Model_Project::getAicRegistraionChoices(),'required'=>false],
                ['name'=>'pending','label'=>'有无未决事项','type'=>'choice','choices'=>Model_Project::getPendingChoices(),'required'=>false],
                ['name'=>'pending_detail','label'=>'未决事项说明','type'=>'selectInput','choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false],
                ['name'=>'field-index-memo','label'=>'工作记录及备忘','type'=>'seperator'],
                ['name'=>'_memo','label'=>'工作备忘','type'=>'rawText','required'=>false,'field'=>function($model){
                    $memo = new Model_DealMemo;
                    $memo->addWhere('project_id', $model->getData('id'));
                    $memo->orderBy('update_time', 'desc');
                    $memo->select();
                    $output = '';
                    if ($memo->getData('id')) {
                        $output .= date('Ymd H:i:s', $memo->getData('update_time'))." ".$memo->getData('operator')." ".$memo->getData('title')." ".$memo->getData('content').' <a target="_blank" href="/admin/DealMemo?__filter='.urlencode('project_id='.$model->getData('id')).'">列表 </a>';
                    }
                    return $output .= '<a target="_blank" href="/admin/DealMemo?action=read&project_id='.$model->getData('id').'">添加+</a>';
                }],
                ['name'=>'update_time','label'=>'更新时间','type'=>'datetime','readonly'=>'true','default'=>time(),'auto_update'=>true,'field'=>function($model){
                    return date('Ymd H:i:s', $model->getData('update_time'));
                }],
                ['name'=>'create_time','label'=>'创建时间','type'=>'hidden','readonly'=>'true','default'=>time(),'field'=>function($model){
                    return date('Ymd H:i:s', $model->getData('create_time'));
                }],
                ['name'=>'field-index-recheck','label'=>'记录校对情况','type'=>'seperator'],
                ['name'=>'finance_check_sign','label'=>'财务签名','type'=>'text','required'=>false],
                ['name'=>'finance_check_time','label'=>'财务校对时间','type'=>'datetime','default'=>null,'field'=>function($model){
                    if ($model->getData('finance_check_time'))
                        return date('Ymd H:i:s', $model->getData('finance_check_time'));
                }],
                ['name'=>'legal_check_sign','label'=>'法务签名','type'=>'text','required'=>false],
                ['name'=>'legal_check_time','label'=>'法务校对时间','type'=>'datetime','default'=>null,'field'=>function($model){
                    if ($model->getData('legal_check_time'))
                        return date('Ymd H:i:s', $model->getData('legal_check_time'));
                }],
            ];
        }
        return self::$fieldsMap;
    }

    public function __construct() {
        parent::__construct(self::getFieldsMap());
        //self::createSqlFields();
    }
}
