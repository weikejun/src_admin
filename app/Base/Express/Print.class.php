<?php
class Base_Express_Print extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'storage_ids','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'print_time','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}