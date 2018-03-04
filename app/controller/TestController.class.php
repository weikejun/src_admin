<?php

class TestController extends BaseController{
        public function __construct(){
                $this->addInterceptor(new DBTransactionInterceptor('indexAction'));
        }
        public function indexAction(){
            phpinfo();
            return;
            echo SMS_SN;
            $amount=new StockAmount();
            $amount=$amount->addWhere('id',1)->select();
            $amount->mAmount+=1;
            $amount->save();
            $amount2=new StockAmount();
            $amount2=$amount2->addWhere('id',2)->select();
            $amount2->mAmount+=1;
            $amount2->save();
            throw new ModelAndViewException("db error",1,"text:{$amount->mAmount}");
            return ["text:{$amount->mAmount}"];
        }
}
