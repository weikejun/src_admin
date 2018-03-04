<?php
class Payment extends Base_Payment{

    //支付类型为order
    const ORDER_TYPE_ORDER = 0;

    //支付类型为payOrder
    const ORDER_TYPE_PAY_ORDER = 1;

    public static function getAllStatus(){
        return [
            ['wait_pay','未支付'],
            ['payed','已支付'],
        ];
    }

    public static function getOrderType(){
        return [
            ['0','订单'],
            ['1','支付订单']
        ];
    }

    public static function getAllType(){
        return [
            ['prepay','定金'],
            ['pay','全款'],
        ];
    }

    public static function getAllSource(){
        return [
            ['kefu','客服手工'],
            ['zfb','支付宝网页'],
            ['zfb_client','支付宝客户端'],
            ['wx_client','微信客户端'],
        ];
    }

    /**
     * @param $orderInfo
     * @return array|null
     */
    public function getPaymentsByOrderId($orderInfo){
        if(empty($orderInfo)){
            return null;
        }else{
            $payments = array();
            //判断首次支付是否存在
            if(!empty($orderInfo['pre_payment_id'])){
                $temp = (new self())->addWhere('id',$orderInfo['pre_payment_id'])->select();
                if(!empty($temp)){
                    $temp = $temp->getData();
                }
                $payments []= $temp;
            }
            //判断二次支付是否存在
            if(!empty($orderInfo['payment_id'])){
                $temp = (new self())->addWhere('id',$orderInfo['payment_id'])->select();
                if(!empty($temp)){
                    $temp = $temp->getData();
                }
                $payments []= $temp;
            }
            return $payments;
        }
    }
}
