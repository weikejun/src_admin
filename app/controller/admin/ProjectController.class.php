<?php
class ProjectController extends Page_Admin_Base {
    private $_objectCache = [];
    public function indexAction() {
	    list($view, $model) = parent::indexAction();
	    if (basename($view, '.html') == 'index') {
		    return array('admin/project/index.tpl', $model);
	    }
	    return [$view, $model];
    }
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Project();
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'company_id','label'=>'目标公司','type'=>"choosemodel",'model'=>'Company','default'=>null,'required'=>true,),
            array('name'=>'name','label'=>'项目名称','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'code','label'=>'项目编号','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'item_status','label'=>'整理状态','type'=>"choice",'choices'=>[['closing','已完成'],['ongoing','待完成'],['pending','其他']], 'default'=>'ongoing','required'=>true,),
            array('name'=>'turn','label'=>'轮次大类','type'=>"choice",'choices'=>['A','B','C'], 'default'=>'A','required'=>true,),
            array('name'=>'turn_sub','label'=>'轮次详情','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'investment_type','label'=>'投退类型','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'decision_date','label'=>'决策时间','type'=>"datetime",'default'=>null,'null'=>false,),
            array('name'=>'proj_status','label'=>'项目状态','type'=>"choice",'choices'=>[['Closing','Closing'],['Ongoing','Ongoing'],['Pending','Pending'],['Ceasing','Ceasing']], 'default'=>'Pending','required'=>false,),
            array('name'=>'close_date','label'=>'Close时间','type'=>"datetime",'default'=>null,'null'=>false,),
            array('name'=>'owner_pre','label'=>'原项目负责人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'owner_now','label'=>'现项目负责人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'law_firm','label'=>'律所及律师','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'legal_in','label'=>'legal接口人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'director_out','label'=>'境外董事','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'director_in','label'=>'境内董事','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'director_status','label'=>'境内董事工商状态','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'observer','label'=>'观察员','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'pre_money','label'=>'投前估值','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'post_money','label'=>'投后估值','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'stock_price','label'=>'每股价格','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'financing_amount','label'=>'融资总额','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'currency','label'=>'货币单位','type'=>"choice",'choices'=>[['RMB','RMB'],['USD','USD'],['HKD','HKD']], 'default'=>'USD','required'=>false,),
            array('name'=>'multi_currency','label'=>'是否多币种','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'investment_co','label'=>'源码投资主体','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'period','label'=>'期数/专项','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'our_amount','label'=>'源码投资金额','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'other_amount','label'=>'其他投资人及金额','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'stock_trans','label'=>'是否有老股转让','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'trans_detail','label'=>'老股转让说明','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'amount_memo','label'=>'金额备注','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'loan','label'=>'借款/CB','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'shareholding','label'=>'投资时持股比例','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'shareholding_new','label'=>'最新持股比例','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'shareholding_total','label'=>'各主体合计持股比例','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'shareholding_member','label'=>'团队持股比例','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'shareholding_esop','label'=>'ESOP比例','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'mirror','label'=>'镜像持股','type'=>"choice",'choices'=>[['N','N'],['mirror','mirror'],['象征性','象征性']], 'default'=>'N','required'=>true,),
            array('name'=>'entrustment','label'=>'基金代持','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'stocknum_all','label'=>'公司总股数','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'stocknum_turn','label'=>'持有本轮股数','type'=>"text", 'default'=>null,'required'=>false,),
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


