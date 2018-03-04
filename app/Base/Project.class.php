<?php
class Base_Project extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'code','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'turn','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'turn_sub','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'investment_type','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'proj_status','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'decision_date','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'close_date','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'owner_pre','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'owner_now','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'law_firm','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'legal_in','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'director_in','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'director_out','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'director_status','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'observer','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'pre_money','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'post_money','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'stock_price','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'financing_amount','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'currency','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'investment_co','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'period','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'multi_currency','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'our_amount','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'other_amount','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'stock_trans','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'trans_detail','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'amount_memo','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'loan','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'shareholding','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'shareholding_new','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'shareholding_total','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'shareholding_member','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'shareholding_esop','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'mirror','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'entrustment','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'stocknum_all','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'stocknum_turn','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'stocknum_total','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'hold_value','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'return_rate','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'return_irr','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}
