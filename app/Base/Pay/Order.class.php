<?php
class Base_Pay_Order extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'user_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'amount','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'status','type'=>"string",'key'=>false,'defalut'=>'wait_prepay','null'=>false,],
            ['name'=>'pre_payment_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'payment_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'coupon_id','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'create_time','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'vid','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'pay_type','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}
