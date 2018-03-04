<?php
class Base_State_Rank extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'buyer_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'type','type'=>"int",'key'=>false,'defalut'=>'1','null'=>false,],
            ['name'=>'state_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'status','type'=>"int",'key'=>false,'defalut'=>'1','null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}