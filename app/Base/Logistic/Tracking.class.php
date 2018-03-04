<?php
class Base_Logistic_Tracking extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'logistic_no','type'=>"string",'key'=>true,'defalut'=>'','null'=>false,],
            ['name'=>'logistic_provider','type'=>"string",'key'=>false,'defalut'=>'','null'=>false,],
            ['name'=>'context','type'=>"string",'key'=>false,'defalut'=>'','null'=>false,],
            ['name'=>'ftime','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}