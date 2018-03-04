<?php
$data['total'] = 5;
$data['name'] = '满100减50';
$data['value'] = 50;
$data['low_price'] = 100;
$data['scene'] = 2;
$data['live_id'] = 0;
$data['desc'] = '线上测试';
$data['status'] = '';
$data['user_id'] = 0;
$data['expireTime'] = '';
$data['source'] = 'system';
$ret = Coupon::createCoupon($data);
var_dump($ret);
 
