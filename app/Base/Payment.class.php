<?php
class Base_Payment extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'project_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'company_id','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'amount','type'=>"string",'key'=>false,'defalut'=>'wait_pay','null'=>false,],
            ['name'=>'currency','type'=>"string",'key'=>false,'defalut'=>'wait_pay','null'=>false,],
            ['name'=>'operator','type'=>"string",'key'=>false,'defalut'=>'wait_pay','null'=>false,],
            ['name'=>'pay_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'memo','type'=>"string",'key'=>false,'defalut'=>'wait_pay','null'=>true,],
            ['name'=>'update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
        ];
        return $FIELD_LIST;
    }
}
