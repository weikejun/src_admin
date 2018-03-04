<?php
class Base_Payment extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'user_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'status','type'=>"string",'key'=>false,'defalut'=>'wait_pay','null'=>true,],
            ['name'=>'order_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'type','type'=>"string",'key'=>false,'defalut'=>'prepay','null'=>true,],
            ['name'=>'amount','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'discount','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'trade_no','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'source','type'=>"string",'key'=>false,'defalut'=>'zfb','null'=>true,],
            ['name'=>'remark','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'pay_account','type'=>"string",'key'=>false,'defalut'=>'','null'=>true,],
            ['name'=>'platform_trade_no','type'=>"string",'key'=>true,'defalut'=>'','null'=>true,],
            ['name'=>'refund_amount','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'refund_memo','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'order_type','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}