<?php
class Base_Public_Notification extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'type','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'title','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'note','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'link','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_buyer_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_admin_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'valid','type'=>"string",'key'=>false,'defalut'=>'valid','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}