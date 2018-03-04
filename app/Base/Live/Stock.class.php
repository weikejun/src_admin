<?php
class Base_Live_Stock extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'live_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'stock_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'status','type'=>"string",'key'=>false,'defalut'=>'not_verify','null'=>true,],
            ['name'=>'stock_type','type'=>"int",'key'=>false,'defalut'=>'1','null'=>true,],
            ['name'=>'flow_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'sell_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'checker_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'check_words','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}