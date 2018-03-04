<?php
require_once("BaseTestCase.class.php");
class UserTest extends BaseTestCase{
    public function setUp(){
        $user=new User();
        $ret=$user->addWhere("username",null)->delete();
        $securecode=new Securecode();
        $ret=$securecode->delete(true);
    }
    public function testLogin(){
        $user=new UserController();
        $_POST['phone']='18610455401';
        $_POST['password']='wp';
        $ret=$user->loginAction();
        $this->assertEquals(0,$ret[1]['errno']);
        $this->assertEquals('18610455401',$ret[1]['rst']['phone']);
    }
    public function testRegister(){
        $user=new UserController();
        $_POST['phone']='18610455403';
        $_POST['password']='wp123456';
        $ret=$user->registerAction();
        $this->assertEquals(0,$ret[1]['errno']);
    }
    public function testResend(){
        $user=new UserController();
        $user->resendPhoneVerifyAction();
        $this->assertEquals(0,$ret[1]['errno']);
    }
    public function testConfirmRegister(){
        $user=new UserController();
        $code=UserVerify::sendPhoneVerify(User::getCurrentUser());
        $_POST['verify_code']=$code;
        $user->confirmRegisterVerifyAction();
        $this->assertEquals(0,$ret[1]['errno']);
    }
    public function testChangePhone(){
        $user=new UserController();
        $_POST['password']='wp';
        $_POST['new_phone']='18610455403';
        $ret=$user->changePhoneAction();
        $this->assertEquals(0,$ret[1]['errno']);
        
        $code=UserVerify::sendPhoneVerify(User::getCurrentUser());
        $_POST['verify_code']=$code;
        $user->confirmChangePhoneAction();
        $this->assertEquals(0,$ret[1]['errno']);
        ////clear!!
        $user=new User();
        $user=$user->addWhere('phone',"18610455403")->select();
        $this->assertTrue(!!$user);
        $user->mPhone='18610455401';
        $user->save();
    }
    public function testUpdatePassword(){
        $user=new UserController();
        $_POST['password']='wp';
        $_POST['new_password']='newpass1';
        $ret=$user->updatePasswordAction();
        $this->assertEquals(0,$ret[1]['errno']);
        
        
        $user=new User();
        $user=$user->addWhere('username',"wp")->select();
        $this->assertTrue(!!$user);
        $this->assertEquals($user->mPassword,md5("newpass1"));
        $user->mPassword=md5('wp');
        $user->save();
    }

    public function testUpdate(){
        $user=new UserController();
        $_POST['address']='address';
        $ret=$user->updateAction();
        $this->assertEquals(0,$ret[1]['errno']);
    }
}
