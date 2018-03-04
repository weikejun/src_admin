<?php

require_once('BaseTestCase.class.php');
class LiveTest extends BaseTestCase{
    public function setUp(){
        $buyer=new Buyer();
        $this->buyer_id=$buyer->insert([
            'name'=>'wp',
            'country'=>'中国',
            'province'=>'北京',
            'city'=>'北京',
            'address'=>'beijing zhi chun lu',
            'phone'=>'18610455401',
            'create_time'=>time(),
            'update_time'=>time(),
            ]);
        $live=new Live();
        $this->live_id=$live->insert([
            'name'=>'first live',
            'buyer_id'=>$this->buyer_id,
            'start_time'=>time(),
            'end_time'=>time()+10000,
            'create_time'=>time(),
            'update_time'=>time(),
            'status'=>'active',
            ]);
    }
    public function tearDown(){
        $live=new Live();
        $live->delete(true);
        $buyer=new Buyer();
        $buyer->delete(true);
    }
    public function testShow(){
        $liveController=new LiveController();
        $_GET['id']=$this->live_id;
        $ret=$liveController->showAction();
        $this->assertEquals(0,$ret[1]['errno']);
        $this->assertEquals('first live',$ret[1]['rst']['name']);
    }
    public function testList(){
        $liveController=new LiveController();
        $ret=$liveController->listAction();
        $this->assertEquals(0,$ret[1]['errno']);
        $this->assertEquals(1,count($ret[1]['rst']['lives']));
    }
}
