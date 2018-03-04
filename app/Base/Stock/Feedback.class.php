<?php
class Base_Stock_Feedback extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'stock_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'live_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'buyer_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'user_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'type','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'info','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}