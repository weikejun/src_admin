<?php
class Base_Admin_Group extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'admin_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'group_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
                    ];
        return $FIELD_LIST;
    }
}