<?php
class Base_Role_Permission extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'group_id','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'admin_id','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'permission_id','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
                    ];
        return $FIELD_LIST;
    }
}