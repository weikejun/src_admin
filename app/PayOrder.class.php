<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-12-9
 * Time: 下午3:12
 */
class PayOrder extends Base_Pay_Order{

    private static $instance = null;

    /**
     * 单例模式
     * @return null|PayOrder
     */
    public static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 根据用户Id获取payOrder的基础内容
     * @param $userId
     * @param $status
     * @param $pageId
     * @param $count
     * @return array
     */
    public function getBaseListByUserId($userId,$status,$pageId = 0,$count = 20){
        $offset = $pageId * $count;
        if(!empty($status)){
            $this->addWhere('status',$status,'in');
        }
        $payOrderList = $this->addWhere('user_id',$userId)->orderBy('id','desc')->limit($offset,$count)->find();
        //获取所有的payOrderIdList
        $payOrderList = array_map(function($payOrder){
            return $payOrder->getData();
        },$payOrderList);
        return array_values($payOrderList);
    }

    /**
     * 订单是否完成支付
     */
    public static function isPayed($status,$payType){
        if($status == 'wait_prepay' || $status == 'wait_pay'){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 获取支付id的基本信息
     * @param $payOrderId
     * @return array
     */
    public function getPayOrderInfoById($payOrderId){
        if(empty($payOrderId)){
            return null;
        }else{
            $ret = $this->addWhere('id',$payOrderId)->select();
            if(!empty($ret)){
                $ret = $ret->getData();
            }
            return $ret;
        }
    }

    //订单写入pay_order
    public static function addPayOrder($payOrderInfo) {
        $pay_order = new self();
        $pay_order->mUserId = User::getCurrentUser()->mId;
        $pay_order->mAmount = $payOrderInfo['amount'];
        $pay_order->mStatus = 'wait_prepay';
        $pay_order->mCouponId = $payOrderInfo['couponId'];
        $pay_order->mCreateTime = $payOrderInfo['time'];
        $pay_order->mUpdateTime = $payOrderInfo['time'];
        $pay_order->mVid = $payOrderInfo['vid'];
        $pay_order->mPayType = $payOrderInfo['pay_type'];
        if ($pay_order->save()) {
            return $pay_order;
        }
        return false;
    }

    //更新payorder字段,$payOrderInfo ['mStatus'=>'prepayed']
    public static function updatePayOrder($payOrderInfo) {
        if (empty($payOrderInfo)) return false;
        if (empty($payOrderInfo['id'])) return false;
        $pay_order = new self();
        $pay_order = $pay_order->addWhere('id', $payOrderInfo['id'])->select();
        if (!$pay_order) {
            return false;
        }
        unset($payOrderInfo['id']);
        foreach ($payOrderInfo as $k=>$v) {
            $pay_order->$k=$v;
        }
        $pay_order->mUpdateTime = time();
        if ($pay_order->save()) {
            return $pay_order;
        }
        return false;
    }

    /**
     * 更新支付订单状态
     * @param $payOrderId
     * @param $curStatus
     * @param $toStatus
     * @return bool | int
     */
    public function updatePayOrderStatus($payOrderId,$curStatus,$toStatus){
        if(empty($payOrderId)){
            return false;
        }else{
            $ret = $this->addWhere('id',$payOrderId)->addWhere('status',$curStatus)->update([
                'status' => $toStatus,
                'update_time' => time(),
            ]);
            return $ret;
        }
    }
    
}
