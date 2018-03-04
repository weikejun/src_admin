<?php
class UserRefundController extends Page_Admin_Base {
    public function bindModelEvent(){
        $this->model->on("after_update",function($model){
            $finder = new UserRefund;
            $model = $finder->addWhere('id', $model->mId)->select();
            if($model->mStatus == "2" && $model->mType == "1" && $model->mRange != 1) {

                $order = self::_getResource($model->mOrderId, 'order', new Order);
                $payments = (new Payment())->getPaymentsByOrderId($order->getData());
                $paymentIdList = array_map(function($payment){
                    return $payment['id'];
                },$payments);

                $payments = new Payment;
                $payments = $payments->addWhere('id',$paymentIdList,'in')->addWhere('status', 'payed')->find();
                $order = new Order;
                $order = $order->addWhere('id', $model->mOrderId)->select();
                foreach($payments as $payment) {
                    $payment->mRefundMemo .= date('Ymd H:i:s').": 手工退款\n";
                    $payment->mRefundAmount = $payment->mAmount;
                    $payment->save();
                    if($payment->mType == 'prepay' && $order->mStatus != 'full_refund') {
                        if($order->mPayType == 1){
                            $order->mStatus = 'full_refund';
                        }else if($order->mPayType == 0){
                            $order->mStatus = 'refund';
                            //定金退款的时候将payOrder的status更新为refund
                            if(!empty($order->mPayOrderId)){
                                PayOrder::getInstance()->updatePayOrderStatus($order->mPayOrderId,$order->mStatus ,'refund');
                            }
                        }
                    } elseif($payment->mType == 'pay') {
                        $order->mStatus = 'full_refund';
                        if($order->mPayType == 1){
                            if(!empty($order->mPayOrderId)){
                                PayOrder::getInstance()->updatePayOrderStatus($order->mPayOrderId,$order->mStatus ,'prepayed');
                            }
                        }else if($order->mPayType == 0){
                            if(!empty($order->mPayOrderId)){
                                PayOrder::getInstance()->updatePayOrderStatus($order->mPayOrderId,$order->mStatus ,'payed');
                            }
                        }
                    }
                }
                $order->mUpdateTime = time();
                $order->save();
                GlobalMethod::orderLog($order, '', 'admin', Admin::getCurrentAdmin()->mId);

                //状态同步到pay_order add by hongjie
                $payOrderInfo['mStatus'] = 'refund';
                $payOrderInfo['id'] = $order->mPayOrderId;
                $payOrder = PayOrder::updatePayOrder($payOrderInfo); 
                if ($payOrder) {
                    GlobalMethod::orderLog($payOrder, '', 'admin', Admin::getCurrentAdmin()->mId, 1);
                }
            }
        });
    }
    private function _setForm($type = null) {
        $fields = array(
            'order_id' => array('name'=>'order_id','label'=>'订单ID','type'=>"choosemodel",'model'=>'Order','readonly'=>'true','default'=>$this->fieldsDefault['order_id'],'required'=>false,),
            'status' => array('name'=>'status','label'=>'处理状态','type'=>"choice",'choices'=>UserRefund::getAllStatus(), 'default'=>0,'required'=>false,),
            'type' => array('name'=>'type','label'=>'退款方式','type'=>"choice",'choices'=>UserRefund::getAllType(), 'default'=>0,'required'=>false,),
            'reason' => array('name'=>'reason','label'=>'退款原因','type'=>"textarea",'default'=>null,'required'=>false,),
            'range' => array('name'=>'range','label'=>'退款范围','type'=>"choice",'choices'=>UserRefund::getAllRange(), 'default'=>0,'required'=>false,),
            'amount' => array('name'=>'amount','label'=>'退款金额（范围是“全部”时无效）','type'=>"text",'default'=>'0.00','readonly'=>false,'required'=>false,),
            'note' => array('name'=>'note','label'=>'备注','type'=>"textarea",'default'=>null,'required'=>false,),
            'account' => array('name'=>'account','label'=>'支付账户','type'=>"textarea",'default'=>null,'required'=>false,),
            'creator' => array('name'=>'creator','label'=>'创建人类型','type'=>"text",'default'=>'admin','readonly'=>true,'required'=>false,),
            'creator_id' => array('name'=>'creator_id','label'=>'创建人ID','type'=>"text",'default'=>Admin::getCurrentAdmin()->mId,'readonly'=>true,'required'=>false,),
            'create_time' => array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            'update_time' => array('name'=>'update_time','label'=>'操作时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,'auto_update'=>true,),
        );
        switch($type) {
        case 'create':
            $sChoices = UserRefund::getAllStatus();
            $fields['status']['choices'] = [$sChoices[0]];
            unset($fields['note']);
            unset($fields['type']);
            unset($fields['account']);
            break;
        case 'cancel':
            $index = 0;
            $selSt = 3;
            foreach(UserRefund::getAllStatus() as $index => $status) {
                if($status[0] == $selSt) {
                    break;
                }
            }
            $fields['status']['choices'] = [UserRefund::getAllStatus()[$index]];
            $fields['status']['checked'] = $selSt;
            unset($fields['type']);
            unset($fields['creator']);
            unset($fields['creator_id']);
            unset($fields['create_time']);
            unset($fields['reason']);
            unset($fields['account']);
            unset($fields['range']);
            unset($fields['amount']);
            break;
        case 'manual':
            $index = 0;
            $selSt = 2;
            foreach(UserRefund::getAllStatus() as $index => $status) {
                if($status[0] == $selSt) {
                    break;
                }
            }
            $fields['status']['choices'] = [UserRefund::getAllStatus()[$index]];
            $fields['status']['checked'] = $selSt;
            $index = 0;
            $selSt = 1;
            foreach(UserRefund::getAllType() as $index => $type) {
                if($type[0] == $selSt) {
                    break;
                }
            }
            $fields['type']['choices'] = [UserRefund::getAllType()[$index]];
            $fields['type']['checked'] = $selSt;
            unset($fields['creator']);
            unset($fields['creator_id']);
            unset($fields['create_time']);
            unset($fields['reason']);
            unset($fields['range']);
            unset($fields['amount']);
            break;
        }
        return array_values($fields);
    }

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new UserRefund();
        $this->model->orderBy('status', 'asc')->orderBy('create_time', 'asc')->orderBy('update_time', 'desc');
        $this->bindModelEvent();
        self::$PAGE_SIZE=20;

        $this->form=new Form($this->_setForm($this->_GET('type')));
        $this->list_display=[
            ['label'=>'流水ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'创建人','field'=>function($model){
                if($model->mCreator == 'admin') {
                    $res = self::_getResource($model->mCreatorId, 'admin', new Admin);
                    return "员工：" . $res->mName;
                } elseif($model->mCreator == 'buyer') {
                    $res = self::_getResource($model->mCreatorId, 'buyer', new Buyer);
                    return "买手：" . $res->mName;
                } 
                return "系统";
            }],
            ['label'=>'申请时间','field'=>function($model){
                return $model->mCreateTime ? date('Y-m-d H:i', $model->mCreateTime) : '';
            }],
            ['label'=>'更新时间','field'=>function($model){
                return $model->mUpdateTime ? date('Y-m-d H:i', $model->mUpdateTime) : '';
            }],
            ['label'=>'订单信息','field'=>function($model){
                $order = self::_getResource($model->mOrderId, 'order', new Order);
//                //判断首次支付是否存在
//                if(!empty($order->mPrePaymentId)){
//                    $payments []= self::_getResource($order->mPrePaymentId,'payment',new Payment,'id');
//                }
//                //判断首次支付是否存在
//                if(!empty($order->mPaymentId)){
//                    $payments []= self::_getResource($order->mPaymentId,'payment',new Payment,'id');
//                }
                $payments = (new Payment())->getPaymentsByOrderId($order->getData());
                $amount = 0;
                foreach($payments as $payment) {
                    $amount += $payment['amount'];
                }
                return "订单ID：" . $model->mOrderId . "<br />"
                    . "客人ID：" . $order->mUserId . "<br />"
                    . "订单金额：" . $order->mSumPrice . "<br />"
                    . "实付金额：" . $amount;
            }],
            ['label'=>'处理状态','field'=>function($model){
                $allStatus = UserRefund::getAllStatus();
                foreach($allStatus as $status) {
                    if($model->mStatus == $status[0]) {
                        $statusDesc = $status[1];
                    }
                }
                $allType = UserRefund::getAllType();
                foreach($allType as $type) {
                    if($model->mType == $type[0]) {
                        $typeDesc = $type[1];
                    }
                }
                return $statusDesc . ($model->mStatus == 2 ? ('，'.$typeDesc) : '') . '<br />' . $model->mNote;
            }],
            ['label'=>'退款原因','field'=>function($model){
                return $model->mReason;
            }],
            ['label'=>'退款范围','field'=>function($model){
                $allStatus = UserRefund::getAllRange();
                foreach($allStatus as $status) {
                    if($model->mRange == $status[0]) {
                        $statusDesc = $status[1];
                    }
                }
                return $statusDesc;
            }],
            ['label'=>'退款金额','field'=>function($model){
                if($model->mRange == 1) {
                    return $model->mAmount;
                } else {
//                    $payments = self::_getResource($model->mOrderId, 'payment', new Payment, 'order_id');
                    $order = self::_getResource($model->mOrderId, 'order', new Order);
                    $payments = (new Payment())->getPaymentsByOrderId($order->getData());
                    $retStr = 0;
                    foreach($payments as $payment) {
                        if($payment['status'] == 'payed') {
                            $retStr += $payment['amount'];
                        }
                    }
                    return sprintf("%.2f", $retStr);
                }
            }],
            ['label'=>'支付单号','field'=>function($model){
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                $payments = (new Payment())->getPaymentsByOrderId($order->getData());
//                $payments = self::_getResource($model->mOrderId, 'payment', new Payment, 'order_id');
                $retStr = '';
                foreach($payments as $payment) {
                    if($payment['status'] == 'payed') {
                        $retStr .= $payment['trade_no'].'<font color="red">(已支付'.$payment['amount'].'元，已退款'.sprintf("%.2f", $payment['refund_amount']).'元)</font><br />';
                    }
                }
                return $retStr;
            }],
            ['label'=>'支付账号','field'=>function($model){
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                $payments = (new Payment())->getPaymentsByOrderId($order->getData());
//                $payments = self::_getResource($model->mOrderId, 'payment', new Payment, 'order_id');
                $retStr = '';
                foreach($payments as $payment) {
                    if($payment['status'] == 'payed') {
                        $retStr .= $payment['pay_account'].'<br />';
                    }
                }
                return $retStr;
            }],
            ['label'=>'商品信息','field'=>function($model){
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                $stock = self::_getResource($order->mStockId, 'stock', new Stock);
                $stockAmount = self::_getResource($order->mStockAmountId, 'stockamount', new StockAmount);
                return $stock->mName . '<br />' . implode('/',array_keys(json_decode($stock->mSkuMeta, true))) . ':' . str_replace("\t", '/', $stockAmount->mSkuValue);
            }],
        ];
        
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'订单ID','paramName'=>'order_id','fusion'=>false,'in'=>true]),
            new Page_Admin_ChoiceFilter(['name'=>'处理状态','paramName'=>'status','choices'=>UserRefund::getAllStatus()]),
            new Page_Admin_ChoiceFilter(['name'=>'退款方式','paramName'=>'type','choices'=>UserRefund::getAllType()]),
            new Page_Admin_ChoiceFilter(['name'=>'退款范围','paramName'=>'range','choices'=>UserRefund::getAllRange()]),
            new Page_Admin_TimeRangeFilter(['name'=>'申请时间','paramName'=>'create_time']),
            new Page_Admin_TimeRangeFilter(['name'=>'更新时间','paramName'=>'update_time']),
        );

        $this->single_actions_default = [
            'edit' => false, 
            'delete' => false
        ];

        $this->single_actions=[
            ['label'=>'原路退', 'action'=>function($model){
//                $payments = self::_getResource($model->mOrderId, 'payment', new Payment, 'order_id');
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                $payments = (new Payment())->getPaymentsByOrderId($order->getData());
                $paymentIds = [];
                foreach($payments as $payment) {
                    if($payment['status'] == 'payed') {
                        $paymentIds[] = $payment['id'];
                    }
                }
                return '/admin/payment/refund?ids='.implode(',', $paymentIds);
            }, 'enable' => function($model) {
                $alipay = true;
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                $payments = (new Payment())->getPaymentsByOrderId($order->getData());
//                $payments = self::_getResource($model->mOrderId, 'payment', new Payment, 'order_id');
                foreach($payments as $payment) {
                    if(strpos($payment['source'], 'zfb') === false) {
                        $alipay = false;
                        break;
                    }
                }
                if(in_array($model->mStatus, [2, 3]) || $model->mRange == 1 || $alipay === false) {
                    return false;
                }
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                if($order->mCreateTime >= strtotime('2014-10-31')) {
                    return true;
                }
                return false;
            }],
            ['label'=>'手工退', 'action'=>function($model){
                return '/admin/userRefund?action=read&type=manual&id='.$model->mId;
            }, 'enable'=>function($model) {
                return !in_array($model->mStatus, [2, 3]) ? true : false;
            }],
            ['label'=>'取消', 'action'=>function($model){
                return '/admin/userRefund?action=read&type=cancel&id='.$model->mId;
            }, 'enable'=>function($model) {
                return $model->mStatus != 3 ? true : false;
            }, 'enable'=>function($model) {
                return !in_array($model->mStatus, [2, 3]) ? true : false;
            }],
            ['label'=>'订单', 'target'=>'_blank', 'action'=>function($model){
                return '/admin/order?__filter='.urlencode('id='.$model->mOrderId);
            }],
        ];

        $this->multi_actions=array(
            array('label'=>'批量原路退','action'=>'/admin/userRefund/batch?ids=__ids__'),
            array('label'=>'导出全部退款','required'=>false,'action'=>'/admin/userRefund/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        );
    }

    use ExportToCsvAction;

    public function batchAction() {
        $ids = $this->_GET('ids', 0);
        if(empty($ids)) {
            echo 'ID不正确';
            exit;
        }
        $finder = new UserRefund;
        $orders = $finder->addWhere('id', explode(',', $ids), 'in')->addWhere('status', '0')->findMap('order_id');
        if(empty($orders)) {
            echo 'ID不正确';
            exit;
        }

        $orderIdList = array_map(function($order){
            return $order->mOrderId;
        },$orders);

        $orders = (new Order())->getListByIdList(array_values($orderIdList));

        $prePaymenIdList = array_values(array_filter(array_map(function($order){
            if(!empty($order['pre_payment_id'])){
                return $order['pre_payment_id'];
            }
        },$orders)));

        $paymentIdList = array_values(array_filter(array_map(function($order){
            if(!empty($order['payment_id'])){
                return $order['payment_id'];
            }
        },$orders)));

        $IdList = array_merge($prePaymenIdList,$paymentIdList);
        if(count($IdList) <= 0){
            echo 'ID不正确';
            exit;
        }

        $finder = new Payment;
        $payments = $finder->addWhere('id', array_values($IdList), 'in')->addWhere('status', 'payed')->addWhere('create_time', strtotime('2014-10-31'), '>=')->setCols(['id'])->findMap('id');
        if(empty($payments)) {
            echo 'ID不正确';
            exit;
        }
        return ['redirect: /admin/payment/refund?ids='.implode(',', array_keys($payments))];
    }
}
