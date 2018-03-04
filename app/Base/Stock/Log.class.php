<?php
class Base_Stock_Log extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'stock_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'types','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'operation','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'changes','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'amount','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'log_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'user_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}