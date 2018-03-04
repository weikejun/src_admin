<?php

class AlipayController extends BaseController{
    public function indexAction(){
        Alipay::pay('123403','0.01');
    }
}
