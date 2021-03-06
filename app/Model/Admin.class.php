<?php
class Model_Admin extends Base_Admin{
    public static function getCurrentAdmin(){
        global $IS_DEBUG;
        $admin=new self();

        // Just for test
        #return $admin->addWhere('name','cq')->select();

        if($IS_DEBUG&&php_sapi_name()=='cli'){
            return $admin->addWhere("name","wp")->select();
        }
        if(!isset($_SESSION['admin'])){
            return false;
        }
        $adminData=$_SESSION['admin'];
        if (!is_array($adminData)) {
            $adminData = $adminData->getData();
        }
        $admin->clear()->setData($adminData);
        return $admin;
        //return $user->addWhere("id",$id)->select();
    }

    public static function getNameById($id) {
        $admin = new self;
        $admin->mId = $id;
        $admin->select();
        return $admin->mName;
    }
}
