<?php
class Base_Order_Grades extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'order_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'user_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'buyer_grade','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'dilivery_grade','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'speed_grade','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'service_grade','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'shopper_grade','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'marks','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}