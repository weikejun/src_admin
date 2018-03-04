<?php
class IndexController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        #$this->addInterceptor(new BuyerLoginInterceptor());
    }
    public function indexAction(){
        return ['user/user.tpl'];
    }
}
