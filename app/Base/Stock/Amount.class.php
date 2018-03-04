<?php
class Base_Stock_Amount extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'stock_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'sku_value','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'amount','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'locked_amount','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'sold_amount','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'valid','type'=>"string",'key'=>false,'defalut'=>'valid','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}