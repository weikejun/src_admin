<?php
class DeliveryAbroad extends Base_Delivery_Abroad{
    public static function getStatusChoice() {
        return [
            ['0', '未结算'],
            ['1', '已结算预付款'],
            ['2', '已结算全款'],
            ['3', '取消'],
        ];
    }
}
