<?php
class Base_Easemob_Anonymous extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'username','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'password','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'session_id','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}