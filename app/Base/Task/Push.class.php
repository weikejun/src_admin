<?php
class Base_Task_Push extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'status','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'content','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'creator_id','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'type','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'user_ids','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'success','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'fail','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'push_time','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'end_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
                    ];
        return $FIELD_LIST;
    }
}