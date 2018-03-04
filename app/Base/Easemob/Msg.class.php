<?php
class Base_Easemob_Msg extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'msg_id','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'from','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'to','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'msg_type','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'msg_text','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'send_time','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'rawdata','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}