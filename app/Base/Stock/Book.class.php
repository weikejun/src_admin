<?php
class Base_Stock_Book extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'category_id','type'=>"int",'key'=>false,'defalut'=>'1','null'=>false,],
            ['name'=>'stock_id','type'=>"int",'key'=>true,'defalut'=>'0','null'=>false,],
            ['name'=>'selector_id','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'comment','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'status','type'=>"int",'key'=>false,'defalut'=>'1','null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}