<?php
class ProjectController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    private $_objectCache = [];

    private function _initForm() {
        $this->form=new Form(array(
            array('name'=>'status','label'=>'数据状态','type'=>"hidden", 'default'=>'valid','required'=>true,),
            array('name'=>'company_id','label'=>'目标企业','type'=>"choosemodel",'model'=>'Model_Company','default'=>$_GET['company_id'],'required'=>true,),
            array('name'=>'_company_short','label'=>'项目简称','type'=>"rawText",'required'=>true,),
            array('name'=>'_company_id','label'=>'企业ID','type'=>"rawText",'required'=>true,),
            array('name'=>'first_financing','label'=>'企业是否首次融资','type'=>"choice",'choices'=>Model_Project::getFirstFinancingChoices(),'required'=>true,),
            array('name'=>'company_period','label'=>'目标企业阶段','type'=>"selectInput", 'choices'=>[['早期','早期'],['成长期','成长期'],['PreIPO','PreIPO'],['不适用','不适用']],'required'=>false,),
            array('name'=>'company_character','label'=>'目标企业性质','type'=>"selectInput", 'choices'=>[['内资','内资'],['VIE','VIE'],['JV','JV'],['WFOE','WFOE'],['非境外VIE','非境外VIE'],['国内基金','国内基金'],['海外基金','海外基金'],['其他','其他']],'required'=>false,),
            array('name'=>'item_status','label'=>'整理状态','type'=>"choice",'choices'=>Model_Project::getItemStatusChoices(),'required'=>true,),
            array('name'=>'field-index-status','label'=>'本轮交易状态', 'type'=>'seperator'),
            array('name'=>'decision_date','label'=>'决策日期','type'=>"date",'default'=>null,'required'=>false,'help'=>'TS日期（优先）、IC决策日期、投资部告知的大致日期，尽量精确到月'),
            array('name'=>'kickoff_date','label'=>'Kick off日期','type'=>"date",'default'=>null,'required'=>false,'help'=>'法务部开始介入的日期，尽量精确到月'),
            array('name'=>'term_longstop_date','label'=>'Long Stop Date','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false),
            array('name'=>'proj_status','label'=>'交易状态','type'=>"choice",'choices'=>Model_Project::getProjStatusChoices(), 'required'=>false,),
            array('name'=>'close_date','label'=>'交割日期','type'=>"date",'default'=>null,),
            array('name'=>'field-index-base','label'=>'本轮交易基本信息', 'type'=>'seperator'),
            array('name'=>'deal_type','label'=>'本轮交易类型','type'=>"choice",'choices'=>Model_Project::getDealTypeChoices(), 'required'=>false,),
            array('name'=>'turn_sub','label'=>'企业所处轮次','type'=>"text", 'default'=>null,'required'=>false,'help'=>'按交易文件的界定填写，示范“A3”、“B+”'),
            array('name'=>'turn','label'=>'企业轮次归类','type'=>"choice",'choices'=>Model_Project::getTurnChoices(),'required'=>false,),
            array('name'=>'new_follow','label'=>'项目新老类型','type'=>"choice",'choices'=>Model_Project::getNewFollowChoices(), 'required'=>false,),
            array('name'=>'enter_exit_type','label'=>'源码投退类型','type'=>"choice",'choices'=>Model_Project::getEnterExitTypeChoices(), 'required'=>false,),
            array('name'=>'other_enter_exit_type','label'=>'其他投资人投退类型','type'=>"choice",'choices'=>Model_Project::getOtherEnterExitTypeChoices(), 'required'=>false,),
            array('name'=>'res_consideration','label'=>'是否有资源作价','type'=>"choice",'choices'=>Model_Project::getResConsiderationChoices(), 'required'=>false,),
            array('name'=>'consideration_memo','label'=>'资源作价备注','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false),
            array('name'=>'deal_memo','label'=>'本轮交易方案备注','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'default'=>null,'required'=>false),
            array('name'=>'field-index-value','label'=>'企业估值及每股单价', 'type'=>'seperator'),
            array('name'=>'value_currency','label'=>'估值计价货币','type'=>"choice",'choices'=>Model_Project::getCurrencyChoices(),'required'=>false,),
            array('name'=>'pre_money','label'=>'企业投前估值','type'=>"text",'required'=>false,),
            array('name'=>'financing_amount','label'=>'本轮新股融资总额','type'=>"text", 'default'=>null,'required'=>false,'help'=>'仅为新股融资金额，不包括老股金额'),
            array('name'=>'post_money','label'=>'企业投后估值','type'=>"text",'required'=>false,'help'=>'（1）默认值为“企业投前估值“+”本轮新股融资总金额“；若有打折等情况影响估值计算，则手动计算填写；（2）企业若本轮未发生融资则写当前估值。'),
            array('name'=>'_stock_price','label'=>'企业每股单价','type'=>"rawText",'required'=>false,'help'=>'本轮“企业投后估值“ 除以 本轮“企业投后总股数”'),
            array('name'=>'_preturn_stock_price','label'=>'企业上轮每股单价','type'=>"rawText",'required'=>false),
            array('name'=>'value_change','label'=>'企业估值涨幅（VS上轮）','type'=>"text", 'default'=>null,'required'=>false,'help'=>'本轮“企业每股单价“ 除以 ”企业上一轮每股单价“；1X 指平价未增资。'),
            array('name'=>'field-index-plan','label'=>'源码投资方案', 'type'=>'seperator'),
            array('name'=>'new_old_stock','label'=>'源码购新股老股','type'=>"choice",'choices'=>Model_Project::getNewOldStockChoices(), 'required'=>false,),
            array('name'=>'invest_currency','label'=>'源码投资计价货币','type'=>"choice",'choices'=>Model_Project::getInvestCurrencyChoices(),'required'=>false,),
            array('name'=>'entity_id','label'=>'源码投资主体','type'=>"choosemodel",'model'=>'Model_Entity','default'=>$_GET['entity_id']?$_GET['entity_id']:0,'required'=>false,),
            array('name'=>'_entity_name','label'=>'源码投资主体描述','type'=>"rawText",'readonly'=>true,'default'=>' ','required'=>false,),
            array('name'=>'our_amount','label'=>'源码合同投资金额','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'stocknum_get','label'=>'本主体投时持有本轮股数','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'_stock_ratio','label'=>'本主体投时持股比例','type'=>"rawText",'readonly'=>true,'default'=>' ','required'=>false,),
            array('name'=>'invest_turn','label'=>'本主体购买股权所属轮次','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'stock_property','label'=>'本主体购买股权属性','type'=>"choice",'choices'=>Model_Project::getStockPropertyChoices(),'required'=>false,),
            array('name'=>'_invest_stock_price','label'=>'投资时每股单价','type'=>"rawText",'readonly'=>true,'default'=>' ','required'=>false,),
            array('name'=>'_pay_amount','label'=>'源码实际支付投资金额','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'default'=>' ','required'=>false,),
            array('name'=>'amount_memo','label'=>'金额备注','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'field-index-loan','label'=>'源码借款或源码CB', 'type'=>'seperator'),
            array('name'=>'loan_currency','label'=>'借款计价货币','type'=>"choice",'choices'=>Model_Project::getCurrencyChoices(),'required'=>false,),
            array('name'=>'loan_type','label'=>'借款类型','type'=>"choice",'choices'=>Model_Project::getLoanTypeChoices(),'required'=>false,),
            array('name'=>'loan_entity_id','label'=>'源码出借主体','type'=>"choosemodel",'model'=>'Model_Entity','default'=>0,'required'=>false,),
            array('name'=>'loan_amount','label'=>'源码借款合同金额','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'loan_sign_date','label'=>'借款合同签署日期','type'=>"date", 'default'=>null,'required'=>false,),
            array('name'=>'loan_end_date','label'=>'借款到期日','type'=>"date", 'default'=>null,'required'=>false,),
            array('name'=>'loan_process','label'=>'借款处理','type'=>"choice",'choices'=>Model_Project::getLoanProcessChoices(),'required'=>false,),
            array('name'=>'loan_memo','label'=>'借款备注','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'help'=>'若多笔过桥借款或过桥CB,第二笔单独填写，其他栏可视情况填不适用'),
            array('name'=>'field-index-otherinvestor','label'=>'其他投资人（非源码）投资方案', 'type'=>'seperator'),
            array('name'=>'other_investor','label'=>'本轮其他投资方','type'=>"choice",'choices'=>Model_Project::getOtherInvestorChoices(),'required'=>false,),
            array('name'=>'other_investor_summary','label'=>'其他投资人及金额与投资比例摘要','type'=>"textarea", 'default'=>null,'required'=>false,'help'=>'选一两个填即可。'),
            array('name'=>'field-index-exit','label'=>'源码退出方案及详情', 'type'=>'seperator'),
            array('name'=>'exit_currency','label'=>'源码退出计价货币','type'=>"choice",'choices'=>Model_Project::getCurrencyChoices(),'required'=>false,),
            array('name'=>'exit_type','label'=>'源码退出方式','type'=>"choice",'choices'=>Model_Project::getExitTypeChoices(),'required'=>false,),
            array('name'=>'exit_profit','label'=>'退出盈亏情况','type'=>"choice",'choices'=>Model_Project::getExitProfitChoices(),'required'=>false,),
            array('name'=>'exit_entity_id','label'=>'源码退出主体','type'=>"choosemodel",'model'=>'Model_Entity','default'=>0,'required'=>false,),
            array('name'=>'exit_entity_name','label'=>'源码退出描述','type'=>"text",'required'=>false,),
            array('name'=>'exit_company_value','label'=>'源码退出的企业估值','type'=>"text",'required'=>false,),
            array('name'=>'exit_stock_number','label'=>'源码退出的股数','type'=>"text",'required'=>false,),
            array('name'=>'exit_amount','label'=>'本交易退出的合同金额','type'=>"text",'required'=>false,),
            array('name'=>'exit_stock_price','label'=>'源码退出的每股单价','type'=>"rawText",'required'=>false,),
            array('name'=>'exit_stock_ratio','label'=>'源码退出的比例','type'=>"rawText",'required'=>false,),
            array('name'=>'exit_turn','label'=>'源码出售股权所属轮次','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'exit_stock_property','label'=>'源码出售的股权属性','type'=>"choice",'choices'=>Model_Project::getStockPropertyChoices(),'required'=>false,),
            array('name'=>'exit_receive_amount','label'=>'源码本次退出实收金额','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'exit_memo','label'=>'源码退出备注','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'_exit_return_rate','label'=>'源码本次退出回报倍数（gross）','type'=>"rawText", 'default'=>null,'required'=>false,),
            array('name'=>'field-index-shareholding','label'=>'本轮Post企业股权结构', 'type'=>'seperator'),
            array('name'=>'stocknum_all','label'=>'本轮企业总股数','type'=>"text", 'default'=>null,'required'=>false,'help'=>'交割后的股数或注册资本'),
            array('name'=>'_stocknum_new','label'=>'源码本主体最新持有本轮股数','type'=>"rawText", 'default'=>null,'required'=>false,'help'=>'源码本主体投时持有本轮股数“ 减去已转让本轮股权股数。'),
            array('name'=>'_shareholding_ratio','label'=>'源码本主体最新持有本轮股比','type'=>"rawText", 'default'=>null,'required'=>false,'help'=>'源码本主体最新持有本轮股数“ 除以 ”本轮企业总股数“'),
            array('name'=>'shareholding_founder','label'=>'最主要创始人股数','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'_shareholding_ratio_founder','label'=>'最主要创始人股比','type'=>"rawText", 'default'=>null,'required'=>false,'help'),
            array('name'=>'shareholding_member','label'=>'团队持股比例(不含ESOP)','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'shareholding_esop','label'=>'ESOP股数','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'_shareholding_ratio_esop','label'=>'ESOP比例','type'=>"rawText", 'default'=>null,'required'=>false,),
            array('name'=>'_shareholding_sum','label'=>'源码各主体合计持股数','type'=>"rawText", 'default'=>null,'required'=>false,'help'=>'源码各轮次各主体的合计数，扣除了退出的。'),
            array('name'=>'_shareholding_ratio_sum','label'=>'源码各主体合计股比','type'=>"rawText", 'default'=>null,'required'=>false,'help'=>'源码各轮次各主体的合计比例，扣除了退出的。'),
            array('name'=>'field-index-term-investorlimit','label'=>'核心条款：对本轮投资人限制', 'type'=>'seperator'),
            array('name'=>'term_limit','label'=>'投资人投资竞品限制','type'=>"choice", 'choices'=>Model_Project::getStandardOptionChoices(),'required'=>false,),
            array('name'=>'term_stock_transfer_limit','label'=>'投资人股权转让竞品限制','type'=>"choice", 'choices'=>Model_Project::getStandardOptionChoices(),'required'=>false,),
            array('name'=>'term_stock_transfer_limit_other','label'=>'投资人股权转让其他限制','type'=>"selectInput", 'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'term_limit_other','label'=>'源码其他限制/责任备注','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'field-index-term-founderlimit','label'=>'核心条款：对创始人限制', 'type'=>'seperator'),
            array('name'=>'term_founder_transfer_limit','label'=>'创始人股转限制','type'=>"choice",'choices'=>Model_Project::getStandardOptionChoices(),'required'=>false,),
            array('name'=>'term_founder_transfer_limit_abstract','label'=>'创始人股转限制简述','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'term_founder_vesting','label'=>'对创始人Vesting','type'=>"choice",'choices'=>Model_Project::getStandardOptionChoices(),'required'=>false,),
            array('name'=>'term_founder_vesting_expiration','label'=>'创始人Vesting期限','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'help'=>'写月数，示范“48个月”'),
            array('name'=>'term_founder_vesting_memo','label'=>'创始人Vesting备注','type'=>"text",'required'=>false,),
            array('name'=>'term_founder_bussiness_limit','label'=>'创始人竞业限制','type'=>"choice",'choices'=>Model_Project::getStandardOptionChoices(),'required'=>false,),
            array('name'=>'term_founder_limit_memo','label'=>'创始人限制备忘','type'=>"textarea",'required'=>false),
            array('name'=>'field-index-management','label'=>'核心条款：企业治理及信息权', 'type'=>'seperator'),
            array('name'=>'board_number','label'=>'公司董事席位数','type'=>"text", 'default'=>null,'required'=>false,'help'=>'示范“3”'),
            array('name'=>'our_board','label'=>'源码董事会席位','type'=>"choice",'choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false,),
            array('name'=>'our_board_status','label'=>'源码董事状态','type'=>"choice",'choices'=>Model_Project::getOurBoardStatusChoices(),'required'=>false,),
            array('name'=>'our_board_register','label'=>'源码董事登记','type'=>"choice",'choices'=>Model_Project::getOurBoardRegisterChoices(),'required'=>false,),
            array('name'=>'observer','label'=>'源码观察员','type'=>"choice",'choices'=>Model_Project::getObserverChoices(),'required'=>false,),
            array('name'=>'info_right','label'=>'源码信息权','type'=>"choice",'choices'=>Model_Project::getStandardRightChoices(),'required'=>false,),
            array('name'=>'info_right_threshold','label'=>'信息权门槛','type'=>"selectInput",'choices'=>[['门槛无特别约定','门槛无特别约定']],'required'=>false,),
            array('name'=>'info_right_memo','label'=>'信息权说明','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'check_right','label'=>'源码检查权','type'=>"choice",'choices'=>Model_Project::getStandardRightChoices(),'required'=>false,),
            array('name'=>'audit_right','label'=>'源码审计权','type'=>"choice",'choices'=>Model_Project::getStandardRightChoices(),'required'=>false,),
            array('name'=>'holder_veto','label'=>'股东会veto','type'=>"choice",'choices'=>Model_Project::getStandardVetoChoices(),'required'=>false,),
            array('name'=>'board_veto','label'=>'董事会veto','type'=>"choice",'choices'=>Model_Project::getStandardVetoChoices(),'required'=>false),
            array('name'=>'veto_memo','label'=>'Veto备注','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'field-index-right','label'=>'核心条款：本轮优先权', 'type'=>'seperator'),
            array('name'=>'warrant','label'=>'源码是否有Warrant','type'=>"choice",'choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false),
            array('name'=>'warrant_memo','label'=>'Warrant备注','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'others_warrant','label'=>'其他投资人是否有Warrant','type'=>"choice",'choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false),
            array('name'=>'others_warrant_memo','label'=>'其他投资人Warrant备注','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'preemptive','label'=>'优先认购权','type'=>"choice",'choices'=>Model_Project::getStandardRightChoices(),'required'=>false,),
            array('name'=>'excess_preemptive','label'=>'超额优先认购权','type'=>"choice",'choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false,),
            array('name'=>'pri_assignee','label'=>'对创始人优先受让权','type'=>"choice",'choices'=>Model_Project::getStandardRightChoices(),'required'=>false,),
            array('name'=>'sell_together','label'=>'对创始人共售权','type'=>"choice",'choices'=>Model_Project::getStandardRightChoices(),'required'=>false,),
            array('name'=>'pri_common_stock','label'=>'对非创始人普通股优先权','type'=>"choice",'choices'=>Model_Project::getStandardRightChoices(),'required'=>false,),
            array('name'=>'buyback_right','label'=>'回购权','type'=>"choice",'choices'=>Model_Project::getStandardRightChoices(),'required'=>false,),
            array('name'=>'buyback_obligor','label'=>'回购义务人','type'=>"selectInput",'choices'=>Model_Project::getBuybackObligorChoices(),'required'=>false,),
            array('name'=>'buyback_standard','label'=>'回购金额计算标准','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'buyback_date','label'=>'本轮投资人可回购时间','type'=>"date", 'default'=>null,'required'=>false,'help'=>'大致时间即可'),
            array('name'=>'qualified_ipo_period','label'=>'合格IPO年限','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,'help'=>'格式示范“5年”'),
            array('name'=>'anti_dilution','label'=>'反稀释权','type'=>"choice",'choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false,),
            array('name'=>'anti_dilution_way','label'=>'反稀释方法','type'=>"choice",'choices'=>Model_Project::getAntiDilutionWayChoices(), 'required'=>false),
            array('name'=>'drag_along','label'=>'拖售权','type'=>"choice",'choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false),
            array('name'=>'drag_along','label'=>'源码对拖售权独立Veto','type'=>"choice",'choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false),
            array('name'=>'liquidation_preference','label'=>'优先清算权','type'=>"choice",'choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false),
            array('name'=>'liquidation_preference_way','label'=>'优先清算权方法','type'=>"choice",'choices'=>Model_Project::getLiquidationPreferenceWayChoices(),'required'=>false),
            array('name'=>'liquidation_preference_memo','label'=>'优先清算权备注','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'register_right','label'=>'登记权','type'=>"choice",'choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false),
            array('name'=>'dividends_preference','label'=>'优先分红权','type'=>"choice",'choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false),
            array('name'=>'valuation_adjustment','label'=>'源码对赌/估值调整','type'=>"choice",'choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false),
            array('name'=>'valuation_adjustment_memo','label'=>'源码对赌/估值调整简述','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'others_valuation_adjustment','label'=>'本轮其他投资人对赌/估值调整','type'=>"choice",'choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false),
            array('name'=>'others_valuation_adjustment_memo','label'=>'本轮其他投资人对赌/估值调整简述','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'most_favored','label'=>'源码是否有最惠国待遇','type'=>"choice",'choices'=>Model_Project::getStandard3OptionChoices(),'required'=>false),
            array('name'=>'rights_memo','label'=>'源码权利备注','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'field-index-other-terms','label'=>'交易文件其他重要条款', 'type'=>'seperator'),
            array('name'=>'right_changes','label'=>'源码前轮重要权利变化','type'=>"choice",'choices'=>Model_Project::getStandard4OptionChoices(),'required'=>false),
            array('name'=>'right_update_record','label'=>'源码前轮权利更新记录','type'=>"choice",'choices'=>Model_Project::getRightUpdateChoices(),'required'=>false),
            array('name'=>'latest_right_changes','label'=>'本轮与上轮投资权利变化','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false,),
            array('name'=>'spouse_consent','label'=>'配偶同意函','type'=>"choice",'choices'=>Model_Project::getStandard4OptionChoices(),'required'=>false),
            array('name'=>'good_item','label'=>'好条款摘选','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false),
            array('name'=>'terms_memo','label'=>'条款其他备注','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false),
            array('name'=>'field-index-entity-detail','label'=>'源码投退主体详情', 'type'=>'seperator'),
            array('name'=>'_entity_type','label'=>'源码投资主体类型','type'=>"rawText", 'default'=>null,'required'=>false,),
            array('name'=>'entity_odi','label'=>'源码主体ODI','type'=>"choice",'choices'=>Model_Project::getEntityOdiChoices(),'required'=>false),
            array('name'=>'mirror_hold','label'=>'镜像持股','type'=>"choice",'choices'=>Model_Project::getMirrorHoldChoices(),'required'=>false),
            array('name'=>'mirror_hold_ratio','label'=>'镜像持股比例','type'=>"choice",'choices'=>Model_Project::getMirrorHoldRatioChoices(),'required'=>false),
            array('name'=>'entrustment','label'=>'是否存在代持情况','type'=>"choice",'choices'=>Model_Project::getStandardOptionChoices(),'required'=>false),
            array('name'=>'entrustment_entity_id','label'=>'代持主体','type'=>"choosemodel",'model'=>'Model_Entity','default'=>0,'required'=>false,'help'=>'选择填入代持主体全称，如有'),
            array('name'=>'field-index-staff','label'=>'项目组成员', 'type'=>'seperator'),
            array('name'=>'_current_partner','label'=>'最新主管合伙人','type'=>"rawText", 'default'=>null,'required'=>false,),
            array('name'=>'manager','label'=>'本轮项目负责人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'_current_manager','label'=>'最新项目负责人','type'=>"rawText", 'default'=>null,'required'=>false,),
            array('name'=>'finance_person','label'=>'本轮法务负责人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'_current_finance_person','label'=>'最新财务负责人','type'=>"rawText", 'default'=>null,'required'=>false,),
            array('name'=>'legal_person','label'=>'本轮法务负责人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'_current_legal_person','label'=>'最新法务负责人','type'=>"rawText", 'default'=>null,'required'=>false,),
            array('name'=>'deal_manager','label'=>'本轮交易负责人','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false),
            array('name'=>'law_firm','label'=>'源码委托律所','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false),
            array('name'=>'field-index-archive','label'=>'Filling及Post', 'type'=>'seperator'),
            array('name'=>'final_captable','label'=>'Final Captalbe','type'=>"choice",'choices'=>Model_Project::getStandardArchiveChoices(),'required'=>false),
            array('name'=>'final_word','label'=>'Final Word','type'=>"choice",'choices'=>Model_Project::getStandardArchiveChoices(),'required'=>false),
            array('name'=>'closing_pdf','label'=>'Closing PDF','type'=>"choice",'choices'=>Model_Project::getStandardArchiveChoices(),'required'=>false),
            array('name'=>'closing_original','label'=>'Closing原件','type'=>"choice",'choices'=>Model_Project::getStandardArchiveChoices(),'required'=>false),
            array('name'=>'overseas_stockcert','label'=>'境外股票证书','type'=>"choice",'choices'=>Model_Project::getStandardArchiveChoices(),'required'=>false),
            array('name'=>'aic_registration','label'=>'人民币项目工商','type'=>"choice",'choices'=>Model_Project::getAicRegistraionChoices(),'required'=>false),
            array('name'=>'pending','label'=>'有无未决事项','type'=>"choice",'choices'=>Model_Project::getPendingChoices(),'required'=>false),
            array('name'=>'pending_detail','label'=>'未决事项说明','type'=>"selectInput",'choices'=>Model_Project::getStandardSelectInputChoices(),'required'=>false),
            array('name'=>'field-index-memo','label'=>'工作记录及备忘', 'type'=>'seperator'),
            array('name'=>'work_memo','label'=>'工作备忘','type'=>"textarea",'required'=>false),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime","readonly"=>'true','default'=>time(),'auto_update'=>true),
        ));
    }

    private function _initListDisplay() {
        $companyCache = new Model_Company;
        $this->list_display=array(
            ['label'=>'交易ID','field'=>function($model)use(&$companyCache){
                $companyCache->mId = $model->mCompanyId;
                $companyCache->select();
                return $model->mId;
            }],
            ['label'=>'企业ID','field'=>function($model){
                return $companyCache->mId;
            }],
            ['label'=>'项目简称','field'=>function($model)use(&$companyCache){
                return $companyCache->mShort;
            }],
            ['label'=>'整理状态','field'=>function($model){
                return $model->mItemStatus;
            }],
            ['label'=>'目标企业','field'=>function($model)use(&$companyCache){
                return "<a href='/admin/company?__filter=".urlencode("id=$companyCache->mId")."'>$companyCache->mName</a>";
            }],
            ['label'=>'所属行业','field'=>function($model){
                $company = $this->_getResource($model->mCompanyId, 'Company', new Model_Company);
                return ($company ? $company->mBussiness : '（公司出错）' );
            }],
            ['label'=>'轮次大类','field'=>function($model){
                return $model->mTurn;
            }],
            ['label'=>'轮次详情','field'=>function($model){
                return $model->mTurnSub;
            }],
            ['label'=>'新老类型','field'=>function($model){
                return $model->mNewFollow;
            }],
            ['label'=>'投退类型','field'=>function($model){
                return $model->mEnterExitType;
            }],
            ['label'=>'新股老股','field'=>function($model){
                return $model->mNewOldStock;
            }],
            ['label'=>'决策日期','field'=>function($model){
                if ($model->mDecisionDate > 0)
                    return date("Ymd", $model->mDecisionDate);
            }],
            ['label'=>'交易状态','field'=>function($model){
                return $model->mProjStatus;
            }],
            ['label'=>'Closing Date','field'=>function($model){
                if ($model->mCloseDate > 0)
                    return date("Ymd", $model->mCloseDate);
            }],
            ['label'=>'负责律所','field'=>function($model){
                return $model->mLawFirm;
            }],
            ['label'=>'观察员','field'=>function($model){
                return $model->mObserver;
            }],
            ['label'=>'信息权','field'=>function($model){
                return $model->mInfoRight;
            }],
            ['label'=>'信息权门槛','field'=>function($model){
                return $model->mInfoRightThreshold;
            }],
            ['label'=>'本轮计价货币','field'=>function($model){
                return $model->mCurrency;
            }],
            ['label'=>'公司投前估值','field'=>function($model){
                if ($model->mPreMoney)
                    return number_format($model->mPreMoney) . " " . $model->mCurrency;
            }],
            ['label'=>'本轮融资总额','field'=>function($model){
                if ($model->mFinancingAmount)
                    return number_format($model->mFinancingAmount) . " " . $model->mCurrency;
            }],
            ['label'=>'公司投后估值','field'=>function($model){
                if ($model->mPreMoney && $model->mFinancingAmount)
                    return number_format($model->mPreMoney + $model->mFinancingAmount) . " " . $model->mCurrency;
            }],
            ['label'=>'公司估值涨幅','field'=>function($model){
                return $model->mValueChange;
            }],
            ['label'=>'公司每股单价','field'=>function($model){
                $company = $this->_getResource($model->mCompanyId, 'Company', new Model_Company);
                if ($model->mPreMoney && $model->mFinancingAmount && $company->mTotalStock)
                    return number_format(($model->mPreMoney + $model->mFinancingAmount) / $company->mTotalStock, 2) . " " . $model->mCurrency;
            }],
            ['label'=>'源码投退主体','field'=>function($model){
                $entity = $this->_getResource($model->mEntityId, 'Entity', new Model_Entity);
                return $entity->mName;
            }],
            ['label'=>'RMB/USD','field'=>function($model){
                return $model->mRmbUsd;
            }],
            ['label'=>'期数/专项','field'=>function($model){
                return $model->mPeriod;
            }],
            ['label'=>'镜像持股','field'=>function($model){
                return $model->mMirror;
            }],
            ['label'=>'基金代持及主体','field'=>function($model){
                return $model->mEntrustment;
            }],
            ['label'=>'源码投资金额','field'=>function($model){
                if ($model->mOurAmount)
                    return number_format($model->mOurAmount) . ' ' . $model->mCurrency;
            }],
            ['label'=>'源码实际支付金额','field'=>function($model){
                $findField = 'project_id';
                $payment = new Model_Payment;
                $payment->addWhere($findField, $model->mId);
                $payment->setCols($findField);
                $payment->setCols('currency');
                $payment->addComputedCol('SUM(amount)', 'total_amount');
                $payment->groupBy($findField);
                $payment->groupBy('currency');
                $resStr = '';
                foreach ($payment->find() as $res) {
                    $data = $res->getData();
                    $resStr .= number_format($data['total_amount'],2) . ' ' . $data['currency'] . "\n";
                }
                return "<div class=data_item><a href='/admin/payment?__filter=".urlencode("project_id=".$model->mId)."'>$resStr</a>"."<a class=item_op href='/admin/payment?action=read&project_id=$model->mId'> +新增 </a></div>";
            }],
            ['label'=>'源码每股单价','field'=>function($model){
                if ($model->mOurAmount && $model->mStocknumGet)
                    return number_format($model->mOurAmount / $model->mStocknumGet, 2) . ' ' . $model->mCurrency;
            }],
            ['label'=>'其他投资人及金额','field'=>function($model){
                return $model->mOtherAmount;
            }],
            ['label'=>'金额备注','field'=>function($model){
                return $model->mAmountMemo;
            }],
            ['label'=>'源码退出金额','field'=>function($model){
                $findField = 'project_id';
                $investExit = new Model_InvestmentExit;
                $investExit->addWhere($findField, $model->mId);
                $investExit->setCols($findField);
                $investExit->setCols('currency');
                $investExit->addComputedCol('SUM(amount)', 'total_amount');
                $investExit->groupBy($findField);
                $investExit->groupBy('currency');
                $resStr = '';
                foreach ($investExit->find() as $res) {
                    $data = $res->getData();
                    $resStr .= number_format($data['total_amount'],2) . ' ' . $data['currency'] . "\n";
                }
                return "<div class=data_item><a href='/admin/investmentExit?__filter=".urlencode("project_id=".$model->mId)."'>$resStr</a>"."<a class=item_op href='/admin/investmentExit?action=read&project_id=$model->mId'> +新增 </a></div>";
            }],
            ['label'=>'源码退出股数','field'=>function($model){
                $findField = 'project_id';
                $investExit = new Model_InvestmentExit;
                $investExit->addWhere($findField, $model->mId);
                $investExit->setCols($findField);
                $investExit->setCols('currency');
                $investExit->addComputedCol('SUM(exit_num)', 'total_num');
                $investExit->groupBy($findField);
                $investExit->groupBy('currency');
                $resStr = '';
                foreach ($investExit->find() as $res) {
                    $data = $res->getData();
                    $resStr .= number_format($data['total_num']). "\n";
                }
                return "<a href='/admin/investmentExit?__filter=".urlencode("project_id=".$model->mId)."'>$resStr</a>";
            }],
            ['label'=>'借款/CB','field'=>function($model){
                return $model->mLoan;
            }],
            ['label'=>'借款到期日','field'=>function($model){
                if ($model->mLoanExpiration > 0)
                    return date("Ymd", $model->mLoanExpiration);
            }],
            ['label'=>'借款备注','field'=>function($model){
                return $model->mLoanMemo;
            }],
            ['label'=>'投时公司总股数','field'=>function($model){
                return number_format($model->mStocknumAll);
            }],
            ['label'=>'投时持有本轮股数','field'=>function($model){
                return number_format($model->mStocknumGet);
            }],
            /*['label'=>'源码持有最新股数','field'=>function($model){
                return number_format($model->mStocknumNew);
            }],*/
            ['label'=>'投时持股比例','field'=>function($model){
                if ($model->mStocknumAll)
                    return sprintf("%.2f%%", $model->mStocknumGet/$model->mStocknumAll * 100);
            }],
            /*['label'=>'最新持股比例','field'=>function($model){
                $company = $this->_getResource($model->mCompanyId, 'Company', new Model_Company);
                if ($model->mStocknumNew && $company->mTotalStock) {
                    return sprintf("%.2f%%", $model->mStocknumNew/$company->mTotalStock * 100);
                }
            }],*/
            ['label'=>'团队持股比例','field'=>function($model){
                return $model->mShareholdingMember;
            }],
            ['label'=>'ESOP比例','field'=>function($model){
                return $model->mShareholdingEsop;
            }],
            ['label'=>'投资限制','field'=>function($model){
                return $model->mTermLimit;
            }],
            ['label'=>'源码转竞品限制','field'=>function($model){
                return $model->mTermStockTransferLimit;
            }],
            ['label'=>'源码转让其他限制','field'=>function($model){
                return $model->mTermStockTransferLimitOther;
            }],
            ['label'=>'对投资人其他限制或责任','field'=>function($model){
                return $model->mTermLimitOther;
            }],
            ['label'=>'创始人转让限制','field'=>function($model){
                return $model->mTermFounderTransferLimit;
            }],
            ['label'=>'股东会veto','field'=>function($model){
                return $model->mTermHolderVeto;
            }],
            ['label'=>'董事会veto','field'=>function($model){
                return $model->mTermBoardVeto;
            }],
            ['label'=>'优先认购权','field'=>function($model){
                return $model->mTermPreemptive;
            }],
            ['label'=>'对创始人优先受让权','field'=>function($model){
                return $model->mTermPriAssignee;
            }],
            ['label'=>'对创始人共售权','field'=>function($model){
                return $model->mTermSellTogether;
            }],
            ['label'=>'对普通股优先权','field'=>function($model){
                return $model->mTermPriCommonStock;
            }],
            ['label'=>'回购金额标准','field'=>function($model){
                return $model->mTermBuybackStandard;
            }],
            ['label'=>'回购起算日','field'=>function($model){
                if ($model->mTermBuybackStart)
                    return date('Ymd', $model->mTermBuybackStart);
            }],
            ['label'=>'回购年限','field'=>function($model){
                return $model->mTermBuybackPeriod;
            }],
            ['label'=>'合格IPO年限','field'=>function($model){
                return $model->mTermIpoPeriod;
            }],
            ['label'=>'等待期年限','field'=>function($model){
                return $model->mTermWaitingPeriod;
            }],
            ['label'=>'反稀释方法','field'=>function($model){
                return $model->mTermAntiDilution;
            }],
            ['label'=>'拖售权','field'=>function($model){
                return $model->mTermDragAlongRight;
            }],
            ['label'=>'拖售权时间/说明/经谁同意','field'=>function($model){
                return $model->mTermDarIllustrate;
            }],
            ['label'=>'Warrant','field'=>function($model){
                return $model->mTermWarrant;
            }],
            ['label'=>'优先分红权','field'=>function($model){
                return $model->mTermDividendsPreference;
            }],
            ['label'=>'对赌/估值调整','field'=>function($model){
                return $model->mTermValuationAdjustment;
            }],
            ['label'=>'配偶同意函','field'=>function($model){
                return $model->mTermSpouseConsent;
            }],
            ['label'=>'Longstop date','field'=>function($model){
                return $model->mTermLongstopDate;
            }],
            ['label'=>'相对上轮重大变化','field'=>function($model){
                return $model->mTermImportantChanges;
            }],
            ['label'=>'不常见好条款摘选','field'=>function($model){
                return $model->mTermGoodItem;
            }],
            ['label'=>'Final Captalbe','field'=>function($model){
                return $model->mArchFinalCaptalbe;
            }],
            ['label'=>'Final Word','field'=>function($model){
                return $model->mArchFinalWord;
            }],
            ['label'=>'Closing PDF','field'=>function($model){
                return $model->mArchClosingPdf;
            }],
            ['label'=>'Closing原件','field'=>function($model){
                return $model->mArchClosingOriginal;
            }],
            ['label'=>'境外股票证书','field'=>function($model){
                return $model->mArchOverseasStockcert;
            }],
            ['label'=>'境内工商登记','field'=>function($model){
                return $model->mArchAicRegistration;
            }],
            ['label'=>'文件Filling保管人','field'=>function($model)use(&$companyCache){
                return $companyCache->mFillingKeeper;
            }],
            ['label'=>'有无未决事项','field'=>function($model){
                return $model->mPendingDetail;
            }],
            ['label'=>'工作备忘','field'=>function($model){
                return $model->mWorkMemo;
            }],
            ['label'=>'更新时间','field'=>function($model){
                return date("Ymd H:i:s", $model->mUpdateTime);
            }],
        );
    }

    private function _initSingleActions() {
        $this->single_actions=[
            ['label'=>'复制','action'=>function($model){
                return '/admin/project?action=clone&id='.$model->mId;
            }],
            ['label'=>'审阅','action'=>function($model){
                return '/admin/systemLog/diff?resource=project&res_id='.$model->mId;
            }],
        ];

        //$this->single_actions_default = ['delete'=>false];
    }

    private function _initMultiActions() {
        $this->multi_actions=array(
            ['label'=>'回收站', 'required'=>false, 'action'=>'/admin/project/recovery'],
            ['label'=>'导出csv','required'=>false,'action'=>'/admin/project/exportToCsv?method=full&__filter='.urlencode($this->_GET("__filter"))],
        );
    }

    private function _initListFilter() {
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'交易ID','paramName'=>'id','fusion'=>false,'hidden'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'项目名称','paramName'=>'short|company_id','foreignTable'=>'Model_Company','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'公司名称','paramName'=>'name|company_id','foreignTable'=>'Model_Company','fusion'=>true]),
            new Page_Admin_ChoiceFilter(['name'=>'整理状态','paramName'=>'item_status','choices'=>Model_Project::getItemStatusChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>'轮次大类','paramName'=>'turn','choices'=>Model_Project::getTurnChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>'新老类型','paramName'=>'new_follow','choices'=>Model_Project::getNewFollowChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>'投退类型','paramName'=>'enter_exit_type','choices'=>Model_Project::getEnterExitTypeChoices()]),
        );
    }

    public function __construct(){
        parent::__construct();

        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        $this->model=new Model_Project();
        $this->model->orderBy('id', 'DESC');

        WinRequest::mergeModel(array(
            'controllerText' => '交易记录',
        ));
    }

    private function _initFullAction() {
        $this->_initForm();
        $this->_initListDisplay();
        $this->_initSingleActions();
        $this->_initMultiActions();
        $this->_initListFilter();

        $this->model->addWhere('status', 'valid');
        WinRequest::mergeModel(array(
            'tableWrap' => '10240px',
        ));

        $this->multi_actions[] = ['label'=>'常用字段','required'=>false,'action'=>trim('/admin/project?'.$_SERVER['QUERY_STRING'],'?')];
    }

    public function fullAction() {
        $this->_initFullAction();
        return parent::indexAction();
    }

    private function _initIndexAction() {
        $this->_initForm();
        $this->_initListDisplay();
        $this->_initSingleActions();
        $this->_initMultiActions();
        $this->_initListFilter();

        $this->model->addWhere('status', 'valid');
        WinRequest::mergeModel(array(
            'tableWrap' => '2048px',
        ));

        $briefFields = [
            '交易ID',
            '项目编号',
            '整理状态',
            '目标企业',
            '所属行业',
            '轮次大类',
            '轮次详情',
            '新老类型',
            '投退类型',
            '新股老股',
            '决策日期',
            '交易状态',
            'Closing Date',
        ];
        $list_display = $this->list_display;
        $this->list_display = [];
        for($i = 0; $i < count($list_display); $i++) {
            if (in_array($list_display[$i]['label'], $briefFields)) {
                array_push($this->list_display, $list_display[$i]);
            }
        }

        $this->multi_actions[] = array('label'=>'全部字段','required'=>false,'action'=>trim('/admin/project/full?'.$_SERVER['QUERY_STRING'],'?'));
    }

    public function indexAction() {
        $this->_initIndexAction();
        return parent::indexAction();
    }

    public function recoveryAction() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $this->model->addWhere('id', $_REQUEST['id'])->update(['status'=>['"valid"', DBTable::NO_ESCAPE]]);
            return ['redirect: ' . dirname($_SERVER['SCRIPT_NAME'])];
        }
        $this->_initListDisplay();
        $this->model->addWhere('status', 'invalid');
        $this->hide_action_new = true;
        $this->single_actions_default = ['edit'=>false,'delete'=>false];
        $this->single_actions=[
            ['label'=>'恢复','action'=>function($model){
                return '/admin/project/recovery?id='.$model->mId;
            }],
        ];
        WinRequest::mergeModel(array(
            'tableWrap' => '10240px',
        ));
        $reqModel = WinRequest::getModel();
        $reqModel['controllerText'] = '交易记录 回收站';
        WinRequest::setModel($reqModel);
        return parent::indexAction();
    }

    /*
     * 重载_delete()方法
     */
    public function _delete() {
        $this->model->addWhere('id', $_REQUEST['id'])->update(['status'=>['"invalid"', DBTable::NO_ESCAPE]]);
    }

    /*
     * 重载ExportActions.initData方法
     */
    public function initData() {
        $this->_initFullAction();
    }
}


