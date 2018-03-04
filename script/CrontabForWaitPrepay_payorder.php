<?php
$now_time = time();
$payOrder = new PayOrder();
$payOrders = $payOrder->addWhere("status","wait_prepay")->addWhere("update_time",$now_time - 600,"<")->find();
foreach($payOrders as $payOrder){

    $payOrder->mStatus = 'canceled';
    $payOrder->mUpdateTime = time();
    $payOrder->save();
    GlobalMethod::orderLog($payOrder, '', 'system',0,1);
    if(!empty($payOrder->mCouponId)){
        Coupon::resendCoupon($payOrder->mCouponId);
    }
    
    //状态同步到order add by hongjie
    $order = new Order();
    $orders = $order->addWhere('pay_order_id', $payOrder->mId)->find();
    foreach ($orders as $order) {
        if(StockAmount::releaseLockedAmount($order)){
            $order->mStatus = 'canceled';
            $order->mUpdateTime = time();
            $order->save();
            GlobalMethod::orderLog($order, '', 'system');
        }
    }
}
 
