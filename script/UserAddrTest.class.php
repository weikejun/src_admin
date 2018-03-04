<?php
require_once('BaseTestCase.class.php');
class UserAddrTest extends BaseTestCase{
    public function setUp(){
        $userAddr=new UserAddr();
        $this->userAddrId=$userAddr->insert(['name'=>'wp1','addr'=>'wp\'s home','user_id'=>User::getCurrentUser()->mId]);
        $this->userAddrId=$userAddr->insert(['name'=>'wp2','addr'=>'wp\'s home','user_id'=>User::getCurrentUser()->mId]);
    }
    public function testAdd(){
        $userAddr=new UserAddrController();
        $_POST['country']='中国';
        $_POST['province']='北京';
        $_POST['city']='北京';
        $_POST['addr']='北京市海淀区罗庄东里小区三号楼7单元501';
        $_POST['postcode']='100080';
        $_POST['name']='王芃';
        $_POST['phone']='18610455401';
        $_POST['cellphone']='18610455401';
        $_POST['email']='wwwppp0801@gmail.com';
        $ret=$userAddr->addAction();
        $this->assertEquals(0,$ret[1]['errno']);
        $this->assertTrue($ret[1]['rst']['id']>0);
    }
    public function tearDown(){
        $userAddr=new UserAddr();
        $userAddr->delete(true);
    }
    public function testUpdate(){
        $userAddrController=new UserAddrController();
        $_POST['country']='中国';
        $_POST['id']=$this->userAddrId;
        $userAddrController->updateAction();
        $this->assertEquals(0,$ret[1]['errno']);
        $userAddr=new UserAddr();
        $userAddr->addWhere("id",$this->userAddrId)->select();
        $this->assertEquals('中国',$userAddr->mCountry);
    }
    public function testDel(){
        $userAddrController=new UserAddrController();
        $_POST['id']=$this->userAddrId;
        $userAddrController->delAction();
        var_dump(DB::getLastQuery());
        $this->assertEquals(0,$ret[1]['errno']);
        
        $userAddr=new UserAddr();
        $userAddr->addWhere("id",$this->userAddrId)->select();
        $this->assertEquals('invalid',$userAddr->mValid);
    
    }
    public function testList(){
        $userAddrController=new UserAddrController();
        $ret=$userAddrController->listAction();
        $this->assertEquals(0,$ret[1]['errno']);
        $this->assertEquals(2,count($ret[1]['rst']['addrs']));
        
    }

}
