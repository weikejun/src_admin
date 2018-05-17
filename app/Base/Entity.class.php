<?php
class Base_Entity extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'tp','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'currency','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
        ];
        return $FIELD_LIST;
    }
}
