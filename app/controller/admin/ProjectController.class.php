<?php
class ProjectController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportToCsvAction;
    private $_objectCache = [];
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        $this->model=new Model_Project();
        $this->model->orderBy('id', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText' => "投资记录",
            'tableWrap' => '10240px',
        ));
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'company_id','label'=>'目标公司','type'=>"choosemodel",'model'=>'Model_Company','default'=>null,'required'=>true,),
            array('name'=>'code','label'=>'项目编号','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'item_status','label'=>'整理状态','type'=>"choice",'choices'=>[['closing','已完成'],['ongoing','待完成'],['pending','其他']], 'default'=>'ongoing','required'=>true,),
            array('name'=>'turn','label'=>'轮次大类','type'=>"choice",'choices'=>Model_Project::getTurnChoices(), 'default'=>'A轮','required'=>true,),
            array('name'=>'turn_sub','label'=>'轮次详情','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'new_follow','label'=>'新老类型','type'=>"choice",'choices'=>Model_Project::getNewFollowChoices(), 'default'=>'new','required'=>true,),
            array('name'=>'enter_exit_type','label'=>'投退类型','type'=>"choice",'choices'=>Model_Project::getEnterExitTypeChoices(), 'default'=>'领投','required'=>true,),
            array('name'=>'new_old_stock','label'=>'新股老股','type'=>"choice",'choices'=>[['新股','新股'],['老股','老股'],['其他','其他']], 'default'=>'新股','required'=>true,),
            array('name'=>'decision_date','label'=>'决策日期','type'=>"date",'default'=>null,'required'=>false),
            array('name'=>'proj_status','label'=>'交易状态','type'=>"choice",'choices'=>[['进展中','进展中'],['已交割','已交割'],['暂停','暂停'],['终止不做','终止不做'],['其他','其他']], 'default'=>'进展中','required'=>false,),
            array('name'=>'close_date','label'=>'Close日期','type'=>"date",'default'=>null,'null'=>false,),
            array('name'=>'law_firm','label'=>'负责律所','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'observer','label'=>'观察员','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'info_right','label'=>'信息权','type'=>"choice",'choices'=>[['有','有'],['无','无']], 'default'=>'有','required'=>true,),            array('name'=>'create_time','label'=>'创建时间','type'=>"hidden","readonly"=>'true','default'=>time(),'null'=>false,),
            array('name'=>'info_right_threshold','label'=>'信息权门槛','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'currency','label'=>'计价货币','type'=>"choice",'choices'=>[['USD','USD'],['RMB','RMB'],['HKD','HKD']], 'default'=>'USD','required'=>false,),
            array('name'=>'pre_money','label'=>'公司投前估值','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'financing_amount','label'=>'本轮融资总额','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'value_change','label'=>'公司估值涨幅','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'entity_id','label'=>'源码投退主体','type'=>"choosemodel",'model'=>'Model_Entity','default'=>null,'required'=>false,),
            array('name'=>'rmb_usd','label'=>'RMB/USD','type'=>"choice",'choices'=>[['RMB','RMB'],['RMB-ODI','RMB-ODI'],['USD','USD'],['USD-JV','USD-JV'],['USD-VIE','USD-VIE'],['USD-拆VIE','USD-拆VIE'],['其他','其他']], 'default'=>'RMB','required'=>false,),
            array('name'=>'period','label'=>'期数/专项','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'mirror','label'=>'镜像持股','type'=>"choice",'choices'=>[['不适用','不适用'],['有','有'],['无','无']], 'default'=>'不适用','required'=>true,),
            array('name'=>'entrustment','label'=>'基金代持及主体','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'our_amount','label'=>'源码投资金额','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'other_amount','label'=>'其他投资人及金额','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'amount_memo','label'=>'金额备注','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'loan','label'=>'借款/CB','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'loan_expiration','label'=>'借款到期日','type'=>"date", 'default'=>null,'required'=>false,),
            array('name'=>'loan_memo','label'=>'借款备注','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'stocknum_all','label'=>'投时公司总股数','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'stocknum_get','label'=>'投时持有本轮股数','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'stocknum_new','label'=>'当前持有本轮股数','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'shareholding_member','label'=>'团队持股比例(不含ESOP)','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'shareholding_esop','label'=>'ESOP比例','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'term_limit','label'=>'投资限制','type'=>"text", 'default'=>'无','required'=>false,),
            array('name'=>'term_stock_transfer_limit','label'=>'股权转让竞品限制','type'=>"text", 'default'=>'无','required'=>false,),
            array('name'=>'term_stock_transfer_limit_other','label'=>'股权转让其他限制','type'=>"text", 'default'=>'无','required'=>false,),
            array('name'=>'term_limit_other','label'=>'对投资人其他限制或责任','type'=>"text", 'default'=>'无','required'=>false,),
            array('name'=>'term_founder_transfer_limit','label'=>'创始人转让限制','type'=>"text", 'default'=>'无','required'=>false,),
            array('name'=>'term_holder_veto','label'=>'股东会veto','type'=>"choice",'choices'=>[['有独立veto','有独立veto'],['有不独立veto','有不独立veto'],['没有','没有'],['个别关键事项否决权','个别关键事项否决权']], 'default'=>'有独立veto','required'=>true,),
            array('name'=>'term_board_veto','label'=>'董事会veto','type'=>"choice",'choices'=>[['有独立veto','有独立veto'],['有不独立veto','有不独立veto'],['没有','没有'],['个别关键事项否决权','个别关键事项否决权']], 'default'=>'有独立veto','required'=>true,),
            array('name'=>'term_preemptive','label'=>'优先认购权','type'=>"choice",'choices'=>[['有','有'],['没有','没有']], 'default'=>'有','required'=>true,),
            array('name'=>'term_pri_assignee','label'=>'对创始人优先受让权','type'=>"choice",'choices'=>[['有','有'],['没有','没有']], 'default'=>'有','required'=>true,),
            array('name'=>'term_sell_together','label'=>'对创始人共售权','type'=>"choice",'choices'=>[['有','有'],['没有','没有']], 'default'=>'有','required'=>true,),
            array('name'=>'term_pri_common_stock','label'=>'对普通股优先权','type'=>"choice",'choices'=>[['有','有'],['没有','没有']], 'default'=>'有','required'=>true,),
            array('name'=>'term_buyback_standard','label'=>'回购金额标准','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'term_buyback_start','label'=>'回购起算日','type'=>"date", 'default'=>null,'required'=>false,),
            array('name'=>'term_buyback_period','label'=>'回购年限','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'term_buyback_date','label'=>'本轮可回购时间','type'=>"date", 'default'=>null,'required'=>false,),
            array('name'=>'term_ipo_period','label'=>'合格IPO年限','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'term_waiting_period','label'=>'等待期年限','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'term_anti_dilution','label'=>'反稀释方法','type'=>"choice",'choices'=>[['完全棘轮','完全棘轮'],['狭义加权平均','狭义加权平均'],['广义加权平均','广义加权平均'],['无','无']], 'default'=>null,'required'=>false),
            array('name'=>'term_liquidation_preference','label'=>'优先清算权方法','type'=>"choice",'choices'=>[['参与分配','参与分配'],['不参与分配','不参与分配'],['无','无']], 'default'=>null,'required'=>false),
            array('name'=>'term_drag_along_right','label'=>'拖售权','type'=>"choice",'choices'=>[['有','有'],['没有','没有']], 'default'=>null,'required'=>false),
            array('name'=>'term_dar_illustrate','label'=>'拖售权说明','type'=>"text",'default'=>null,'required'=>false),
            array('name'=>'term_warrant','label'=>'warrant','type'=>"choice",'choices'=>[['有','有'],['没有','没有']], 'default'=>null,'required'=>false),
            array('name'=>'term_dividends_preference','label'=>'优先分红权','type'=>"choice",'choices'=>[['有','有'],['没有','没有']], 'default'=>null,'required'=>false),
            array('name'=>'term_valuation_adjustment','label'=>'对赌/估值调整','type'=>"text",'default'=>null,'required'=>false),
            array('name'=>'term_spouse_consent','label'=>'配偶同意函','type'=>"choice",'choices'=>[['有','有'],['没有','没有']], 'default'=>null,'required'=>false),
            array('name'=>'term_longstop_date','label'=>'longstop date','type'=>"text",'default'=>null,'required'=>false),
            array('name'=>'term_important_changes','label'=>'相对上轮重大变化','type'=>"text",'default'=>null,'required'=>false),
            array('name'=>'term_good_item','label'=>'不常见好条款摘选','type'=>"text",'default'=>null,'required'=>false),
            array('name'=>'arch_final_captalbe','label'=>'Final Captalbe','type'=>"choice",'choices'=>[['已存档','已存档'],['未存档','未存档']], 'default'=>null,'required'=>false),
            array('name'=>'arch_final_word','label'=>'Final Word','type'=>"choice",'choices'=>[['已存档','已存档'],['未存档','未存档']], 'default'=>null,'required'=>false),
            array('name'=>'arch_closing_pdf','label'=>'Closing PDF','type'=>"choice",'choices'=>[['已存档','已存档'],['未存档','未存档']], 'default'=>null,'required'=>false),
            array('name'=>'arch_closing_original','label'=>'Closing原件','type'=>"choice",'choices'=>[['已存档','已存档'],['未存档','未存档']], 'default'=>null,'required'=>false),
            array('name'=>'arch_overseas_stockcert','label'=>'境外股票证书','type'=>"choice",'choices'=>[['已存档','已存档'],['未存档','未存档']], 'default'=>null,'required'=>false),
            array('name'=>'arch_aic_registration','label'=>'境内工商登记','type'=>"choice",'choices'=>[['已办理','已办理'],['未办理','未办理']], 'default'=>null,'required'=>false),
            array('name'=>'pending_detail','label'=>'未决事项说明','type'=>"text",'default'=>'无','required'=>false),
            array('name'=>'work_memo','label'=>'工作备忘','type'=>"textarea",'default'=>'','required'=>false),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime","readonly"=>'true','default'=>time(),'auto_update'=>true),
        ));
        $companyCache = new Model_Company;
        $this->list_display=array(
            ['label'=>'交易ID','field'=>function($model)use(&$companyCache){
                $companyCache->mId = $model->mCompanyId;
                $companyCache->select();
                return $model->mId;
            }],
            ['label'=>'项目编号','field'=>function($model){
                return $model->mCode;
            }],
            ['label'=>'项目名称','field'=>function($model)use(&$companyCache){
                return $companyCache->mShort;
            }],
            ['label'=>'整理状态','field'=>function($model){
                return $model->mItemStatus;
            }],
            ['label'=>'公司名称','field'=>function($model)use(&$companyCache){
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
                return date("Ymd", $model->mDecisionDate);
            }],
            ['label'=>'交易状态','field'=>function($model){
                return $model->mProjStatus;
            }],
            ['label'=>'Closing Date','field'=>function($model){
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
                return number_format($model->mPreMoney) . " " . $model->mCurrency;
            }],
            ['label'=>'本轮融资总额','field'=>function($model){
                return number_format($model->mFinancingAmount) . " " . $model->mCurrency;
            }],
            ['label'=>'公司投后估值','field'=>function($model){
                return number_format($model->mPreMoney + $model->mFinancingAmount) . " " . $model->mCurrency;
            }],
            ['label'=>'公司估值涨幅','field'=>function($model){
                return $model->mValueChange;
            }],
            ['label'=>'公司每股单价','field'=>function($model){
                $company = $this->_getResource($model->mCompanyId, 'Company', new Model_Company);
                if (!$company) {
                    return '（公司出错）';
                }
                return number_format(($model->mPreMoney + $model->mFinancingAmount) / $company->mTotalStock, 2) . " " . $model->mCurrency;
            }],
            ['label'=>'源码投退主体','field'=>function($model){
                $entity = $this->_getResource($model->mEntityId, 'Entity', new Model_Entity);
                return ($entity ? $entity->mName : '（公司出错）' );
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
                return "<a href='/admin/payment?__filter=".urlencode("project_id=".$model->mId)."'>$resStr</a>";
            }],
            ['label'=>'源码每股单价','field'=>function($model){
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
                return "<a href='/admin/investmentExit?__filter=".urlencode("project_id=".$model->mId)."'>$resStr</a>";
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
            ['label'=>'源码持有最新股数','field'=>function($model){
                return number_format($model->mStocknumNew);
            }],
            ['label'=>'投时持股比例','field'=>function($model){
                return sprintf("%.2f%%", $model->mStocknumGet/$model->mStocknumAll * 100);
            }],
            ['label'=>'最新持股比例','field'=>function($model){
                $company = $this->_getResource($model->mCompanyId, 'Company', new Model_Company);
                if (!$company) {
                    return '（公司出错）';
                }
                return $model->mStocknumNew/$company->mTotalStock;
            }],
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
            ['label'=>'文件Filling保管人','field'=>function($model){
                // TODO: return $model->mArchDocFillingKeeper;
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

        $this->single_actions=[
            ['label'=>'复制','action'=>function($model){
                return '/admin/project?action=clone&id='.$model->mId;
            }],
        ];

        $this->multi_actions=array(
            array('label'=>'导出csv','required'=>false,'action'=>'/admin/project/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        );

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'项目名称','paramName'=>'short|company_id','foreignTable'=>'Model_Company','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'公司名称','paramName'=>'name|company_id','foreignTable'=>'Model_Company','fusion'=>true]),
            new Page_Admin_ChoiceFilter(['name'=>'轮次大类','paramName'=>'turn','choices'=>Model_Project::getTurnChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>'新老类型','paramName'=>'new_follow','choices'=>Model_Project::getNewFollowChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>'投退类型','paramName'=>'enter_exit_type','choices'=>Model_Project::getEnterExitTypeChoices()]),
        );
    }
}


