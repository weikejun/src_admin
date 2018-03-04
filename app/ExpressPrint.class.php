<?php

class ExpressPrint extends Base_Express_Print{
    public static function getAllProvider(){
        return [
            ['shunfeng_new','顺丰速运'],
            ['shunfeng','顺丰（旧）'],
            ['yuantong','圆通'],
        ];
    }
}
