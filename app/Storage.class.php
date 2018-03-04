<?php

class Storage extends Base_Storage{
    public static function getAllStatus(){
        return [
            ['waiting','未到货'],
            ['in','已收货'],
            ['out','已发货'],
            ['canceled','已取消'],
        ];
    }

    public static function getStockStatus(){
        return [
            ['normal','正常件'],
            ['pending','问题件'],
        ];
    }

    public static function getCsStatus(){
        return [
            ['0','待处理'],
            ['1','已处理'],
        ];
    }

    public static function getPurchaseStatus(){
        return [
            ['0','待处理'],
            ['1','已处理'],
        ];
    }
}
