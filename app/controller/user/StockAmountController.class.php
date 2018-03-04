<?php
class StockAmountController extends AppBaseController{
    public function lockAction(){
        //TODO
    }
    public function unlockAction(){
        //TODO
    }
    public function setShelf(){
        //TODO 上架、下架
    }
    public function calPayAction(){
        $id=$this->_GET("stock_amount_id",0);
        $num=$this->_GET("num",0);
        $amount = new StockAmount();
        $amount = $amount->addWhere('id', $id)->addWhere('valid', 'valid')->select();
        if(!$amount){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],'20015')];
        }
        $stock=new Stock();
        $stock=$stock->addWhere('id',$amount->mStockId)->addWhere('valid','valid')->addWhere('onshelf', 1)->select();
        if(!$stock){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],'20003')];
        }
        $total_pay = $num * $stock->mPriceout; 
        $data['pay'] = $total_pay;
        $data['prepay'] = GlobalMethod::countPrepay($total_pay,GlobalMethod::ALL_PAY_SWITCH);
        $data['pay_unit']=$stock->mPriceoutUnit;
        $data['pay_show']=TableUtils::getUnitShow($stock->mPriceoutUnit);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($data)];
    } 

    public function inAction(){
        
        if(isset($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],20007)];
        }

        if(isset($_POST['amount'])) {
            $amount = $_POST['amount'];
        } else {
            $amount = 1;
        }


        $stock = new StockAmount();
        
        $ret=$stock->addWhere("id",$id)
            ->addWhere("valid",'valid')
            ->update(
                array(
                    "amount"=>"`amount`+$amount",
                )
            );
        if(!$ret){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],10007)];
        }
        $stocklog=new StockLog();
        $stocklog->insert(['stock_id'=>null,'stock_amount_id'=>$id,'operation'=>'in','changes'=>"",'amount'=>$amount,'log_time'=>date("Y-m-d H:i:s"),'user_id'=>User::getCurrentUser()->mId]);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($stock->getData())];
    }
    public function outAction(){
        if(isset($_POST['id'])) {
            $id= $_POST['id'];
        } else {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],20007)];
        }
        if(isset($_POST['amount']) and (int)$_POST['amount'] > 1) {
            $amount = (int)$_POST['amount'];
        } else {
            $amount = 1;
        }

        if(isset($_POST['out_type'])) {
            $outType = $_POST['out_type'];
        } else {
            $outType = "order";
        }

        $stock = new Stock();
        
        $ret=$stock->addWhere("id",$id)
            ->addWhere("valid",'valid')
            ->update(
                array(
                    "amount"=>"`amount`-$amount",
                )
            );
        if(!$ret){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],10007)];
        }
        $stocklog=new StockLog();
        $stocklog->insert(['stock_id'=>null,'stock_amount_id'=>$id,'operation'=>'in','changes'=>"",'amount'=>$amount,'log_time'=>date("Y-m-d H:i:s"),'log_user'=>User::getCurrentUser()->mId]);
    }

}
