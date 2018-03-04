<?php
class Base_Order_Log extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'order_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'log','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'user_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'op_type','type'=>"string",'key'=>false,'defalut'=>'','null'=>true,],
            ['name'=>'operator','type'=>"string",'key'=>false,'defalut'=>'','null'=>true,],
            ['name'=>'operator_id','type'=>"string",'key'=>false,'defalut'=>'','null'=>true,],
            ['name'=>'order_type','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}
