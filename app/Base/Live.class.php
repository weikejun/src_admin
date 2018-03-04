<?php
class Base_Live extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'intro','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'buyer_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'country','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'province','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'city','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'address','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'brands','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'start_time','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'end_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'valid','type'=>"string",'key'=>false,'defalut'=>'valid','null'=>false,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'status','type'=>"string",'key'=>false,'defalut'=>'not_verify','null'=>true,],
            ['name'=>'imgs','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'dim_imgs','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'product_type','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'check_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'checker_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'check_words','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'selector','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'editor','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'fee','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'list_show','type'=>"int",'key'=>false,'defalut'=>'1','null'=>false,],
            ['name'=>'type','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}