<?php
class Base_Live_Flow extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'live_id','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'imgs','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'content','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'flow_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'status','type'=>"int",'key'=>false,'defalut'=>'1','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}