<?php
class Base_Trademsg_Queue extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'send_type','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'to_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'msg_type','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'content','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'status','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'send_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'live_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
                    ];
        return $FIELD_LIST;
    }
}