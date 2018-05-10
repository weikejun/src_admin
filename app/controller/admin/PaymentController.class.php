<?php

class PaymentController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Payment();
        $this->model->orderBy("pay_time","desc");
        WinRequest::mergeModel(array(
            'controllerText'=>"付款记录",
        ));

        $this->form=new Form(array(
            array('name'=>'project_id','label'=>'项目ID','type'=>"choosemodel",'model'=>'Project','default'=>null,'required'=>true,),
            array('name'=>'amount','label'=>'金额','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'currency','label'=>'货币','type'=>"choice",'choices'=>[['RMB','RMB'],['USD','USD'],['HKD','HKD']], 'default'=>'USD','required'=>false,),
            array('name'=>'operator','label'=>'操作人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'pay_time','label'=>'付款时间','type'=>"datetime",'default'=>null,'null'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"hidden","readonly"=>'true','default'=>time(),'null'=>false,),
            array('name'=>'admin_id','label'=>'创建人ID','type'=>"hidden",'readonly'=>'true','default'=>Admin::getCurrentAdmin()->mId,'required'=>true,),
        ));
        $this->list_display=[
            ['label'=>'项目编号','field'=>function($model){
		$ret = self::_getResource($model->mProjectId, 'project', new Project());
		return ($ret ? $ret->mCode : '(id='.$model->mProjectId.')' );
            }],
            ['label'=>'项目名称','field'=>function($model){
		$ret = self::_getResource($model->mProjectId, 'project', new Project());
		return ($ret ? $ret->mName : '(id='.$model->mProjectId.')' );
            }],
            ['label'=>'金额','field'=>function($model){
                return $model->mAmount;
            }],
            ['label'=>'货币','field'=>function($model){
                return $model->mCurrency;
            }],
            ['label'=>'操作人','field'=>function($model){
                return $model->mOperator;
            }],
            ['label'=>'付款时间','field'=>function($model) {
                return date('Y-m-d H:i:s', $model->mPayTime);
            }],
            ['label'=>'创建人','field'=>function($model){
		$ret = self::_getResource($model->mAdminId, 'admin', new Admin());
		return ($ret ? $ret->mName : '(id='.$model->mAdminId.')' );
            }],
            ['label'=>'创建时间','field'=>function($model) {
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
        ];

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'项目ID','paramName'=>'project_id','fusion'=>false, 'in'=>true]),
        );

    }
}

