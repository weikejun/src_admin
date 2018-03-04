<?php
class Base_User_Reminder extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'model_type','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'model_id','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'user_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'push_id','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'type','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'status','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'desc','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}