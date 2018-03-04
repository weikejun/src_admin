<?php
class OrderLogController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new OrderLog();
        $this->model->addWhere("order_type",0);
        $this->model->orderBy("id","desc");
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'order_id','label'=>'订单ID','type'=>'text','readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'log','label'=>'备注','type'=>'text','readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'create_time','label'=>'操作时间','readonly'=>true,'type'=>"datetime",'default'=>null,'required'=>false,),
            array('name'=>'operator','label'=>'操作者身份','readonly'=>true,'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'operator_id','label'=>'操作者ID','readonly'=>true,'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'op_type','label'=>'订单状态','readonly'=>true,'type'=>"text",'default'=>null,'required'=>false,),
        ));

        $this->list_display=array('id',
            ['label'=>'订单ID','field'=>function($model){
                return '<a href="/admin/orderLog?__filter='.urlencode('order_id='.$model->mOrderId).'">'.$model->mOrderId.'</a>';
            }],
            ['label'=>'订单状态','field'=>function($model){
                return OrderLog::genStatusInfo($model->mOpType);
            }],
            ['label'=>'操作者身份','field'=>function($model){
                return orderLog::getOperatorDesc($model->mOperator);
            }],
            ['label'=>'操作者ID','field'=>function($model){
                return $model->mOperatorId;
            }],
            ['label'=>'操作时间','field'=>function($log){
                return date("Y-m-d H:i:s", $log->mCreateTime);
            }],
        );
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'订单ID','paramName'=>'order_id','fusion'=>false]),
            new Page_Admin_TimeRangeFilter(['name'=>'操作时间','paramName'=>'create_time','fusion'=>false]),
            new Page_Admin_ChoiceFilter(['name'=>'操作者','paramName'=>'operator','choices'=>OrderLog::getOperators()]),
            new Page_Admin_ChoiceFilter(['name'=>'订单状态','paramName'=>'op_type','choices'=>OrderLog::getOrderStatus()]),
        );
    }

}




