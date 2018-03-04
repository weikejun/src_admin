<?php
class Base_Favor extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'favor_type','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'favor_id','type'=>"int",'key'=>true,'defalut'=>'0','null'=>false,],
            ['name'=>'notify_id','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'user_id','type'=>"int",'key'=>true,'defalut'=>'0','null'=>false,],
            ['name'=>'valid','type'=>"int",'key'=>false,'defalut'=>'1','null'=>false,],
            ['name'=>'read','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}