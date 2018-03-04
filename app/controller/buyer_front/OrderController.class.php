<?php
class OrderController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new BuyerLoginInterceptor());
    }
    
    
    /*
    public function createAction(){
        return ['buyer/order/create.tpl'];
    }*/

    public function buyListAction(){
        return ['buyer/order/buyList.tpl'];
    }

    public function waitpackListAction(){
        //$id=$this->_GET("id","",99999);
        return ['buyer/order/waitpackList.tpl'];
    }
    
}


