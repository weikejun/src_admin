<?php
class Base_Delivery_Abroad extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'order_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'buyer_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'pack_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'live_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'stock_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'sku_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'status','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'pay_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'delivery_time','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}