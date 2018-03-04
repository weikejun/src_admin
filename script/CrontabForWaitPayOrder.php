<?php
$now_time = time();
$order = new Order();
$orders = $order->addWhereRaw("`status` = 'wait_pay' and ((`update_time` < " . ($now_time - 3*86400) . " and `create_time` >= unix_timestamp('2014-10-28 01:00:00')) or (`update_time` < " . ($now_time - 7*86400) . " and `create_time` < unix_timestamp('2014-10-28 01:00:00')))")->findMap('id');
foreach($orders as $order){
    echo $order->mId;
    $order->mStatus = 'timeout';
    $order->mUpdateTime = time();
    $order->save();
    GlobalMethod::orderLog($order, '', 'system');

    //状态同步到pay_order add by hongjie
    $payOrderInfo['mStatus'] = 'timeout';
    $payOrderInfo['id'] = $order->mPayOrderId;
    $payOrder = PayOrder::updatePayOrder($payOrderInfo); 
    if ($payOrder) {
        GlobalMethod::orderLog($payOrder, '', 'system', 0, 1);
    }

    StockAmount::releaseSoldAmount($order);
    if ($order->mCouponId) {
        Coupon::changeCouponStatus($order->mCouponId, 'nouse');
    }
}
 
