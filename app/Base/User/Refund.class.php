<?php
class Base_User_Refund extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'order_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'status','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'type','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'note','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'account','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'creator','type'=>"string",'key'=>false,'defalut'=>'system','null'=>true,],
            ['name'=>'creator_id','type'=>"string",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'operator','type'=>"string",'key'=>false,'defalut'=>'admin','null'=>true,],
            ['name'=>'operator_id','type'=>"string",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'reason','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'range','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'amount','type'=>"string",'key'=>false,'defalut'=>'0.00','null'=>false,],
                    ];
        return $FIELD_LIST;
    }
}