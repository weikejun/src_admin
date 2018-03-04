<?php
class Base_Exchange_Rate extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'currency_short','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'currency_name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'buy','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'cash_buy','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'sell','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'cash_sell','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'pub_time','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
                    ];
        return $FIELD_LIST;
    }
}