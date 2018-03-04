<?php
class Base_Trade_Cart extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'user_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'stock_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'stock_amount_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'number','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'buyer_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'live_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'create_time','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'source','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
                    ];
        return $FIELD_LIST;
    }
}