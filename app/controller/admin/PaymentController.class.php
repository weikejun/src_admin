<?php

class PaymentController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_Payment();
        $this->model->orderBy("pay_time","desc");
        WinRequest::mergeModel(array(
            'controllerText'=>"付款记录",
        ));

        function setCompanyId($model) {
            $project = new Model_Project;
            $project->mId = $model->mProjectId;
            $project->select();
            if($project->mCompanyId) {
                $model->addWhere('id', $model->mId)->update(['company_id'=>[$project->mCompanyId, DBTable::NO_ESCAPE]]);
            }
        };

        $this->model->on('after_update', function($model) {
            setCompanyId($model);
        });
        $this->model->on('after_insert', function($model) {
            setCompanyId($model);
        });

        $this->form=new Form(array(
            array('name'=>'project_id','label'=>'项目ID','type'=>"choosemodel",'model'=>'Model_Project','default'=>$_GET['project_id'],'required'=>true,'show'=>'id'),
            array('name'=>'amount','label'=>'金额','type'=>"text", 'default'=>null,'required'=>true,),
            array('name'=>'company_id','label'=>'公司ID','type'=>"hidden", 'default'=>0,'required'=>false,),
            array('name'=>'currency','label'=>'货币','type'=>"choice",'choices'=>[['RMB','RMB'],['USD','USD'],['HKD','HKD']], 'default'=>'USD','required'=>true,),
            array('name'=>'operator','label'=>'操作人','type'=>"text", 'default'=>Model_Admin::getCurrentAdmin()->mName,'required'=>false,),
            array('name'=>'pay_time','label'=>'付款时间','type'=>"datetime",'default'=>null,),
            array('name'=>'memo','label'=>'备注','type'=>"textarea", 'default'=>null,'required'=>false,),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime",'default'=>time(),'readonly'=>true,'auto_update'=>true),
        ));
        $this->list_display=[
            ['label'=>'记录ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'交易ID','field'=>function($model){
                return $model->mProjectId;
            }],
            ['label'=>'项目名称','field'=>function($model){
                $ret = self::_getResource($model->mCompanyId, 'Company', new Model_Company());
                return ($ret ? $ret->mShort : '(id='.$model->mProjectId.')' );
            }],
            ['label'=>'金额','field'=>function($model){
                return number_format($model->mAmount) . ' ' . $model->mCurrency;
            }],
            ['label'=>'操作人','field'=>function($model){
                return $model->mOperator;
            }],
            ['label'=>'付款时间','field'=>function($model) {
                return date('Y-m-d H:i:s', $model->mPayTime);
            }],
            ['label'=>'备注','field'=>function($model){
                return $model->mMemo;
            }],
            ['label'=>'更新时间','field'=>function($model) {
                return date('Y-m-d H:i:s', $model->mUpdateTime);
            }],
        ];

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'交易ID','paramName'=>'project_id','fusion'=>false, 'in'=>true]),
        );

    }
}

