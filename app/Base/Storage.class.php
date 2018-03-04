<?php
class Base_Storage extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'order_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'buyer_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'user_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'pack_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'logistic_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'location','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'memo','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'status','type'=>"string",'key'=>false,'defalut'=>'waiting','null'=>true,],
            ['name'=>'stock_status','type'=>"string",'key'=>false,'defalut'=>'normal','null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'pending_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'cs_status','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'cs_memo','type'=>"string",'key'=>false,'defalut'=>'','null'=>true,],
            ['name'=>'cs_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'pu_status','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'pu_memo','type'=>"string",'key'=>false,'defalut'=>'','null'=>true,],
            ['name'=>'pu_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'in_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'out_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'action_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'send_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'imgs','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}