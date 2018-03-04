<?php
class Base_Stock extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'live_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'buyer_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'category_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'serial_num','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'model_num','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'brand','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'pricein','type'=>"string",'key'=>false,'defalut'=>'0.00','null'=>true,],
            ['name'=>'pricein_unit','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'priceout','type'=>"string",'key'=>false,'defalut'=>'0.00','null'=>true,],
            ['name'=>'priceout_unit','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'original_price','type'=>"string",'key'=>false,'defalut'=>'0.00','null'=>true,],
            ['name'=>'imgs','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'flow_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'sell_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'valid','type'=>"string",'key'=>false,'defalut'=>'valid','null'=>true,],
            ['name'=>'sku_meta','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'note','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'tags','type'=>"string",'key'=>false,'defalut'=>'','null'=>true,],
            ['name'=>'status','type'=>"string",'key'=>false,'defalut'=>'not_verify','null'=>true,],
            ['name'=>'check_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'checker_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'check_words','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'onshelf','type'=>"int",'key'=>false,'defalut'=>'1','null'=>true,],
            ['name'=>'prepay','type'=>"string",'key'=>false,'defalut'=>'0.00','null'=>true,],
            ['name'=>'liked','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'commented','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'score','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'rate_count','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'rate_tags','type'=>"string",'key'=>false,'defalut'=>'','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}