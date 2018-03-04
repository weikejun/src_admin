<?php
$buyer=new Buyer();
$salt_user='sdejks@#%677&^c5';
$salt_pass='@#fffy%6f7&^c82_';
foreach($buyer->iterator() as $_buyer){
    //$_buyer->mName;
    if($_buyer->mEasemobUsername){
        continue;
    }
    $_buyer->mEasemobUsername="buyer_".md5($salt_user.$_buyer->mName.rand(100,999));
    $_buyer->mEasemobPassword=md5($salt_pass.$_buyer->mPassword);
    $res=Easemob::getInstance()->createUser($_buyer->mEasemobUsername,$_buyer->mEasemobPassword);
    if(!$res||isset($res['error_description'])){
        var_dump($res);
        break;
    }
    $_buyer->save();
    echo "buyer {$_buyer->mName} ok\n";
}

$salt_user='sdejks@*d7sdd#c5';
$salt_pass='@#fffyphsdsdw82_';
$user=new User();
foreach($user->iterator() as $_user){
    //$_user->mName;
    if($_user->mEasemobUsername){
        continue;
    }
    $_user->mEasemobUsername="user_".md5($salt_user.$_user->mPhone.rand(100,999));
    $_user->mEasemobPassword=md5($salt_pass.$_user->mPassword);
    $res=Easemob::getInstance()->createUser($_user->mEasemobUsername,$_user->mEasemobPassword);
    if(!$res||isset($res['error_description'])){
        var_dump($_user->getData(),$res);
        break;
    }
    $_user->save();
    echo "user {$_user->mName} ok\n";
}

/*
$token=Easemob::getInstance()->token();
var_dump($token);

$res=Easemob::getInstance()->createUser('test','test');
var_dump($res);
$res=Easemob::getInstance()->deleteUser('test');
var_dump($res);
 */

/*
array(10) {
  ["action"]=>
  string(4) "post"
  ["application"]=>
  string(36) "4d47e100-08fa-11e4-80b8-710cb0e62caf"
  ["params"]=>
  array(0) {
  }
  ["path"]=>
  string(6) "/users"
  ["uri"]=>
  string(46) "http://a1.easemob.com/wwwppp0801/sandbox/users"
  ["entities"]=>
  array(1) {
    [0]=>
    array(6) {
      ["uuid"]=>
      string(36) "cc49690a-090f-11e4-a1df-1daed43b04ed"
      ["type"]=>
      string(4) "user"
      ["created"]=>
      int(1405092418448)
      ["modified"]=>
      int(1405092418448)
      ["username"]=>
      string(4) "test"
      ["activated"]=>
      bool(true)
    }
  }
  ["timestamp"]=>
  int(1405092418446)
  ["duration"]=>
  int(109)
  ["organization"]=>
  string(10) "wwwppp0801"
  ["applicationName"]=>
  string(7) "sandbox"
}*/
