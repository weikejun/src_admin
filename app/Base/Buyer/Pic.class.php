<?php
class Base_Buyer_Pic extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'buyer_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'note','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'imgs','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'location','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'liked','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'commented','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'status','type'=>"int",'key'=>false,'defalut'=>'1','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}