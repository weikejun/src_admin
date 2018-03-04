<?php
class GlobalMethod{
    /**
     * 这个是切换全款与部分尾款模块
     */
    const ALL_PAY_SWITCH = 0;

    public static function orderLog($order,$log, $operator = 'user', $operatorId = 0, $order_type = 0){
        $order_log = new OrderLog();
        $order_id = $order->mId;
        $order_status = $order->mStatus;
        $order_log = false;//$order_log->addWhere('order_id',$order_id)->addWhere('op_type',$order_status)->select();
        if(!$order_log){
            $order_log = new OrderLog();
            $order_log->mOrderId = $order_id;
            $order_log->mLog = $log;
            $order_log->mUserId = $order->mUserId;
            $order_log->mCreateTime = time();
            $order_log->mOpType = $order_status;
            $order_log->mOperator = $operator;
            $order_log->mOperatorId = $operatorId;
            $order_log->mOrderType = $order_type;
            $order_log->save();
        }
    }

    public static function showOrderLog($order_id){
        $order_log = new OrderLog();
        $order_logs = $order_log->addWhere('order_id',$order_id)->addWhere('order_type', 0)->orderBy("create_time","desc")->find();
        $order_logs = array_map(function($order_log){
            $data = $order_log->getData();
            $data['log'] = OrderLog::genStatusInfo($data['op_type']).($data['log'] ? ';'.$data['log'] : '');
            return $data;
        },$order_logs
        );
        return $order_logs;
    }

    /**
     * @param $price
     * @param $payType
     * @return float
     */
    public static function countPrepay($price, $payType=Order::PRE_PAY) {
        if($payType == Order::ALL_PAY){
            return round($price , 2);
        }else{
            return round($price * PREPAY_RATIO, 2);
        }
    }

    # 根据payment自增ID生成订单ID
    public static function genOrderId($paymentId) {
        return ORDER_PREFIX.date('YmdHis').(ORDER_BASE_NUM + $paymentId);
    }

    # 根据订单ID生成payment自增ID
    public static function genPaymentId($orderId) {
        return substr($orderId, strlen(ORDER_PREFIX) + 14) - ORDER_BASE_NUM;
    }

    //根据payorder总价和代金券金额返回实际支付价格
    public static function getPayOrderAmount($total_price, $coupon_value=0) {
        if (empty($coupon_value)) {
            return $total_price;
        }
        $total_price = ($total_price - $coupon_value)>0 ? round(100*($total_price - $coupon_value))/100 : 0.01;
        return $total_price;
    }
}
