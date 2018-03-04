<?php
class Base_User_Addr extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'user_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'country','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'province','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'city','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'addr','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'postcode','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'phone','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'cellphone','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'email','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'valid','type'=>"string",'key'=>false,'defalut'=>'valid','null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'first_choice','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}