<?php
class LiveController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new BuyerLoginInterceptor());
    }
    
    
    public function createAction(){
        return ['buyer/live/create.tpl'];
    }
    public function updateAction(){
        return ['buyer/live/update.tpl'];
    }

    
}
