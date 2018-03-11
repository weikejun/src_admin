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
            array('name'=>'name','label'=>'项目名称','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'code','label'=>'项目编号','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'item_status','label'=>'整理状态','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'company_id','label'=>'被投企业','type'=>"choosemodel",'model'=>'Company','default'=>null,'required'=>true,),
            array('name'=>'turn','label'=>'轮次大类','type'=>"choice",'choices'=>['A','B','C'], 'default'=>'A','required'=>true,),
            array('name'=>'turn_sub','label'=>'轮次详情','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'decision_date','label'=>'决策时间','type'=>"datetime",'default'=>null,'null'=>false,),
            array('name'=>'proj_status','label'=>'项目状态','type'=>"choice",'choices'=>[['Closing','Closing'],['Ongoing','Ongoing'],['Pending','Pending'],['Ceasing','Ceasing']], 'default'=>'Pending','required'=>true,),
            array('name'=>'close_date','label'=>'Close时间','type'=>"datetime",'default'=>null,'null'=>false,),
            array('name'=>'owner_pre','label'=>'原项目负责人','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'owner_now','label'=>'现项目负责人','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'law_firm','label'=>'律所及律师','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'legal_in','label'=>'legal接口人','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'director_out','label'=>'境外董事','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'director_in','label'=>'境内董事','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'director_status','label'=>'境内董事工商状态','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'observer','label'=>'观察员','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'pre_money','label'=>'投前估值','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'post_money','label'=>'投后估值','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'stock_price','label'=>'每股价格','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'financing_amount','label'=>'融资总额','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'currency','label'=>'货币单位','type'=>"choice",'choices'=>[['RMB','RMB'],['USD','USD'],['HKD','HKD']], 'default'=>'USD','required'=>true,),
            array('name'=>'multi_currency','label'=>'是否多币种','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'investment_co','label'=>'源码投资主体','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'period','label'=>'期数/专项','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'our_amount','label'=>'源码投资金额','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'other_amount','label'=>'其他投资人及金额','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'stock_trans','label'=>'是否有老股转让','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'trans_detail','label'=>'老股转让说明','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'amount_memo','label'=>'金额备注','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'loan','label'=>'借款/CB','type'=>"text", 'default'=>null,'required'=>false,),
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


