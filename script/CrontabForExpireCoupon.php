<?php
$now_time = time();
$coupon = new Coupon();
$coupons = $coupon->addWhereRaw("`expire_time`<$now_time and status!='expire'")->findMap('id');
foreach($coupons as $coupon){
    $coupon->mStatus = 'expire';
    $coupon->mUpdateTime = $now_time;
    $coupon->save();
}
 
