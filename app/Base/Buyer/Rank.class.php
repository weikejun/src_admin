<?php
class Base_Buyer_Rank extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'buyer_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'type','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'update_time','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'selector_id','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'comment','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'soso_comment','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}