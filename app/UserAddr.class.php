<?php
class UserAddr extends Base_User_Addr{

    public function getDefaultAddr($userId){
        if(empty($userId)){
            return null;
        }else{
            //1. 看看用户的默认地址是否有效
            $ret = $this->addWhere('user_id',$userId)->addWhere('first_choice',1)->addWhere('valid','valid')->select();
            if(empty($ret)){
                $user_addr = $this->addWhere('user_id', User::getCurrentUser()->mId)->orderBy('create_time', 'desc')->addWhere('valid','valid')->select();
                if(!empty($user_addr)){
                    $user_addr = $user_addr->getData();
                }
                return $user_addr;
            }
            return $ret->getData();
        }
    }
}
