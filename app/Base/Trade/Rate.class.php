<?php
class Base_Trade_Rate extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'order_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'user_id','type'=>"int",'key'=>true,'defalut'=>'0','null'=>false,],
            ['name'=>'stock_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'buyer_id','type'=>"int",'key'=>true,'defalut'=>'0','null'=>false,],
            ['name'=>'score','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'stock_desc','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'buyer_desc','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'status','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'comment','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}