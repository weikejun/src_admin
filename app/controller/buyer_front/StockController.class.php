<?php
class StockController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new BuyerLoginInterceptor());
    }
    
    
    public function createAction(){
        return ['buyer/stock/create.tpl'];
    }
    public function listAction(){
        return ['buyer/stock/list.tpl'];
    }

    public function updateAction(){
        //$id=$this->_GET("id","",99999);
        return ['buyer/stock/update.tpl'];
    }
    
}

