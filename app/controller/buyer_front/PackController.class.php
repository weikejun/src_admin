<?php
class PackController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new BuyerLoginInterceptor());
    }
    
    public function listAction(){
        return ['buyer/pack/list.tpl'];
    }
    
}

