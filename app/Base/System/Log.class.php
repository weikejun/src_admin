<?php
class Base_System_Log extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'operator_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'operator_ip','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'action','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'resource','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'res_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'detail','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}
