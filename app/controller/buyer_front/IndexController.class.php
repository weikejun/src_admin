<?php
class IndexController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new BuyerLoginInterceptor());
    }
    public function loginAction(){
        return ['buyer/login.tpl'];
    }
    public function indexAction(){
        return ['buyer/index.tpl'];
    }
    
}
