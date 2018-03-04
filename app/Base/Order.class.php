<?php
class Base_Order extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'user_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'status','type'=>"string",'key'=>false,'defalut'=>'wait_prepay','null'=>true,],
            ['name'=>'live_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'buyer_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'stock_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'stock_amount_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'num','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'sum_price','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'pre_payment_id','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'payment_id','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'coupon_id','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'logistic_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'pack_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'note','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'sys_note','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'user_addr_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'buyer_withdraw_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'country','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'province','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'city','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'addr','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'postcode','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'phone','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'cellphone','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'email','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'source','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'vid','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'pay_order_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'pay_type','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}
