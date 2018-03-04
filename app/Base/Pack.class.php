<?php
class Base_Pack extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'status','type'=>"string",'key'=>false,'defalut'=>'not_send','null'=>true,],
            ['name'=>'live_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'live_ids','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'buyer_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'logistic_provider_fixed','type'=>"string",'key'=>false,'defalut'=>'','null'=>true,],
            ['name'=>'logistic_provider','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'logistic_no','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'valid','type'=>"string",'key'=>false,'defalut'=>'valid','null'=>true,],
            ['name'=>'logistic_price','type'=>"string",'key'=>false,'defalut'=>'0.00','null'=>false,],
            ['name'=>'imgs','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'logistic_imgs','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'note','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'logistic_price_unit','type'=>"string",'key'=>false,'defalut'=>'CNY','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}