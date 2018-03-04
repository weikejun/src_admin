<?php
class Base_Admin extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'password','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'gender','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'department','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'valid','type'=>"string",'key'=>false,'defalut'=>'valid','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}