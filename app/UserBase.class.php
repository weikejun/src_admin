<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-12-18
 * Time: 下午4:44
 * Aimed: the foundation of user system for TaoShiJie.com
 */
class UserBase {

    private static $instance = null;
    public static function getInstance(){
        if(empty($instance)){
            self::$instance = new self();
            return self::$instance;
        }else{
            return self::$instance;
        }
    }

    const USER = 1;

    const BUYER = 2;

    const ADMIN = 3;

    /**
     * get the baseInfo of User，Buyer，Admin(exclude)
     * @param $userId
     * @param $userType
     * @return array
     */
    public function getBaseUserInfo($userId,$userType){
        switch($userType){
            case self::BUYER:
                return (Buyer::getBuyerInfo($userId));
            case self::USER:
                return (new User())->getUserInfo($userId);
            default:
                return null;
        }
    }
}