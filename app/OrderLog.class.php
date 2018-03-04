<?php
class OrderLog extends Base_Order_Log{
    public static $status_info = [
        'wait_prepay' => '订单生成',
        'prepayed' => '已支付定金',
        'wait_pay' => '备货完毕',
        'payed' => '已支付全款',
        'packed' => '商品打包完毕',
        'to_demostic' => '商品海外发出',
        'demostic' => '商品国内入库',
        'to_user' => '商品国内发出',
        'success' => '订单完成',
        'fail' => '订单取消',
        'refund' => '订单关闭，已退定金',
        'full_refund' => '订单关闭，已退全款',
        'returned' => '订单关闭，已退货',
        'canceled' => '订单取消',
        'wait_refund' => '备货失败，商品缺货',
        'timeout' => '未按时支付全款'
    ];

    private static $_operatorDesc = [
        'user' => '买家',
        'buyer' => '买手',
        'admin' => '工作人员',
        'system' => '系统自动',
    ];

    public static function genStatusInfo($status){
        return self::$status_info[$status];
    }

    public static function getOperatorDesc($operator) {
        if(isset(self::$_operatorDesc[$operator])) {
            return self::$_operatorDesc[$operator];
        }

        return '未知';
    }

    public static function getOperators() {
        $choices = array();
        foreach(self::$_operatorDesc as $operator => $desc) {
            $choices[] = [$operator, $desc];
        }
        return $choices;
    }

    public static function getOrderStatus() {
        $choices = array();
        foreach(self::$status_info as $status => $desc) {
            $choices[] = [$status, $desc];
        }
        return $choices;
    }

    /**
     *  获取最新的订单追踪状态
     * @param $orderId
     * @return array
     */
    public function getOrderLastLog($orderId){
        if(empty($orderId)){
            return null;
        }else{
            $ret = $this->addWhere('order_id',$orderId)->orderBy('id','desc')->select();
            if(!empty($ret)){
                $ret = $this->getData();
            }
            return $ret;
        }
    }

    /**
     * 查询某个状态的log是否存在
     * @param $orderId
     * @param $status
     * @return null
     */
    public function getLogByStatus($orderId, $status){
        if(empty($orderId)){
            return null;
        }else{
            $ret = $this->addWhere('order_id',$orderId)->addWhere('op_type', $status)->select();
            if(!empty($ret)){
                $ret = $ret->getData();
            }
            return $ret;
        }
    }
}
