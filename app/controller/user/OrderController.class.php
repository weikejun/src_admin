<?php
class OrderController extends AppBaseController{

    private static $stockInfo;
    private static $buyerInfo;
    private static $order_timeout = 10;

    public function __construct(){
        $this->addInterceptor(new LoginInterceptor());        
    }
    use WxPay;

    public function myAction(){
        $pageId=$this->_GET('pageId',1);
        $count=$this->_GET('count',100);
        $status = trim($this->_GET('status', ''));
        $order=new Order();
        $order->setAutoClear(false);
        $order=$order
            ->addWhere('user_id',User::getCurrentUser()->mId)
            ->orderBy("create_time","desc");
        if(!empty($status)) {
            $status = explode(',', $status);
            $order->addWhere('status', $status, 'IN');
        }
        $allCount=$order->count();
        $pageInfo=$this->_PAGE($allCount,$pageId,$count);
        $orders=$order->limit(($pageId-1)*$count,$count)->find();
        $orders = Order::genOrderDetail($orders);
        self::getBuyerName($orders);

        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["orders"=>$orders,"pageInfo"=>$pageInfo])];
    }
    private static function getBuyerName(&$orders){
        if(empty($orders)) {
            return;
        }
        $live_ids=array_values(array_unique(array_map(
            function($order){return $order['live_id'];},
            $orders)));
        $live=new Live();
        $livemap=$live->addWhere('id',$live_ids,"in")->findMap();
        $buyer_ids=array_values(array_unique(array_map(function($live){
            return $live->mBuyerId;
        },$livemap)));
        $buyer=new Buyer();
        $buyermap=$buyer->addWhere("id",$buyer_ids,"in")->findMap();
        foreach($orders as &$order){
            $buyer_name="unkown";
            try{
                $buyer_id=$livemap[$order['live_id']]->mBuyerId;
                $buyer_name=$buyermap[$buyer_id]->mName;
            }catch(Exception $e){}
            $order['buyer_name']=$buyer_name;
        }
    }

    public function addAction(){
        $stock_amount_id=$this->_POST("stock_amount_id","",'20014');
        $user_addr_id=$this->_POST("user_addr_id","",'12001');
        $note=$this->_POST("note","");
        $num=intval($this->_POST("num",1));
        $num=$num?$num:1;

        $stock_amount=new StockAmount();
        $stock_amount=$stock_amount->addWhere('id',$stock_amount_id)->select();
        if(!$stock_amount){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'stock amount error'],'20014')];
        }

        $stock=new Stock();
        $stock=$stock->addWhere('id',$stock_amount->mStockId)->select();
        if(!$stock){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'stock error'],'20015')];
        }

        $user_addr=new UserAddr();
        $user_addr=$user_addr->addWhere('id',$user_addr_id)->select();
        if(!$user_addr){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'user addr error'],'12001')];
        }

        //////lock amount/////////////
        $stock_amount_tbl=new DBTable('stock_amount');
        $res=$stock_amount_tbl->addWhere('id',$stock_amount_id)->addWhere('amount',"`locked_amount`+`sold_amount`+$num",'>=',"and",DBTable::NO_ESCAPE)->update(['locked_amount'=>["`locked_amount`+$num",DBTable::NO_ESCAPE]]);
        if(!$res){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'amount not enough'],'20002')];
        }
        ////////////////////////////

        $live=new Live();
        $live=$live->addWhere('id', $stock->mLiveId)->select();

        if(!$live){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'live error'],'13001')];
        }elseif(time() < $live->mStartTime) { // 直播未开始
            $res=$stock_amount_tbl->addWhere('id',$stock_amount_id)->update(['locked_amount'=>["`locked_amount`-$num",DBTable::NO_ESCAPE]]);
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'live not start'],'14001')];
        }elseif($live->mBuyerId == 2018) { // 新用户专享
            $user = new User;
            $user = $user->addWhere('id', User::getCurrentUser()->mId)->select();
            if($user->mCreateTime < time() - 86400) {
                $res=$stock_amount_tbl->addWhere('id',$stock_amount_id)->update(['locked_amount'=>["`locked_amount`-$num",DBTable::NO_ESCAPE]]);
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'live not start'],'14002')];
            }
        } elseif(in_array($live->mId, [1071, 1072])) { // 圣诞活动
            $order = new Order();
            $orders = $order->addWhere('user_id', User::getCurrentUser()->mId)->addWhere('live_id', $live->mId)->find();
            if ($orders) {
                foreach ($orders as $order) {
                    if ($order->mStatus != 'canceled') {
                        $res = $stock_amount_tbl->addWhere('id',$stock_amount_id)->update(['locked_amount'=>["`locked_amount`-$num",DBTable::NO_ESCAPE]]);
                        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'live error'],'14003')];
                    }
                }
            }
        }

        $time = time(); 
        //写pay_order
        $payOrderInfo['amount'] = $num * $stock->mPriceout;
        $payOrderInfo['couponId'] = '';
        $payOrderInfo['vid'] = 0;
        //$payOrderInfo['pay_type'] = PREPAY_RATIO!=1 ? 0 : 1;
        $payOrderInfo['pay_type'] = GlobalMethod::ALL_PAY_SWITCH? Order::ALL_PAY:Order::PRE_PAY;;
        $payOrderInfo['time'] = $time;  
        $payOrder = PayOrder::addPayOrder($payOrderInfo);
        GlobalMethod::orderLog($payOrder, '', 'user', User::getCurrentUser()->mId, 1);
        
        $order=new Order();
        $order->setData([
            'status'=>'wait_prepay',
            'user_id'=>User::getCurrentUser()->mId,
            'live_id'=>$stock->mLiveId,
            'buyer_id'=>$live->mBuyerId,
            'stock_id'=>$stock->mId,
            'stock_amount_id'=>$stock_amount->mId,
            'num'=>$num,
            'sum_price'=>$num*$stock->mPriceout,
            'create_time'=>$time,
            'update_time'=>$time,
            'note'=>$note,
            'user_addr_id'=>$user_addr_id,
            'country' => $user_addr->mCountry,
            'province' => $user_addr->mProvince,
            'city' => $user_addr->mCity,
            'addr' => $user_addr->mAddr,
            'postcode' => $user_addr->mPostcode,
            'name' => $user_addr->mName,
            'phone' => $user_addr->mPhone,
            'cellphone' => $user_addr->mCellphone,
            'email' => $user_addr->mEmail,
            'source' => $_SERVER['HTTP_USER_AGENT'],
            'vid' => 0,
            'pay_order_id' => $payOrder->mId,
            'pay_type' => GlobalMethod::ALL_PAY_SWITCH? Order::ALL_PAY:Order::PRE_PAY,
            ]);
        $res=$order->save();
        if(!$res){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order save error'],'30021')];
        }
        #$data=$order->getData();
        #$data['priceout_unit']=$stock->mPriceoutUnit;
        #$data['priceout_unit_show']=TableUtils::getUnitShow($stock->mPriceoutUnit);
        $notifyStr = str_replace('%stockName%', $stock ? "“".$stock->mName."”" : "", "您刚拍的%stockName%请在10分钟内支付定金，下手要快哦，不要被别人抢先啦~");
        Notification::sendNotification($order->mUserId,['title'=>$notifyStr,'type'=>'trade','from'=>'trade',
            'data'=>[
                'order_id'=>$order->mId,
                'trade_title'=>$order->statusDesc(),
                'stock_imageUrl'=>$stock->mImgs?json_decode($stock->mImgs,true)[0]:[],
            ]
        ], 0);
        
        GlobalMethod::orderLog($order,'', 'user', User::getCurrentUser()->mId);
        $orders = Order::genOrderDetail([$order]);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["order"=>$orders[0]])];
    }

    public function payAction(){
        $id=$this->_POST("id","",'30010');
        $couponId = $this->_POST("coupon_id");
        if ($couponId) {
            $couponRet = Coupon::checkCouponValid($id, $couponId);
            if (!$couponRet['errStatus']) {
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'coupon invalid'],$couponRet['errNo'])];
            }
        }
        $order=new Order();
        $order=$order->addWhere("id",$id)->select();
        if(!$order || $order->mStatus != 'wait_pay'){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order status error'],'30023')];
        }
        if ($order->mVid == 1) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order status error'],'30026')];
        }
        $prepayment=new Payment();
        $prepayment = $prepayment->addWhere("id",$order->mPrePaymentId)->select();
        $payment=new Payment();
        $payment = $payment->addWhere("id",$order->mPaymentId)->select();
        if($payment->mStatus == 'payed') { // 已支付，防止重复支付
            $order->mPaymentId = $payment->mId;
            $order->mStatus = 'payed';
            $order->mUpdateTime = time();
            if(!$order->save()){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order save error'],'99999')];
            }
            GlobalMethod::orderLog($order, '', 'user', User::getCurrentUser()->mId);
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'订单已被支付，请刷新查看'],'30023')];
        } else {
            $payment=new Payment();
            $payment->mOrderId=$order->mId;
            $payment->mUserId=$order->mUserId;
            $payment->mType='pay';
            $surplus = $amount = $order->mSumPrice - ($prepayment ? $prepayment->mAmount : GlobalMethod::countPrepay($order->mSumPrice,$order->mPayType));
            if ($couponId) {
                $amount = ($surplus - $couponRet['obj']->mValue)>0 ? ($surplus - $couponRet['obj']->mValue) : 0.01;
                $discount = $surplus-$amount;
            }
            $payment->mAmount = $amount;
            $payment->mDiscount = $discount>0 ? $discount : 0;
            $payment->mCreateTime=time();
            $payment->mUpdateTime=time();
            if(!$payment->save()){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'payment save error'],'99999')];
            }
            $payment->mTradeNo = GlobalMethod::genOrderId($payment->mId);
            if(!$payment->save()){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'payment save error'],'99999')];
            }
            $order->mPaymentId = $payment->mId;
            $order->mUpdateTime = time();
            if ($couponId) {
                $order->mCouponId = $couponId;
            }
            if(!$order->save()){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order save error'],'99999')];
            }

            //状态同步到pay_order add by hongjie
            $payOrderInfo['mPaymentId'] = $payment->mId;
            $payOrderInfo['id'] = $order->mPayOrderId;
            PayOrder::updatePayOrder($payOrderInfo);
        }
        $data['payment_id'] = $payment->mId;
        $data['trade_no'] = $payment->mTradeNo;
        $data['num'] = $order->mNum;
        //$data['price'] = $order->mSumPrice;
        // TODO: 确保线上无bug，未来新版本覆盖率提升之后取消
        $data['price'] = $payment->mAmount;
        $data['prepay'] = $payment->mAmount;
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($data)];
    }

    public function prepayAction(){
        $id=$this->_POST("id","",'30010');
        $order=new Order();
        $order=$order->addWhere("id",$id)->select();
        if(!$order || $order->mStatus != 'wait_prepay'){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order status error'],'30023')];
        }
        if ($order->mVid == 1) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order status error'],'30026')];
        }
        $payment=new Payment();
        $payment = $payment->addWhere("id",$order->mPrePaymentId)->select();

        if($payment->mStatus == 'payed') { // 已支付，防止重复支付
            $order->mPrePaymentId = $payment->mId;
            $order->mStatus = 'prepayed';
            $order->mUpdateTime = time();
            if(!$order->save()){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order save error'],'99999')];
            }
            GlobalMethod::orderLog($order, '', 'user', User::getCurrentUser()->mId);
            //状态同步到pay_order add by hongjie
            $payOrderInfo['mStatus'] = 'prepayed';
            $payOrderInfo['id'] = $order->mPayOrderId;
            $payOrder = PayOrder::updatePayOrder($payOrderInfo); 
            if ($payOrder) {
                GlobalMethod::orderLog($payOrder, '', 'user', User::getCurrentUser()->mId, 1);
            }
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'订单已被支付，请刷新查看'],'30023')];
        } else {
            $payment=new Payment();
            $payment->mOrderId=$order->mId;
            $payment->mUserId=$order->mUserId;
            $payment->mAmount=GlobalMethod::countPrepay($order->mSumPrice,GlobalMethod::ALL_PAY_SWITCH);
            $payment->mCreateTime=time();
            $payment->mUpdateTime=time();
            $payment->mOrderType = 0;
            if(!$payment->save()){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'payment save error'],'99999')];
            }
            $payment->mTradeNo = GlobalMethod::genOrderId($payment->mId);
            if(!$payment->save()){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'payment save error'],'99999')];
            }
            $order->mPrePaymentId = $payment->mId;
            $order->mPayType = GlobalMethod::ALL_PAY_SWITCH? Order::ALL_PAY:Order::PRE_PAY;
            //$order->mUpdateTime = time();
            if(!$order->save()){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order save error'],'99999')];
            }

            //状态同步到pay_order add by hongjie
            $payOrderInfo['mPrePaymentId'] = $payment->mId;
            $payOrderInfo['id'] = $order->mPayOrderId;
            $payOrderInfo['mPayType'] = GlobalMethod::ALL_PAY_SWITCH? Order::ALL_PAY:Order::PRE_PAY;
            PayOrder::updatePayOrder($payOrderInfo); 
        }
        $data['payment_id'] = $payment->mId;
        $data['trade_no'] = $payment->mTradeNo;
        $data['num'] = $order->mNum;
        $data['price'] = $order->mSumPrice;
        $data['prepay'] = $payment->mAmount;
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($data)];
    }
    #  支付宝
    public function alipayAction(){
        $payment_id=$this->_POST("payment_id","",'99999');
        $callbackUrl = $this->_POST('callback_url', null);
        // test
        #$payment_id = 27;
        $payment=new Payment();
        $payment=$payment->addWhere("id",$payment_id)->select();
        if(!$payment || $payment->mStatus != 'wait_pay'){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'payment\'s stauts error'],'99999')];
        }

        # 云安全验证
        #$userName = User::getCurrentUser()->mName;
        SafeCloudAlipay::checkSafeCloud(User::getCurrentUser(), $payment);

        # 支付
        Alipay::pay($payment->mTradeNo,$payment->mAmount, $callbackUrl);

        # 测试限定0.01
        //Alipay::pay($payment_id,0.01);
    }
    // just for test
    #public function alipayTestAction(){
    #    Alipay::pay(34,0.01);
    #}
    public function callbackAction(){
        #Logger::error("callbackAction");
        Alipay::callback();
        exit();
    }
    public function notifyAction(){
        #Logger::error("notifyAction");
        Alipay::notify();
        exit();
    }
    public function notifyClientAction(){
        #Logger::error("notifyAction");
        Alipay::notify_client();
        exit();
    }
    public function notifyRefundAction() {
        Alipay::notify_refund();
        exit();
    }
    public function alipaySuccessAction(){
        exit();
    }
    public function alipayFailAction(){
        exit();
    }
    public function statusAction(){
        $id=$this->_GET("id",0);
        $order=new Order();
        $order=$order->addWhere('user_id',User::getCurrentUser()->mId)->addWhere("id",$id)->select();
        if(!$order){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order error'],99999)];
        }
        $orders = Order::genOrderDetail([$order]);
        //增加订单用到的代金券相关信息
        $couponInfo = Coupon::getCouponInfo($orders[0]['coupon_id']);
        $orders[0]['coupon_info'] = !empty($couponInfo) ? [$couponInfo] : [];
        $buyer = new Buyer();
        $buyer = $buyer->addWhere("id",$orders[0]['buyer_id'])->select();
        $orders[0]['buyer_name'] = $buyer->mName;
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["order"=>$orders[0]])];
    }
    public function cancelAction(){
        $id=$this->_POST("id","",'30010');
        $log=$_POST["log"] ? $_POST["log"] : '';
        $order=new Order();
        $order=$order->addWhere("id",$id)->select();
        if($order->mStatus != 'wait_prepay'){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order\'s status is wrong'],'30024')];
        }
        $order->mStatus = 'canceled';
        $order->mUpdateTime = time();
        if($order->save()){
            GlobalMethod::orderLog($order,$log, 'user', User::getCurrentUser()->mId);
            //状态同步到pay_order add by hongjie
            $payOrderInfo['mStatus'] = 'canceled';
            $payOrderInfo['id'] = $order->mPayOrderId;
            $payOrder = PayOrder::updatePayOrder($payOrderInfo); 
            if ($payOrder) {
                GlobalMethod::orderLog($payOrder, '', 'user', User::getCurrentUser()->mId, 1);
                //如果有优惠券，则要把优惠券返回用户
                if(!empty($payOrder->mCouponId)){
                    Coupon::resendCoupon($payOrder->mCouponId);
                }
            }
            if(!StockAmount::releaseLockedAmount($order)){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'releaseLockedAmount error'],'20016')];
            }
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'cancel success'])];
        }else{
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'can\'t save order'],'30022')];
        }
    }
    public function successAction(){
        $id=$this->_POST("id","",'30010');
        $order=new Order();
        $order=$order->addWhere("id",$id)->select();
        if(!$order || $order->mStatus != 'to_user'){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order status error'],'30023')];
        }
        $order->mStatus='success';
        $order->mUpdateTime = time();
        if(!$order->save()){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order save error'],'99999')];
        }
        $stock = new Stock();
        $stock = $stock->addWhere('id', $order->mStockId)->select();
        $notifyStr = str_replace('%stockName%', $stock ? "“".$stock->mName."”" : "", "您的%stockName%已经签收，感谢您的支持，加入淘世界VIP（QQ群：319149328）。");
        Notification::sendNotification($order->mUserId,['title'=>$notifyStr,'type'=>'trade','from'=>'trade',
            'data'=>[
                'order_id'=>$order->mId,
                'trade_title'=>$order->statusDesc(),
                'stock_imageUrl'=>$stock->mImgs?json_decode($stock->mImgs,true)[0]:[],
            ]
        ]);
        GlobalMethod::orderLog($order,'', 'user', User::getCurrentUser()->mId); 

        //状态同步到pay_order add by hongjie
        $payOrderInfo['mStatus'] = 'payed';
        $payOrderInfo['id'] = $order->mPayOrderId;
        $payOrder = PayOrder::updatePayOrder($payOrderInfo); 
        if ($payOrder) {
            GlobalMethod::orderLog($payOrder, '', 'user', User::getCurrentUser()->mId, 1);
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["msg"=>"success"])];
    }
    public function showOrderLogAction(){
        $id=$this->_GET("id",0);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["logs"=>GlobalMethod::showOrderLog($id)])];
    }

    /**
     * 用户订单统计
     * @return array
     */
    public function statisticAction(){
        $orderList=(new Order())->getOrderListByUserId(User::getCurrentUser()->mId);
        $canCommentList = (new TradeRate())->canComment($orderList);
        $wait_payed = 0;
        $wait_delivered = 0;
        $wait_received =0;
        $wait_commented = 0;
        foreach($orderList as $order){
           switch($order['status']){
               case 'wait_prepay':
               case 'wait_pay':
                   $wait_payed ++;
                   break;
               case 'payed':
               case 'packed':
                   $wait_delivered++;
                   break;
               case 'prepayed':
                   if($order['pay_type'] == Order::ALL_PAY){
                       $wait_delivered++;
                   }
                   break;
               case 'to_demostic':
               case 'to_user':
                   $wait_received++;
                   break;
               case 'success':
                   if($canCommentList[$order['id']]){
                       $wait_commented++;
                   }
                   break;
               default:
                   break;
           }
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
            "waitToPay"=>$wait_payed,
            "waitToDeliver"=>$wait_delivered,
            "waitToReceive"=>$wait_received,
            "waitToComment"=>$wait_commented,
        ])];
    }

    /**
     * 订单列表
     */
    public function listAction(){
        $status = $this->_GET("status");
        $pageId = $this->_GET("pageId",1);
        $count = $this->_GET("count",20);

        $userId = User::getCurrentUser()->mId;
        $cellList = (new Order())->getOrderListByStatus($userId,$status,$pageId-1,$count);
        $pageInfo = $this->_PAGEV2($pageId,count($cellList),$count);

        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
            "orderList"=>$cellList,
            "pageInfo"=>$pageInfo,
        ])];
    }

    /**
     * 订单详情
     */
    public function detailAction(){
        $orderId = $this->_GET("order_id");

        $cell = (new Order())->detailByOrderId($orderId);
        if(empty($cell)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "msg" => "请输入正确的订单号",
            ],10001)];
        }else{
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "orderDetail" => $cell,
            ])];
        }
    }

    //获取订单的物流跟踪信息（包括国内和国外物流）
    //by boshen@20141209
    public function getOrderLogisticTrackingAction() {
        $order_id = $this->_GET('orderId', 0);
        $user_id = User::getCurrentUser()->mId;
        //set user_id for test by@boshen
        //$user_id = 3;
        $order = new Order();
        $order = $order->addWhere('id', $order_id)->addWhere('user_id', $user_id)->limit(1)->select();
        if(empty($order)) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order empty'],'30003')];
        }

        $data = array();
        $logistic_tracking = new LogisticTracking();

        $logistic = new Logistic();
        $logistic = $logistic->addWhere('order_id', $order->mId)->limit(1)->select();
        if(!empty($logistic)) {
            $trackings = $logistic_tracking->getAllTrackings($logistic->mLogisticNo, $logistic->mLogisticProviderFixed);
            $data['Home'] = array( 'logistic_provider'=>$logistic->mLogisticProvider, 'logistic_no'=>$logistic->mLogisticNo, 'trackings'=>$trackings );
        }

        $pack = new Pack();
        $pack = $pack->addWhere('id', $order->mPackId)->limit(1)->select();
        if(!empty($pack) && !empty($pack->mLogisticNo)) {
            $trackings = $logistic_tracking->getAllTrackings($pack->mLogisticNo, $pack->mLogisticProviderFixed);
            $data['Abroad'] = array( 'logistic_provider'=>$pack->mLogisticProvider, 'logistic_no'=>$pack->mLogisticNo, 'trackings'=>$trackings );
        }
        //var_dump($data); exit;

        return ['json:', AppUtils::returnValue($data, 0)];
    }

    /**
     * 根据payOrderId获取支付id的详细信息,同时获取收银台需要显示的信息与数据
     */
    public function getPayOrderInfoAction(){
        $payOrderId = $this->_GET("pay_order_id");

        if(empty($payOrderId)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "msg" => "支付订单不存在",
            ],30025)];
        }else{
            $payOrderInfo = (new PayOrder())->getPayOrderInfoById($payOrderId);
            if(empty($payOrderInfo)){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    "msg" => "支付订单不存在",
                ],30025)];
            }
            //care: 因为payAction与payNewAction是使用不同的意义的id进行支付，所以这边要兼容两种类型的id，统一处理为id.
            $orderList = (new Order())->getListByPayOrderId($payOrderInfo['id']);
            if($payOrderInfo['pay_type'] == Order::ALL_PAY){
                if($payOrderInfo['status'] == 'wait_prepay'){
                    $payOrderInfo['expire_time_show'] = "下单后请10分钟内付款，超时未支付订单将被自动取消哦。";
                    $payOrderInfo['end_time'] = $payOrderInfo['create_time'] + 10 * 60 - time();
                    $payOrderInfo['pay'] = $payOrderInfo['amount'];
                    if(count($orderList) <= 0){
                        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                            "msg" => "订单异常，不允许被支付"
                        ],30005)];
                    }else{
                        //这边要标注下：因为订单调用payAction进行支付的订单走orderId
                        $order = $orderList[0];
                        $payOrderInfo['detailOrderId'] = $order['id'];
                    }
                }
            }else if($payOrderInfo['pay_type'] == Order::PRE_PAY){
                if($payOrderInfo['status'] == 'wait_pay'){
                    $payOrderInfo['expire_time_show'] = "尾款请在3天内付款，未支付尾款定金将被没收。";
                    $payOrderInfo['end_time'] = $payOrderInfo['create_time'] + 3 * 86400 - time();
                    $payOrderInfo['pay'] = $payOrderInfo['amount'] - GlobalMethod::countPrepay($payOrderInfo['amount'],$payOrderInfo['pay_type']);
                    if(count($orderList)<=0){
                        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                            "msg" => "订单异常，不允许被支付"
                        ],30005)];
                    }else{
                        //这边要标注下：因为订单调用payAction进行支付的订单走orderId
                        $order = $orderList[0];
                        $payOrderInfo['id'] = $order['id'];
                        $payOrderInfo['detailOrderId'] = $order['id'];
                    }
                }else if($payOrderInfo['status'] == 'wait_prepay'){
                    $payOrderInfo['expire_time_show'] = "下单后请10分钟内付款，超时未支付订单将被自动取消哦。";
                    $payOrderInfo['end_time'] = $payOrderInfo['create_time'] + 10 * 60 - time();
                    $payOrderInfo['pay'] = $payOrderInfo['amount'];
                    if(count($orderList)<=0){
                        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                            "msg" => "订单异常，不允许被支付"
                        ],30005)];
                    }else{
                        //这边要标注下：因为订单调用payAction进行支付的订单走orderId
                        //这边要标注下：因为订单调用payAction进行支付的订单走orderId
                        $order = $orderList[0];
                        $payOrderInfo['detailOrderId'] = $order['id'];
                    }
                }
            }
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "payOrderInfo" => $payOrderInfo,
            ])];
        }
    }

//===========3.0start=========

    public function preAddAction() {
//{"sku_info":[{"stock_amount_id":21395,"num":2},{"stock_amount_id":21396,"num":1},{"stock_amount_id":752,"num":2},{"stock_amount_id":21353,"num":1}]}
        $sku_info = $this->_POST("sku_info",'','00001');
        $sku_info = json_decode($sku_info, true);
        if (!is_array($sku_info)) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'sku_info error'],'00001')];
        }
        $output = [];
        //用户收货地址
        $user_addr = new UserAddr();
        $user_addr = $user_addr->addWhere('user_id', User::getCurrentUser()->mId)->orderBy('create_time', 'desc')->addWhere('valid','valid')->find();
        foreach($user_addr as $addr) {
            $output['address'][] = $addr->getData();
        }
        //取sku的详情包括商品详情和买手详情
        $total_price = 0;
        foreach($sku_info['sku_info'] as $sku) {
            $stock_amount = new StockAmount();
            $stock_amount = $stock_amount->addWhere('id', $sku['stock_amount_id'])->select();
            if (!isset(self::$stockInfo[$stock_amount->mStockId])) {
                $stock = new Stock();
                $stock = $stock->addWhere("id", $stock_amount->mStockId)->select();
                $data = $stock->getData();
                if (isset($data['imgs'])) $data['imgs'] = json_decode($data['imgs'], true);
                if ($data['imgs']) {
                    $data['imgs_meta']=array_map(function($file){
                        return ImageMagick::size($file);
                    },$data['imgs']);
                }
                if (isset($data['sku_meta'])) {
                    $meta = json_decode($data['sku_meta'], true);
                    $meta_arr = [];
                    $i = 0;
                    foreach($meta as $key=>$val){
                        $meta_arr[$i++] = ['meta'=>$key, 'value'=>$val];
                    }
                    $data['sku_meta'] = $meta_arr;
                }
                $stock_info['name'] = $data['name'];
                $stock_info['imgs'] = $data['imgs'];
                $stock_info['priceout'] = $data['priceout'];
                $stock_info['buyer_id'] = $data['buyer_id'];
                self::$stockInfo[$stock_amount->mStockId] = $stock_info;
            }
            $buyerId = self::$stockInfo[$stock_amount->mStockId]['buyer_id'];
            if (!isset(self::$buyerInfo[$buyerId])) {
                $buyer = new Buyer();
                $buyer = $buyer->getBuyerInfo($buyerId);
                $buyerInfo['name'] = $buyer['name'];
                $buyerInfo['head'] = $buyer['head'];
                $buyerInfo['level'] = $buyer['level'];
                $buyerInfo['easemob_username'] = $buyer['easemob_username'];
                self::$buyerInfo[$buyerId] = $buyerInfo;
            }
            $skuData['stock_info'] = self::$stockInfo[$stock_amount->mStockId];
            $skuData['sku_value'] = $stock_amount->mSkuValue;
            $skuData['num'] = $sku['num'];
            $skuData['stock_amount_id'] = $sku['stock_amount_id'];
            $skuData['sku_price'] = $sku['num'] * self::$stockInfo[$stock_amount->mStockId]['priceout'];
            $total_price += $skuData['sku_price'];
            $skuData['sku_price'] = '￥'.$skuData['sku_price'];
            $skuData['postage_icon'] = 1;//包税包邮icon
            $tmp[$buyerId]['buyer_info'] = self::$buyerInfo[$buyerId];
            $tmp[$buyerId]['sku_info'][] = $skuData;
        }
        $trade_cart = []; 
        foreach($tmp as $v){
            $trade_cart[] = $v;
        }
        $output['trade_cart'] = $trade_cart;
        //代金券
        $availableCoupons = Coupon::getAvailable($total_price);
        $output['coupons'] = $availableCoupons;
        //页尾文案
        $output['origin_price'] = '原价:￥'.$total_price;
        $output['total_price'] = '总价:￥'.$total_price;
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($output)];
    }

    //刷新总价 一般用于用户在下单页面选择完代金券后重新刷新总价
    public function refreshTotalPriceAction() {
        $sku_info = $this->_POST("sku_info",'','00001');
        $sku_info = json_decode($sku_info, true);
        if (!is_array($sku_info)) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'sku_info error'],'00001')];
        }
        $couponId = $this->_POST("coupon_id");
        if ($couponId) {
            $couponRet = Coupon::checkCouponValidNew($couponId);
            if (!$couponRet['errStatus']) {
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'coupon invalid'],$couponRet['errNo'])];
            }
        }
        $output = [];
        //取sku的详情包括商品详情和买手详情
        $total_price = 0;
        foreach($sku_info['sku_info'] as $sku) {
            $stock_amount = new StockAmount();
            $stock_amount = $stock_amount->addWhere('id', $sku['stock_amount_id'])->select();
            if (!isset(self::$stockInfo[$stock_amount->mStockId])) {
                $stock = new Stock();
                $stock = $stock->addWhere("id", $stock_amount->mStockId)->select();
                $data = $stock->getData();
                $stock_info['priceout'] = $data['priceout'];
                self::$stockInfo[$stock_amount->mStockId] = $stock_info;
            }
            $skuData['sku_price'] = $sku['num'] * self::$stockInfo[$stock_amount->mStockId]['priceout'];
            $total_price += $skuData['sku_price'];
        }
        //页尾文案
        $output['origin_price'] = '原价:￥'.$total_price;
        if ($couponRet) {
            $output['origin_price'] = '原价:￥'.$total_price."-"."代金券:￥".$couponRet['obj']->mValue;
            //php的float运算有坑，先进位*100，然后round 四舍五入，除以100
            $total_price = ($total_price - $couponRet['obj']->mValue)>0 ?  round(100*($total_price - $couponRet['obj']->mValue))/100 : 0.01;
        }
        $output['total_price'] = '总价:￥'.$total_price;
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($output)];
    }

    public function addNewAction() {
        $user_addr_id = $this->_POST('user_addr_id', '', '12001');
        $sku_info = $this->_POST("sku_info",'','00001');
        $sku_info = json_decode($sku_info, true);
        if (!is_array($sku_info)) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'sku_info error'],'00001')];
        }
        $couponId = $this->_POST('coupon_id','');
        if ($couponId) {
            $couponRet = Coupon::checkCouponValidNew($couponId);
            if (!$couponRet['errStatus']) {
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'coupon invalid'],$couponRet['errNo'])];
            }
        }
        $couponValue = isset($couponRet['obj']->mValue)?$couponRet['obj']->mValue : 0;

        //验证参数user_add_id
        $user_addr = new UserAddr();
        $user_addr = $user_addr->addWhere('id',$user_addr_id)->addWhere('user_id',User::getCurrentUser()->mId)->select();
        if (!$user_addr) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'user addr error'],'12001')];
        }
        //计算总价格, 多个sku如果其中任何一个sku 的stock_amount stock 为空直接报错返回
        $total_price = 0;
        foreach($sku_info['sku_info'] as $sku) {
            $stock_amount = new StockAmount();
            $stock_amount = $stock_amount->addWhere('id', $sku['stock_amount_id'])->select();
            if (!$stock_amount) {
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'stock amount error'],'20014')];
            }
            if (!isset(self::$stockInfo[$stock_amount->mStockId])) {
                $stock = new Stock();
                $stock = $stock->addWhere("id", $stock_amount->mStockId)->select();
                if(!$stock){
                    return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'stock error'],'20015')];
                }
                self::$stockInfo[$stock_amount->mStockId]['priceout'] = $stock->mPriceout;
                self::$stockInfo[$stock_amount->mStockId]['buyer_id'] = $stock->mBuyerId;
                self::$stockInfo[$stock_amount->mStockId]['live_id'] = $stock->mLiveId;
                self::$stockInfo[$stock_amount->mStockId]['stock_id'] = $stock->mId;
            }

            //lock amount 需要在这里提前判断库存,如果单件商品库存不足,本次订单创建失败
            $stock_amount_tbl = new DBTable('stock_amount');
            $res = $stock_amount_tbl->addWhere('id',$sku['stock_amount_id'])->addWhere('amount',"`locked_amount`+`sold_amount`+".$sku['num'], '>=', 'and', DBTable::NO_ESCAPE)->select();
            if (!$res) {
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'amount not enough sku_id:'.$sku['stock_amount_id']],'20002')];
            }
            ////////////////////////////
            $total_price += $sku['num'] * self::$stockInfo[$stock_amount->mStockId]['priceout'];
        }

        $pay_amount = GlobalMethod::getPayOrderAmount($total_price, $couponValue);
        $payOrderInfo['amount'] = $pay_amount;
        $payOrderInfo['couponId'] = !empty($couponId)?$couponId:'';
        $time = time();
        $payOrderInfo['time'] = $time;
        $payOrderInfo['vid'] = 1;
        $payOrderInfo['pay_type'] = Order::ALL_PAY;
        $payOrder = PayOrder::addPayOrder($payOrderInfo);
        $payOrder = $payOrder->getData();
        $payOrder['pay'] = $payOrder['amount'];
        $payOrder['end_time'] = $payOrder['create_time'] + self::$order_timeout*60 - time();
        $payOrder['expire_time_show'] = "下单后请".self::$order_timeout."分钟内付款\n超时未支付订单将被自动取消哦。";

        //写order
        foreach($sku_info['sku_info'] as $sku) {
            $stock_amount = new StockAmount();
            $stock_amount = $stock_amount->addWhere('id', $sku['stock_amount_id'])->select();
            if (!isset(self::$stockInfo[$stock_amount->mStockId])) {
                $stock = new Stock();
                $stock = $stock->addWhere("id", $stock_amount->mStockId)->select();
                self::$stockInfo[$stock_amount->mStockId]['priceout'] = $stock->mPriceout;
                self::$stockInfo[$stock_amount->mStockId]['buyer_id'] = $stock->mBuyerId;
                self::$stockInfo[$stock_amount->mStockId]['live_id'] = $stock->mLiveId;
                self::$stockInfo[$stock_amount->mStockId]['stock_id'] = $stock->mId;
            }

            //lock amount 需要在这里提前判断库存,如果单件商品库存不足,本次订单创建失败
            $stock_amount_tbl = new DBTable('stock_amount');
            $res = $stock_amount_tbl->addWhere('id', $sku['stock_amount_id'])->addWhere('amount', "`locked_amount`+`sold_amount`+".$sku['num'],'>=',"and",DBTable::NO_ESCAPE)->update(['locked_amount'=>["`locked_amount`+".$sku['num'], DBTable::NO_ESCAPE]]);
            ///////////lock amount///////////
            $i = 0;
            while($i<$sku['num']) {
                $order = new Order();
                $order->setData([
                    'status'=> 'wait_prepay',
                    'user_id'=> User::getCurrentUser()->mId,
                    'live_id'=> self::$stockInfo[$stock_amount->mStockId]['live_id'],
                    'buyer_id'=> self::$stockInfo[$stock_amount->mStockId]['buyer_id'],
                    'stock_id'=> self::$stockInfo[$stock_amount->mStockId]['stock_id'],
                    'stock_amount_id'=> $sku['stock_amount_id'],
                    'num'=> 1,
                    'sum_price'=> self::$stockInfo[$stock_amount->mStockId]['priceout'],
                    'coupon_id' => empty($couponId) ? '':$couponId,
                    'create_time'=> $time,
                    'update_time'=> $time,
                    'note'=>isset($sku['note'])?$sku['note']:'',
                    'user_addr_id'=> $user_addr_id,
                    'country' => $user_addr->mCountry,
                    'province' => $user_addr->mProvince,
                    'city' => $user_addr->mCity,
                    'addr' => $user_addr->mAddr,
                    'postcode' => $user_addr->mPostcode,
                    'name' => $user_addr->mName,
                    'phone' => $user_addr->mPhone,
                    'cellphone' => $user_addr->mCellphone,
                    'email' => $user_addr->mEmail,
                    'source' => $_SERVER['HTTP_USER_AGENT'],
                    'vid' => 1,
                    'pay_order_id' => $payOrder['id'],
                    'pay_type' => 1,
                ]);
                $res = $order->save();
                //打日志
                GlobalMethod::orderLog($order,'', 'user', User::getCurrentUser()->mId);
                //if (!$res) {
                //    return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order save error'],'30021')];
                //}
                $i++;
            }
        }
        //设置一个id可以让支付成功之后查看订单详情
        $payOrder['detailOrderId'] = $order->mId;
        //这个是预留给支付方用于记录商品名称与描述的字段
//        $payOrder['stockName'] = "";
//        $payOrder['stockDesc'] = "";
        //消息推送
        //$notifyStr = str_replace('%stockName%', $stock ? "“".$stock->mName."”" : "", "您刚拍的%stockName%请在10分钟内支付定金，下手要快哦，不要被别人抢先啦~");
        //Notification::sendNotification($order->mUserId,['title'=>$notifyStr,'type'=>'trade','from'=>'trade',
        //    'data'=>[
        //        'order_id' => $order->mId,
        //        'trade_title' => $order->statusDesc(),
        //        'stock_imageUrl' => $stock->mImgs?json_decode($stock->mImgs,true)[0]:[],
        //    ]
        //]);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["order"=>$payOrder])];
    }

    //3.0版本之后付款都用payNew接口
    public function payNewAction() {
        $id = $this->_POST('id', '', '30010');
        $payOrder = new PayOrder();
        $payOrder = $payOrder->addWhere('id', $id)->select();
        if(!$payOrder || $payOrder->mStatus != 'wait_prepay'){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'pay order status error'],'30023')];
        }
        $payment = new Payment();
        $payment = $payment->addWhere("id", $payOrder->mPrePaymentId)->select();
        if ($payment->mStatus == 'payed') { // 已支付，防止重复支付
            $payOrder->mPrePaymentId = $payment->mId;
            $payOrder->mStatus = 'prepayed';
            $payOrder->mUpdateTime = time();
            if(!$payOrder->save()){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order save error'],'99999')];
            }
            GlobalMethod::orderLog($payOrder, '', 'user', User::getCurrentUser()->mId,1);
            //状态同步给order子订单
            $order = new Order();
            $orders = $order->addWhere('user_id', User::getCurrentUser()->mId)->addWhere('pay_order_id', $payOrder->mId)->find();
            foreach ($orders as $order) {
                $order->mStatus = 'prepayed';
                $order->mUpdateTime = time();
                $order->save();
                GlobalMethod::orderLog($order, '', 'user', User::getCurrentUser()->mId);
            }
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'订单已被支付，请刷新查看'],'30023')];
        } else {
            $payment = new Payment();
            $payment->mOrderId = $payOrder->mId;
            $payment->mUserId = $payOrder->mUserId;
            $payment->mAmount  = $payOrder->mAmount;
            $payment->mCreateTime = time();
            $payment->mUpdateTime = time();
            $payment->mOrderType = 1;
            if (!$payment->save()) {
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'payment save error'],'99999')];
            }
            $payment->mTradeNo = GlobalMethod::genOrderId($payment->mId);
            if(!$payment->save()){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'payment save error'],'99999')];
            }
            $payOrder->mPrePaymentId = $payment->mId;
            $payOrder->mPayType = Order::ALL_PAY;
            $payOrder->mUpdateTime = time();
            if(!$payOrder->save()){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'order save error'],'99999')];
            }
            //状态同步给order子订单
            $order = new Order();
            $orders = $order->addWhere('user_id', User::getCurrentUser()->mId)->addWhere('pay_order_id', $payOrder->mId)->find();
            foreach ($orders as $order) {
                $order->mPrePaymentId = $payment->mId;
                $order->mUpdateTime = time();
                $order->mPayType = Order::ALL_PAY;
                $order->save();
            }
            
        }

        $data['payment_id'] = $payment->mId;
        $data['trade_no'] = $payment->mTradeNo;
        //$data['price'] = $payOrder->mAmount;
        $data['prepay'] = $payment->mAmount;
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($data)];
    }

    //新版本取消关注用这个
    public function cancelNewAction() {
        $payorderId = $this->_POST("payorder_id","",'30010');
        $log = $_POST['log']?$_POST['log']:'';

        //取消payorder
        $payOrder = new PayOrder();
        $payOrder = $payOrder->addWhere('id', $payorderId)->select();
        if($payOrder->mStatus != 'wait_prepay'){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'payorder\'s status is wrong'],'30024')];
        }
        $payOrder->mStatus = 'canceled';
        $time = time();
        $payOrder->mUpdateTime = $time;
        if (!$payOrder->save()) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'can\'t save payorder'],'30022')];
        }
        //给payorder打log 用order_type区分0:order 1:payorder
        GlobalMethod::orderLog($payOrder, $log, 'user', User::getCurrentUser()->mId, 1);
        if(!empty($payOrder->mCouponId)){
            Coupon::resendCoupon($payOrder->mCouponId);
        }

        //取消order
        $order = new Order();
        $orders = $order->addWhere("pay_order_id", $payOrder->mId)->find();
        array_map(function($order)use($time, $log){
            $order->mStatus = 'canceled';
            $order->mUpdateTime = $time;
            $order->save();
            GlobalMethod::orderLog($order,$log, 'user', User::getCurrentUser()->mId);
            StockAmount::releaseLockedAmount($order);
        }, $orders);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'cancel success'])];
    }
}
