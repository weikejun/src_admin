<?php

class User extends Base_User{
    public static function getCurrentUser(){
        global $IS_DEBUG;
        $user=new self();

        // Just for test
        #return $user->addWhere('name','caoqing')->select();
        // 该cookie暂时用来测试用
        if($IS_DEBUG && $_COOKIE  && $_COOKIE['PHPSESSID'] == '32cc2b0e680815a34810fa6ec31dbd68'){
        #if(false){
            $user=$user->addWhere("name","liuyuan")->select();
            return $user;
        }
        
        if($IS_DEBUG&&php_sapi_name()=='cli'){
            return $user->addWhere("name","wp")->select();
        }
        if(!isset($_SESSION['user']) || !isset($_SESSION['user'])){
            return false;
        }
        $userData=$_SESSION['user'];

        //直接根据pk取数据（兼容第三方登陆进来的用户）
        if(!empty($userData['id'])){
            return $user->addWhere('id',$userData['id'])->select();
        }

        return $user->addWhere("phone",$userData['phone'])->select();
    }

    /**
     * get userInfo by userId
     * @param $userId
     * @return array
     */
    public function getUserInfo($userId){
        if(empty($userId)){
            return null;
        }else{
            $ret = $this->addWhere('id',$userId)->select();
            if(!empty($ret)){
                $ret = $ret->getData();
            }
            return $ret;
        }
    }

    /**
     * get user list by id list
     * @param $userIdList
     * @return array
     */
    public function getUserListByIdList($userIdList){
        if(count($userIdList) <=0){
            return null;
        }else{
            $userList = $this->addWhere('id',$userIdList,'in')->find();
            $userList = array_map(function($user){
                $user = $user->getData();
                return $user;
            },$userList);
            return $userList;
        }
    }
}
