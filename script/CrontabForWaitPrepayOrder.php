<?php
$now_time = time();
$order = new Order();
$orders = $order->addWhere("status","wait_prepay")->addWhere("update_time",$now_time - 600,"<")->find();
foreach($orders as $order){
    if(StockAmount::releaseLockedAmount($order)){
        $order->mStatus = 'canceled';
        $order->mUpdateTime = time();
        $order->save();
        GlobalMethod::orderLog($order, '', 'system');

        //状态同步到pay_order add by hongjie
        $payOrderInfo['mStatus'] = 'canceled';
        $payOrderInfo['id'] = $order->mPayOrderId;
        $payOrder = PayOrder::updatePayOrder($payOrderInfo); 
        if ($payOrder) {
            GlobalMethod::orderLog($payOrder, '', 'system', 0, 1);
        }
    }
}
 
