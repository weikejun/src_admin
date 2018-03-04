<?php
class CouponController extends AppBaseController{

    public static $black_lives = [458, 757, 758, 759];

    public function __construct() {
       $this->addInterceptor(new LoginInterceptor());        
    }

    public function availableAction() {
        $pageId = $this->_GET('pageId', 1);
        $count = $this->_GET('count', 10);
        $order_id = $this->_GET('order_id','','00001');
        $order = new Order();
        $order = $order->addWhere('user_id',User::getCurrentUser()->mId)->addWhere("id",$order_id)->select();
        if (!$order) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order error'],'00001')];
        }
        if ($order->mSumPrice<=0 || empty($order->mPrePaymentId)) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["coupons"=>[]])];
        }
        //增加live_id黑名单制定的live 不能用代金券
        if (in_array($order->mLiveId, self::$black_lives)) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["coupons"=>[]])];
        }
        //清除订单id 和代金券id的关联
        if ($order->mStatus!='payed') {
            $order->mCouponId = 0;
            $order->save();
        }
        //计算订单可用的代金券
        $couponsArr = Coupon::getCouponListByUid(User::getCurrentUser()->mId, 'nouse');
        $availableArr = [];
        foreach ($couponsArr as $coupon) {
            //代金券已经被其他订单占用,需要被踢掉.
            $order1 = new Order();
            $order1 = $order1->addWhere("coupon_id", $coupon['coupon_id'])->select();
            if ($order1) {
                continue;
            }
            //scene=1专属的情况暂时没有
            if (($coupon['scene'] == 0 || ($coupon['scene'] == 2 && $order->mSumPrice>=$coupon['low_price'])) && $coupon['expire_time']>time()) {
                $availableArr[] = $coupon;
            }
        }
        $allCount = count($availableArr);
        $pageInfo=$this->_PAGE($allCount, $pageId, $count);
        $data = array_slice($availableArr, ($pageId-1)*$count, $count);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["coupons"=>$data, "pageInfo"=>$pageInfo])];
    } 

    public function listAction() {
        $pageId = $this->_GET('pageId', 1);
        $count = $this->_GET('count', 10);
        $status = $this->_GET('status', 'nouse');
        if (!in_array($status,['nouse','used','expire'])) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'status error'],'00001')];
        }
        $coupon = new Coupon();
        $coupon->setAutoClear(false);
        $coupon = $coupon->addWhere("user_id", User::getCurrentUser()->mId)->addWhere("status", $status);
        $allCount = $coupon->count();
        $pageInfo=$this->_PAGE($allCount, $pageId, $count);
        $listObj=$coupon->limit(($pageId-1)*$count, $count)->find();
        $data = [];
        foreach ($listObj as $obj) {
            $tmp = $obj->getData();
            $tmp['color'] = Coupon::color($tmp['value']);
            $data []= $tmp;
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["coupons"=>$data, "pageInfo"=>$pageInfo])];
    }

    public function addAction() {
        $couponId = $this->_POST('coupon_id','','00001');
        $couponInfo = Coupon::getCouponInfo($couponId);
        if (empty($couponInfo) || $couponInfo['status']!='unclaimed' || $couponInfo['expire_time'] < time()) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'coupon error'], '32001')];
        }
        Coupon::add(User::getCurrentUser()->mId, $couponId);
        return ['json:',AppUtils::returnValue(['msg'=>'代金券添加成功'])];
    }

}
