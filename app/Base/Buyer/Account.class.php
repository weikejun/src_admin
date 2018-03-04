<?php
class Base_Buyer_Account extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'buyer_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'type','type'=>"string",'key'=>false,'defalut'=>'local','null'=>false,],
            ['name'=>'no','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'address','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'bank','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'swift','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'routing','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'country','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'city','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}