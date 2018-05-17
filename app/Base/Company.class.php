<?php
class Base_Company extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'bussiness','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'short','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'init_manager','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'current_manager','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'legal_person','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'director','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'director_turn','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'director_status','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'filling_keeper','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'total_stock','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
        ];
        return $FIELD_LIST;
    }
}
