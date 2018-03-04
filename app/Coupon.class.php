<?php
class Coupon extends Base_Coupon {

    public static $loginRandValue = [5, 10, 20];
    public static $purchaseValue = [149=>20, 399=>50, 999=>200];

    public static function getAllStatus(){
        return [
            ['unclaimed','未认领'],
            ['nouse','未使用'],
            ['used','已使用'],
            ['expire','已过期']
        ];
    }

    /**
     * 返回指定代金券的详情
     * @param int $total 生成总数
     * @param int $scene
     * @param int $low_price
     * @param int $expireTime
     * return boolen
    */
    public static function createCoupon($param) {
        if (empty($param['total']) || empty($param['name']) || empty($param['value'])) {
            return false;
        }
        $i = 0;
        $time  = time();
        $couponIds = [];
        while($i < $param['total']) {
            $coupon = new self();
            $couponId = rand(100000,999999).substr(time(),-2,2).rand(1000,9999);
            $couponIds[] = $couponId;
            $coupon->mCouponId = $couponId;
            $coupon->mName = trim($param['name']);
            $coupon->mValue = $param['value'];
            $coupon->mLowPrice = $param['low_price'];
            if ($param['low_price']==0 && $param['live_id']==0) {
                $scene = 0;
            } elseif($param['low_price']!=0 && $param['live_id']==0) {
                $scene = 2;
            } else {
                $scene = 1;
            }
            $coupon->mScene = $scene;
            $coupon->mLiveId = !empty($param['live_id'])?$param['live_id']:0;
            $desc = trim($param['desc']);
            $coupon->mDesc = !empty($desc)?trim($param['desc']):'';
            $coupon->mStatus = !empty($param['status'])?$param['status']:'unclaimed';
            $coupon->mUserId = !empty($param['user_id'])?$param['user_id']:0;
            $coupon->mCreateTime = $time;
            $coupon->mUpdateTime = $time;
            $coupon->mExpireTime = !empty($param['expireTime'])?$param['expireTime']: 1893456000;
            $coupon->mSource = !empty($param['source'])?$param['source']:'';
            $ret = $coupon->save();
            $i++;
        }
        return ['success'=>true,'data'=>$couponIds];
    }

    //登录送代金券
    public static function loginLottery() {
        if (time()<1419264000 || time()>1419523200) {
            return false;
        }
        $coupon = new self();
        $coupon = $coupon->addWhere("user_id", User::getCurrentUser()->mId)->addWhere("source","shengdan")->select();
        if ($coupon) {//登录已经送过了
            return false;
        }
        $key = rand()%2;
        $value = self::$loginRandValue[$key];
        $name = $value.'元代金券';
        $data['total'] = 1;
        $data['name'] = $name;
        $data['value'] = $value;
        $data['low_price'] = 0;
        $data['live_id'] = 0;
        $data['desc'] = '淘世界全场通用，不限订单金额，（秒杀、优惠活动除外）';
        $data['status'] = 'nouse';
        $data['user_id'] = User::getCurrentUser()->mId;
        $data['expireTime'] = time()+30*86400;
        $data['source'] = 'shengdan';
        self::createCoupon($data);
        return true;
    }

    /**
     * 返回指定代金券的详情
     * @param $couponId
     * return  array
    */
    public static function getCouponInfo($couponId) {
        if (empty($couponId)) {
            return [];
        }
        $coupon = new self();
        $coupon = $coupon->addWhere("coupon_id", $couponId)->select();
        if (!$coupon) return [];
        return $coupon->getData();
    }

    /**
     * 返回用户指定状态的代金券数量
     * @param $userId
     * @param $status
     * return int
    */
    public static function getCouponNumByUid($userId, $status='nouse') {
        $coupon = new self();
        return $coupon->addWhere("user_id", $userId)->addWhere("status", $status)->count();
    }

    /**
     * 返回用户指定状态的代金券列表
     * @param $userId
     * @param $status
     * return array
    */
    public static function getCouponListByUid($userId, $status) {
        $coupon = new self();
        $listObj =  $coupon->addWhere("user_id", $userId)->addWhere("status", $status)->orderBy('expire_time','ASC')->find();
        $output = [];
        foreach ($listObj as $obj) {
            $tmp = $obj->getData();
            $tmp['color'] = self::color($tmp['value']);
            $output[] = $tmp;
        }
        return $output;
    }

    public static function add($userId, $couponId) {
        $coupon = new self();
        $coupon = $coupon->addWhere("coupon_id", $couponId)->select();
        $coupon->mUserId = $userId;
        $coupon->mStatus = 'nouse';
        $coupon->mUpdateTime = time();
        $coupon->save();
        error_log(date("Y-m-d H:i:s",time()).' userId:'.$userId.' couponId:'.$couponId."\n",3,'/var/log/aimei/coupon_'.date("Y-m-d").".log");
        return true;
    }

    //检查代金券有效性
    public static function checkCouponValid($orderId, $couponId) {
        $output['errNo'] = '0';
        $output['errStatus'] = false;
        //代金券不属于当前用户
        $coupon = new self();
        $coupon = $coupon->addWhere("coupon_id", $couponId)->addWhere("user_id",User::getCurrentUser()->mId)->select();
        if (!$coupon) {
            $output['errNo'] = '32004';
            return $output;
        }
        //判断代金券本身状态是否有效
        if ($coupon->mStatus!='nouse' || $coupon->mExpireTime<time()) {
            $output['errNo'] = '32001';
            return $output;
        }
        $order = new Order();
        //代金券维度判断 代金券已经被其他订单占用
        $order = $order->addWhere("coupon_id", $couponId)->select();
        if ($order && $order->mId!=$orderId) {//代金券被重复使用
            $order->mCouponId = 0;
            $order->save();
        }
        $output['errStatus'] = true;
        $output['obj'] = $coupon;
        return $output;
    }

    //修改代金券状态
    public static function changeCouponStatus($couponId, $status) {
        $coupon = new self();
        $coupon =$coupon->addWhere("coupon_id", $couponId)->select();
        $coupon->mStatus = $status;
        $coupon->mUpdateTime = time();
        if(!$coupon->save()){
            return false;
        }
        return true;
    }

    /**
     * 根据couponId列表返回数据
     * @param $couponIdList
     * @return null
     */
    public function getListByCouponIdList($couponIdList){
        if(count($couponIdList) <= 0){
            return null;
        }else{
            $couponList = $this->addWhere('coupon_id',$couponIdList,'in')->find();
            $couponList = array_map(function($coupon){
                return $coupon->getData();
            },$couponList);
            return $couponList;
        }
    }

    /**
     * 将优惠券转换为描述
     * @param $coupon
     * @return array
     */
    public static function couponUtils($coupon){
        $desc = $coupon['name'];
        if($coupon['low_price'] > 0){
            $detail = "满".$coupon['low_price']."减".$coupon['value']."元";
        }else{
            $detail = $coupon['value']."元代金券";
        }
        return array(
            'desc' => $desc,
            'detail' => $detail,
            'cut' => $coupon['value'],
            'couponId' => $coupon['id'],
        );
    }
//===================3.0 start===================

    //根据订单总价计算可用的代金券
    public static function getAvailable($totalPrice) {
        $couponsArr = self::getCouponListByUid(User::getCurrentUser()->mId, 'nouse');
        $availableArr = [];
        foreach ($couponsArr as $coupon) {
            //代金券是否被payorder占用
            $payOrder = new PayOrder();
            $payOrder = $payOrder->addWhere("coupon_id", $coupon['coupon_id'])->select();
            if ($payOrder) continue;

            //代金券已经被order占用,需要被踢掉.
            $order = new Order();
            $order = $order->addWhere("coupon_id", $coupon['coupon_id'])->select();
            if ($order) continue;
            
            //scene=1专属的情况暂时没有
            if (($coupon['scene'] == 0 || ($coupon['scene'] == 2 && $totalPrice>=$coupon['low_price'])) && $coupon['expire_time']>time()) {
                $availableArr[] = $coupon;
            }
        }
        return $availableArr;
    }

    //检查代金券有效性3.0之后使用这个方法
    public static function checkCouponValidNew($couponId) {
        $output['errNo'] = '0';
        $output['errStatus'] = false;
        //代金券不属于当前用户
        $coupon = new self();
        $coupon = $coupon->addWhere("coupon_id", $couponId)->addWhere("user_id", User::getCurrentUser()->mId)->select();
        if (!$coupon) {
            $output['errNo'] = '32004';
            return $output;
        }
        //判断代金券本身状态是否有效
        if ($coupon->mStatus!='nouse' || $coupon->mExpireTime<time()) {
            $output['errNo'] = '32001';
            return $output;
        }

        //代金券是否被payorder占用
        $payOrder = new PayOrder();
        $payOrder = $payOrder->addWhere("coupon_id", $couponId)->select();
        if ($payOrder) {
            $output['errNo'] = '32005';
            return $output;
        }

        //代金券是否被order占用,等到用户全部升级到3.0的时候就不用这个了
        $order = new Order();
        $order = $order->addWhere("coupon_id", $couponId)->select();
        if ($order) {
            $output['errNo'] = '32005';
            return $output;
        }
        $output['errStatus'] = true;
        $output['obj'] = $coupon;
        return $output;
    }

    /**
     * 颜色转换的函数
     * @param $value
     * @return string
     */
    public static function color($value){
        if( $value < 20 && $value >= 1){
            return "ffbb02";
        }else if($value >= 20 && $value < 50){
            return "ced702";
        }else if($value >= 50 && $value < 200){
            return "ff5d8c";
        }else if($value >= 200 && $value < 500){
            return "61e0b7";
        }else if($value >= 500){
            return "c3597d";
        }
        return "";
    }

    /**
     * 重新发放现金券
     * @param $couponId
     * @return array
     */
    public static function resendCoupon($couponId){
        if(empty($couponId)){
            return false;
        }else{
            $couponParam = self::getCouponInfo($couponId);
            if(!empty($couponParam)){
                $createParam = array(
                    'total' => 1,
                    'name'  => $couponParam['name'],
                    'value' => $couponParam['value'],
                    'low_price' => $couponParam['low_price'],
                    'live_id' => $couponParam['live_id'],
                    'desc' => $couponParam['desc'],
                    'status' => $couponParam['status'],
                    'user_id' => $couponParam['user_id'],
                    'expireTime' => $couponParam['expire_time'],
                    'source' => $couponParam['source'],
                );
                return self::createCoupon($createParam);
            }
        }
    }
}
