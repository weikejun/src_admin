<?php
class ProjectController extends Page_Admin_Base {
    private $_objectCache = [];
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Project();
        $this->model->orderBy('id', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText' => "投资记录",
            'tableWrap' => true,
        ));
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'company_id','label'=>'目标公司','type'=>"choosemodel",'model'=>'Company','default'=>null,'required'=>true,),
            array('name'=>'item_status','label'=>'整理状态','type'=>"choice",'choices'=>[['closing','已完成'],['ongoing','待完成'],['pending','其他']], 'default'=>'ongoing','required'=>true,),
            array('name'=>'turn','label'=>'轮次大类','type'=>"choice",'choices'=>[['A轮','A轮'],['B轮','B轮'],['C轮','C轮'],['D轮','D轮'],['E轮','E轮'],['F轮','F轮'],['F轮后','F轮后'],['不适用','不适用']], 'default'=>'A轮','required'=>true,),
            array('name'=>'turn_sub','label'=>'轮次详情','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'new_follow','label'=>'新老类型','type'=>"choice",'choices'=>[['new','new'],['follow on','follow on']], 'default'=>'new','required'=>true,),
            array('name'=>'enter_exit_type','label'=>'投退类型','type'=>"choice",'choices'=>[['领投','领投'],['跟投','跟投'],['不跟投','不跟投'],['部分退出','部分退出'],['全部退出','全部退出'],['清算退出','清算退出'],['重组','重组'],['上市','上市'],['其他','其他']], 'default'=>'领投','required'=>true,),
            array('name'=>'new_old_stock','label'=>'新股老股','type'=>"choice",'choices'=>[['新股','新股'],['老股','老股'],['其他','其他']], 'default'=>'新股','required'=>true,),
            array('name'=>'decision_date','label'=>'决策日期','type'=>"date",'default'=>null,'null'=>false,),
            array('name'=>'proj_status','label'=>'项目状态','type'=>"choice",'choices'=>[['进展中','进展中'],['已交割','已交割'],['暂停','暂停'],['终止不做','终止不做'],['其他','其他']], 'default'=>'进展中','required'=>false,),
            array('name'=>'close_date','label'=>'Close日期','type'=>"date",'default'=>null,'null'=>false,),
            array('name'=>'law_firm','label'=>'负责律所','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'currency','label'=>'计价货币','type'=>"choice",'choices'=>[['USD','USD'],['RMB','RMB'],['HKD','HKD']], 'default'=>'USD','required'=>false,),
            array('name'=>'pre_money','label'=>'公司投前估值','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'financing_amount','label'=>'本轮融资总额','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'post_money','label'=>'公司投后估值','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'invest_entity','label'=>'源码投退主体','type'=>"choosemodel",'model'=>'Entity','default'=>null,'required'=>true,),
            array('name'=>'rmb_usd','label'=>'RMB/USD','type'=>"choice",'choices'=>[['RMB','RMB'],['RMB-ODI','RMB-ODI'],['USD','USD'],['USD-JV','USD-JV'],['USD-VIE','USD-VIE'],['USD-拆VIE','USD-拆VIE'],['其他','其他']], 'default'=>'RMB','required'=>false,),
            array('name'=>'period','label'=>'期数/专项','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'mirror','label'=>'镜像持股','type'=>"choice",'choices'=>[['不适用','不适用'],['有','有'],['无','无']], 'default'=>'不适用','required'=>true,),
            array('name'=>'entrustment','label'=>'基金代持及主体','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'our_amount','label'=>'源码投资金额','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'other_amount','label'=>'其他投资人及金额','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'amount_memo','label'=>'金额备注','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'loan','label'=>'借款/CB','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'loan_expiration','label'=>'借款到期日','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'loan_memo','label'=>'借款备注','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'stocknum_all','label'=>'投时公司总股数','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'stocknum_own','label'=>'最新持有本轮股数','type'=>"text", 'default'=>null,'required'=>false,),
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
            array('name'=>'term_pri_comon_stock','label'=>'对普通股优先权','type'=>"choice",'choices'=>[['有','有'],['没有','没有']], 'default'=>'有','required'=>true,),
            array('name'=>'stock_trans','label'=>'是否有老股转让','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'trans_detail','label'=>'老股转让说明','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'shareholding_total','label'=>'各主体合计持股比例','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'stocknum_total','label'=>'持有合计股数','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'hold_value','label'=>'持有股数的价值','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'return_rate','label'=>'投资回报倍数','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'return_irr','label'=>'投资回报IRR','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'final_captable','label'=>'Final Captable','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'final_word','label'=>'Final Word','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'closing_pdf','label'=>'Closing PDF','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'closing_file','label'=>'Closing原件','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'stock_cert','label'=>'境外股票证书','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'business_reg','label'=>'境内工商','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'filling_owner','label'=>'文件filling保管人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'exit_amount','label'=>'退出金额','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'exit_way','label'=>'退出方式','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'exit_ratio','label'=>'退出比例','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'exit_return','label'=>'退出回报倍数','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"hidden","readonly"=>'true','default'=>time(),'null'=>false,),
            array('name'=>'admin_id','label'=>'创建人ID','type'=>"hidden",'readonly'=>'true','default'=>Admin::getCurrentAdmin()->mId,'required'=>true,),
        ));
        $this->list_display=array(
            ['label'=>'项目ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'项目名称','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'项目编号','field'=>function($model){
                return $model->mCode;
            }],
            ['label'=>'整理状态','field'=>function($model){
                return $model->mItemStatus;
            }],
            ['label'=>'被投企业','field'=>function($model){
                $company = new Company();
		$ret = $company->addWhere("id", $model->mCompanyId)->select();
		return ($ret ? $company->mName : '（名称出错）' );
            }],
            ['label'=>'所属行业','field'=>function($model){
                $company = new Company();
		$ret = $company->addWhere("id", $model->mCompanyId)->select();
		return ($ret ? $company->mName : '（名称出错）' );
            }],
            ['label'=>'轮次大类','field'=>function($model){
                return $model->mTurn;
            }],
            ['label'=>'轮次详情','field'=>function($model){
                return $model->mTurnSub;
            }],
            ['label'=>'投退类型','field'=>function($model){
                return $model->mInvestmentType;
            }],
            ['label'=>'决策年份','field'=>function($model){
                return date("Y", $model->mDecisionDate);
            }],
            ['label'=>'决策月份','field'=>function($model){
                return date("m", $model->mDecisionDate);
            }],
            ['label'=>'状态','field'=>function($model){
                return $model->mProjStatus;
            }],
            ['label'=>'Closing Date','field'=>function($model){
                return date("Ymd", $model->mCloseDate);
            }],
            ['label'=>'原项目负责人','field'=>function($model){
                return $model->mOwnerPre;
            }],
            ['label'=>'现项目负责人','field'=>function($model){
                return $model->mOwnerNow;
            }],
            ['label'=>'律所及律师','field'=>function($model){
                return $model->mLawFirm;
            }],
            ['label'=>'Legal接口人','field'=>function($model){
                return $model->mLegalIn;
            }],
            ['label'=>'工作记录','field'=>function($model){
		return '<a href="/admin/projectMemo?__filter='.urlencode('project_id='.$model->mId).'">查看</a>&nbsp;<a href="/admin/projectMemo?action=read&project_id='.$model->mId.'">添加</a>';
            }],
            ['label'=>'创建人','field'=>function($model){
		$admin = new Admin();
		$ret = $admin->addWhere("id", $model->mAdminId)->select();
		return ($ret ? $admin->mName : '(id='.$model->mAdminId.')' );
            }],
        );

        $this->single_actions=[
            ['label'=>'工作记录','action'=>function($model){
                return '/admin/projectMemo?__filter='.urlencode('project_id='.$model->mId);
            }],
        ];

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'项目名称','paramName'=>'name','fusion'=>true]),
        );
    }
}


