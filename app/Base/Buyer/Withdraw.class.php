<?php
class Base_Buyer_Withdraw extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'buyer_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'admin_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'account_type','type'=>"string",'key'=>false,'defalut'=>'local','null'=>false,],
            ['name'=>'account_no','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'account_name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'account_address','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'account_bank','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'account_swift','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'account_routing','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'account_country','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'account_city','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'amount','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'note','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'log','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'admin_note','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'status','type'=>"string",'key'=>false,'defalut'=>'begin','null'=>false,],
                    ];
        return $FIELD_LIST;
    }
}