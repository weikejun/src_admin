<?php
class Base_Payment extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'project_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'amount','type'=>"string",'key'=>false,'defalut'=>'wait_pay','null'=>true,],
            ['name'=>'currency','type'=>"string",'key'=>false,'defalut'=>'wait_pay','null'=>true,],
            ['name'=>'operator','type'=>"string",'key'=>false,'defalut'=>'wait_pay','null'=>true,],
            ['name'=>'pay_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'admin_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}
