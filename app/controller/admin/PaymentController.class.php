<?php

class PaymentController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Payment();
        $this->model->orderBy("id","desc");
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            //array('name'=>'id','label'=>'流水号','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'trade_no','label'=>'支付单号','type'=>"text",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'status','label'=>'支付状态',"choices"=>Payment::getAllStatus(), 'type'=>"choice",'default'=>'unchecked','null'=>false,),
            array('name'=>'type','label'=>'支付类型',"choices"=>Payment::getAllType(), 'type'=>"choice",'readonly'=>true,'default'=>'unchecked','null'=>false,),
            array('name'=>'amount','label'=>'金额','type'=>"text",'readonly'=>true,'default'=>null,'null'=>false,),
            array('name'=>'source','label'=>'支付工具','type'=>"text",'readonly'=>true,'default'=>null,'null'=>false,),
            array('name'=>'order_id','label'=>'订单ID','type'=>"text",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'user_id','label'=>'用户ID','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'refund_amount','label'=>'退款金额','type'=>"text",'readonly'=>true,'default'=>null,'null'=>false,),
            array('name'=>'refund_memo','label'=>'退款说明','type'=>"textarea",'readonly'=>true,'default'=>null,'null'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>null,'null'=>false,),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime",'readonly'=>true,'auto_update'=>true,'default'=>null,'null'=>false,),
            array('name'=>'order_type','label'=>'订单类型',"choices"=>Payment::getOrderType(),'type'=>"choice",'default'=>null,'null'=>false ),
        ));
        $this->list_display=[
            ['label'=>'流水号','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'支付单号','field'=>function($model){
                return $model->mTradeNo;
            }],
            ['label'=>'外部订单号','field'=>function($model){
                return $model->mPlatformTradeNo;
            }],
            ['label'=>'支付工具','field'=>function($model){
                foreach(Payment::getAllSource() as $source){
                    if($model->mSource==$source[0]){
                        return $source[1];
                    }
                }
            }],
            ['label'=>'支付账户','field'=>function($model){
                return $model->mPayAccount;
            }],
            ['label'=>'金额','field'=>function($model){
                return $model->mAmount;
            }],
            ['label'=>'退款','field'=>function($model){
                return $model->mRefundAmount;
            }],
            ['label'=>'支付类型','field'=>function($model){
                foreach(Payment::getAllType() as $types){
                    if($model->mType==$types[0]){
                        return $types[1];
                    }
                }
            }],
            ['label'=>'支付状态','field'=>function($model){
                foreach(Payment::getAllStatus() as $status){
                    if($model->mStatus==$status[0]){
                        return $status[1];
                    }
                }
            }],
            ['label'=>'创建时间','field'=>function($model) {
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
            ['label'=>'更新时间','field'=>function($model) {
                return date('Y-m-d H:i:s', $model->mUpdateTime);
            }],
            ['label'=>'订单ID','field'=>function($model){
                if($model->mOrderType == Payment::ORDER_TYPE_ORDER){
                    return '<a href="/admin/payment?__filter='.urlencode('order_id='.$model->mOrderId).'">'.$model->mOrderId.'</a>';
                }else if($model->mOrderType == Payment::ORDER_TYPE_PAY_ORDER){
                    $orderList = (new Order())->getListByPayOrderId($model->mOrderId);
                    $str = array();
                    foreach($orderList as $order){
                        $str []=  '<a href="/admin/order?____filter="'.$order['id'].'">'.$order['id'].'</a>';
                    }
                    return implode(',',$str);
                }
            }],
            ['label'=>'用户ID','field'=>function($model){
                return '<a href="/admin/payment?__filter='.urlencode('user_id='.$model->mUserId).'">'.$model->mUserId.'</a>';
            }],
            ['label'=>'订单类型','field'=>function($model){
                foreach(Payment::getOrderType() as $orderType){
                    if($model->mOrderType==$orderType[0]){
                        return $orderType[1];
                    }
                }
            }]
        ];

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'订单ID','paramName'=>'order_id','fusion'=>false, 'in'=>true]),
            new Page_Admin_TextFilter(['name'=>'用户ID','paramName'=>'user_id','fusion'=>false, 'in'=>true]),
            new Page_Admin_TextFilter(['name'=>'支付单号','paramName'=>'trade_no','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>'创建时间','paramName'=>'create_time']),
            new Page_Admin_ChoiceFilter(['name'=>'支付类型','paramName'=>'type','choices'=>Payment::getAllType()]),
            new Page_Admin_ChoiceFilter(['name'=>'支付状态','paramName'=>'status','choices'=>Payment::getAllStatus()]),
            new Page_Admin_ChoiceFilter(['name'=>'支付状态','paramName'=>'source','choices'=>Payment::getAllSource()]),
        );

        $this->single_actions=[
            ['label'=>'订单','action'=>function($model){
                return '/admin/order?__filter='.urlencode('id='.$model->mOrderId);
            }],
            /*['label'=>'退款','action'=>function($model){
                return '/admin/payment/refund?ids='.$model->mId;
            },'enable'=>function($model) {
                return $model->mStatus == 'payed' && ($model->mSource == 'zfb' || $model->mSource == 'zfb_client') && $model->mCreateTime >= strtotime('2014-10-31');
            }],*/
        ];
        
        $this->multi_actions=array(
            //array('label'=>'批量退款','action'=>'/admin/payment/refund?ids=__ids__'),
        );

        //$this->search_fields=array('name');
    }

    public function refundAction() {
        $ids = $this->_GET('ids', 0);
        $ids = explode(',', $ids);
        if(empty($ids)) {
            echo '支付单选择不正确';
            exit;
        }
        $finder = new Payment;
        $pays = $finder->addWhere('id', $ids, 'IN')->find();
        if(empty($pays)) {
            echo '支付单选择不正确';
            exit;
        }
        $refundDate = date("Y-m-d H:i:s");
        $batchNo = date("YmdHis").$timeStruct['usec'];
        $batchNum = 0;
        $detailData = [];
        foreach($pays as $payment) {
            if(empty($payment->mPlatformTradeNo)) {
                continue;
            }
            $batchNum++;
            $detailData[] = implode('^', [$payment->mPlatformTradeNo, $payment->mAmount, 'customer request']);
        }
        $detailData = implode('#', $detailData);
        $ret = Alipay::refund($batchNo, $batchNum, $refundDate, $detailData);
        if(empty($ret)) {
            echo '支付宝退款失败，请联系技术人员';
            exit;
        }
        echo $ret;
        exit;
    }

}
