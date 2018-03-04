<?php
class Base_Index_New extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'type','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'model_id','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'title','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'imgs','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'imgs6','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'order','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'valid','type'=>"string",'key'=>false,'defalut'=>'valid','null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'channel','type'=>"int",'key'=>false,'defalut'=>'1','null'=>false,],
            ['name'=>'url','type'=>"string",'key'=>false,'defalut'=>'','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}