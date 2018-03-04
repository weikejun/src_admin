<?php
class Base_Live_Apply extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'address','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'intro','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'brands','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'text','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'imgs','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'start_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'end_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'checker_id','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'check_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'check_result','type'=>"string",'key'=>false,'defalut'=>'unchecked','null'=>false,],
            ['name'=>'check_words','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'valid','type'=>"string",'key'=>false,'defalut'=>'valid','null'=>true,],
            ['name'=>'product_type','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}