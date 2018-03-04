<?php 
class BuyerWithdraw extends Base_Buyer_Withdraw{
    public static function getAllStatus(){
        return [
            ['begin','未打款'],
            ['finish','已打款'],
            ];
    }  
}
