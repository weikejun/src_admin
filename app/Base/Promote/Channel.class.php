<?php
class Base_Promote_Channel extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'udid','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'mac','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'ifa','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'oid','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'appid','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'source','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'click_ip','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'active_ip','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'click_time','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'active_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'user_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'ping_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}