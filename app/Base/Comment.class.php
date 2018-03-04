<?php
class Base_Comment extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'ci_user_type','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'ci_user_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'comment','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'state_type','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'state_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'owner_type','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'owner_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'comment_type','type'=>"int",'key'=>false,'defalut'=>'1','null'=>false,],
            ['name'=>'reply_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'reply_user_type','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'reply_user_id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'status','type'=>"int",'key'=>false,'defalut'=>'1','null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}