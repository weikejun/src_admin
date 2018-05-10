<?php
class Base_InvestmentExit extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'project_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'amount','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'currency','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'exit_way','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'stock_num','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'exit_rate','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'rest_rate','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'return_rate','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'memo','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'exit_time','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'admin_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
                    ];
        return $FIELD_LIST;
    }
}
