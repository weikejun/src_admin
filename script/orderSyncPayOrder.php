<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-12-22
 * Time: 下午3:02
 */
$lastOrderId = 0;
$limit = 1000;

$payOrderModel  = PayOrder::getInstance();
$orderModel = new Order();
while($res = DB::query("select * from `order` where `id` > $lastOrderId limit $limit")){
    $count = count($res);
    foreach($res as $ret){
        $payOrderInfo = array(
            'user_id' => $ret['user_id'],
            'amount' => $ret['sum_price'],
            'pre_payment_id' => $ret['pre_payment_id'],
            'payment_id' => $ret['payment_id'],
            'coupon_id' => $ret['coupon_id'],
            'create_time' => $ret['create_time'],
            'update_time' => time(),
            'vid' => 0,
            'pay_type' => 0,
        );

        switch($ret['status']){
            case 'wait_prepay':
                $payOrderInfo['status'] = 'wait_prepay';
                break;
            case 'prepayed':
                $payOrderInfo['status'] = 'prepayed';
                break;
            case 'wait_pay':
                $payOrderInfo['status'] = 'wait_pay';
                break;
            case 'payed':
            case 'packed':
            case 'to_demostic':
            case 'to_user':
            case 'success':
            case 'post_sale':
                $payOrderInfo['status'] = 'payed';
                break;
            case 'wait_refund':
                $payOrderInfo['status'] = 'refund';
                break;
            case 'refund':
                $payOrderInfo['status'] = 'refund';
                break;
            case 'full_refund':
                $payOrderInfo['status'] = 'payed';
                break;
            case 'timeout':
                $payOrderInfo['status'] = 'timeout';
                break;
            case 'canceled':
                $payOrderInfo['status'] = 'canceled';
                break;
            case 'returned':
                $payOrderInfo['status'] = 'returned';
                break;
            case 'fail':
                $payOrderInfo['status'] = 'canceled';
                break;
            default:
                break;
        }

        $payOrderId = $payOrderModel->addWhere('id',$ret['pay_order_id'])->update($payOrderInfo);

        $lastOrderId = $ret['id'];
    }
}