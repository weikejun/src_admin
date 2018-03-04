<?php
class Base_Logistic extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'order_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'live_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'user_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'buyer_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'logistic_no','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'logistic_provider','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'logistic_provider_fixed','type'=>"string",'key'=>false,'defalut'=>'','null'=>true,],
            ['name'=>'logistic_price','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'receiver_name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'receiver_addr','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'receiver_phone','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'receiver_email','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'sender_name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'sender_addr','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'sender_phone','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'sender_email','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'valid','type'=>"string",'key'=>false,'defalut'=>'valid','null'=>false,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'status','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}