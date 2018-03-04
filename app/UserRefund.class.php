<?php

class UserRefund extends Base_User_Refund{
    private static $ins = null;

    /**
     * 单例模式
     * @return null|UserRefund
     */
    public static function getInstance(){
        if(!self::$ins){
            self::$ins = new self();
        }
        return self::$ins;
    }

    public static function getAllStatus(){
        return [
            ['0','未处理'],
            ['2','已完成'],
            ['3','已取消'],
            ['4','退款失败'],
        ];
    }

    public static function getAllType(){
        return [
            ['0','原路退'],
            ['1','手工退'],
        ];
    }

    public static function getAllRange(){
        return [
            ['0','全额'],
            ['1','部分'],
        ];
    }

    /**
     * 根据订单id获取退款信息
     * @param $orderId
     * @return array
     */
    public function getInfoByOrderId($orderId){
        if(empty($orderId)){
            return null;
        }else{
            $ret=$this->addWhere('order_id',$orderId)->select();
            if(!empty($ret)){
                $ret = $this->getData();
            }
            return $ret;
        }
    }

    /**
     *  拼装退款信息
     * @param $info
     */
    public static function utils($info){
        if(empty($info)){
            return null;
        }else{
            if($info['range'] == 0){
                $orderInfo = (new Order())->getOrderInfoByOrderId($info['order_id']);
                if(!empty($orderInfo)){
                    $amountDesc = "全款(￥".$orderInfo['sum_price'].")";
                }
            }else if($info['range'] == 1){
                $amountDesc = "金额(￥".$info['amount'].")";
            }

            $desc = null;
            switch($info['status']){
                case '0':
                    $desc = "退款处理中，退款".$amountDesc."，需1~3个工作日到账";
                    break;
                case '1':
                case '2':
                    $desc = "退款成功，退".$amountDesc."，需1~3个工作日到账";
                    break;
                default:
                    break;
            }

            return $desc;
        }
    }
}
