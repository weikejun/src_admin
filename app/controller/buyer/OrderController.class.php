<?php
class OrderController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new BuyerLoginInterceptor());
        $this->addInterceptor(new DBTransactionInterceptor('finishBuyAction'));
    }
    /**
     * 买手v2.0接口，买手订单列表
     */
    public function myAction(){
        $pageId=$this->_GET('pageId',1);
        $count=$this->_GET('count',10);
        $liveId = $this->_GET('liveId', 0);
        $status = trim($this->_GET('status', ''));
        $order=new Order();
        $order->setAutoClear(false);
        $order=$order
            ->addWhere('buyer_id', Buyer::getCurrentBuyer()->mId)
            ->orderBy("id","desc");
        if(!empty($status)) {
            $statArr = [];
            switch($status) {
            case 'purchase':
                $statArr = ['prepayed'];
                break;
            case 'pay':
                $statArr = ['wait_pay', 'wait_prepay'];
                break;
            case 'delivery':
                $statArr = ['payed','packed'];
                break;
            case 'success':
                $statArr = ['success','to_demostic','to_user'];
                break;
            case 'cancel':
                $statArr = ['wait_refund','refund','returned','fail','canceled','timeout'];
                break;
            case 'returning':
                $statArr = ['timeout'];
                break;
            default:
                $statArr = false;
                break;
            }
            if($statArr) {
                $order->addWhere('status', $statArr, 'IN');
            }
        }
        if(!empty($liveId)) {
            $order->addWhere('live_id', $liveId);
        }
        $allCount=$order->count();
        $pageInfo=$this->_PAGE($allCount,$pageId,$count);
        $orders=$order->limit(($pageId-1)*$count,$count)->find();
        $orders = Order::genOrderDetail($orders);
        
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["orders"=>$orders,"pageInfo"=>$pageInfo])];
    }

    public function statAction() {
        $buyerId = Buyer::getCurrentBuyer()->mId;
        $finder = new Order();
        $pack = new Pack();
        $stats = array(
            'prepayed' => $finder->addWhere('buyer_id', $buyerId)->addWhere('status', 'prepayed')->count(),
            'payed' => $finder->addWhere('buyer_id', $buyerId)->addWhere('status', 'payed')->count(),
            'timeout' => $finder->addWhere('buyer_id', $buyerId)->addWhere('status', 'timeout')->count(),
            'not_send' => $pack->addWhere('buyer_id', $buyerId)->addWhere('status', 'not_send')->count(),
            'send' => $pack->addWhere('buyer_id', $buyerId)->addWhere('status', 'send')->count()
        );
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["order_stat" => $stats])];
    }

    public function packListAction() {
        $orderMap = [];
        $liveMap = [];
        $order = new Order();
        $payedOrders=$order->addWhere('buyer_id', Buyer::getCurrentBuyer()->mId)->addWhere('status','payed')->orderBy('create_time')->find();
        if(!empty($payedOrders)) {
            foreach($payedOrders as $order) {
                $orderTmp = Order::genOrderDetail([$order]);
                $orderTmp = $orderTmp[0];
                if(empty($orderTmp)) {
                    continue;
                }
                $orderMap[$order->mLiveId]['orders'][] = [
                    'id' => $orderTmp['id'],
                    'note' => $orderTmp['note'],
                    'sku_info' => [
                        'id' => $orderTmp['stock_info']['id'],
                        'name' => $orderTmp['stock_info']['name'],
                        'cover' => $orderTmp['stock_info']['imgs'][0],
                        'sku_value' => $orderTmp['stock_amount_info']['sku_value'],
                    ],
                ];
            }
            $live = new Live();
            $lives = $live->addWhere('buyer_id', Buyer::getCurrentBuyer()->mId)->addWhere('id', array_keys($orderMap), 'in')->orderBy('start_time', 'ASC')->find();
            foreach($lives as $live) {
                $liveMap[] = [
                    'id' => $live->mId,
                    'name' => $live->mName,
                    'start_time' => $live->mStartTime,
                    'end_time' => $live->mEndTime,
                    'orders' => $orderMap[$live->mId]['orders'],
                ];
            }
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["lives"=>$liveMap], 0)];
    }
    public function buyListAction(){
        $live=new Live();
        $live=$live->addWhere('id',$this->_GET('live_id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->orderBy('start_time', 'ASC')->select();
        if(!$live){
            return ['json:',AppUtils::returnValue(['no live'],99999)];
        }

        $order=new Order();
        $orders=$order->addWhere('live_id',$live->mId)->addWhere('status','prepayed')->find();

        return ['json:',AppUtils::returnValue([
            'stockInfos'=>Order::getOrderDataGroupByStockAmount($orders),
            //'stockAmountMap'=>$stockAmountMap,
            ],0)];
    }

    //两个功能，备货完成通知付尾款，备货失败退定金
    //检查如果是100%定金（全款模式），备货完毕需要将订单状态改成payed
    //参数说明：
    //  order_ids 备货完成的订单
    //  refund_order_ids 备货失败需要退款的订单
    public function finishBuyAction(){
        /*
        $stockAmount=new StockAmount();
        $stockAmount=$stockAmount->addWhere('id',$this->_POST('stock_amount_id','',10001))->select();
        if(!$stockAmount){
            return ['json:',AppUtils::returnValue(['no stock amount'],99999)];
        }
        $stock=new Stock();
        $stock=$stock->addWhere('id',$stockAmount->mStockId)->select();
        if(!$stock){
            return ['json:',AppUtils::returnValue(['no stock'],99999)];
        }*/
        ///////buy orders
        $orderIds=json_decode($this->_POST('order_ids',''),true);
        $buyer_id = Buyer::getCurrentBuyer()->mId;
        if($orderIds){
            $order=new Order();
            $orders=$order->addWhere('id',$orderIds,'in')->addWhere('status','prepayed')->addWhere('buyer_id', $buyer_id)->find();
            foreach($orders as $order){
                if(1 == $order->mPayType) {
                    //付全款用户的订单状态应该修改为“已付款”
                    $order->mStatus='payed';
                } else {
                    $order->mStatus='wait_pay';
                    if(!empty($order->mPayOrderId)){
                        PayOrder::getInstance()->updatePayOrderStatus($order->mPayOrderId,'prepayed','wait_pay');
                    }
                }
                $order->mUpdateTime = time();
                $stock=new Stock();
                $stock=$stock->addWhere('id',$order->mStockId)->select();

                if(1 == $order->mPayType) {
                    //付全款文案修改
                    $notifyStr = str_replace('%stockName%', $stock ? "“".$stock->mName."”" : "", "您的%stockName%已经买到，即将海外发货，请您耐心等待或者在客户端查看物流跟踪");
                } else {
                    $notifyStr = str_replace('%stockName%', $stock ? "“".$stock->mName."”" : "", "您的%stockName%已经买到，别忘了在3个工作日内前往淘世界订单中心补齐余款，若逾期未补款订单将被视为取消，定金将无法退回，客服咨询4008766388");
                }
                Notification::sendNotification($order->mUserId,['title'=>$notifyStr,'type'=>'trade','from'=>'trade',
                    'data'=>[
                        'order_id'=>$order->mId,
                        'trade_title'=>$order->statusDesc(),
                        'stock_imageUrl'=>$stock->mImgs?json_decode($stock->mImgs,true)[0]:[],
                    ]
                    ]);
                $order->save();
                GlobalMethod::orderLog($order,'', 'buyer', Buyer::getCurrentBuyer()->mId);

                if($order->mPayType == Order::PRE_PAY){
                    //状态同步到pay_order add by hongjie
                    $payOrderInfo['mStatus'] = 'wait_pay';
                    $payOrderInfo['id'] = $order->mPayOrderId;
                    $payOrder = PayOrder::updatePayOrder($payOrderInfo);
                    if ($payOrder) {
                        GlobalMethod::orderLog($payOrder, '', 'buyer', Buyer::getCurrentBuyer()->mId, 1);
                    }
                }
            }
        }
        ///////refund orders
        $orderIds=json_decode($this->_POST('refund_order_ids',''),true);
        if($orderIds){
            $refund_reason = $this->_POST('refund_reason', '备货失败');
            $order=new Order();
            $orders=$order->addWhere('id',$orderIds,'in')->addWhere('status','prepayed')->addWhere('buyer_id', $buyer_id)->find();
            foreach($orders as $order){
                $orderId = $order->mId;
                $order->mStatus='wait_refund';
                $order->mUpdateTime = time();
                $stock=new Stock();
                $stock=$stock->addWhere('id',$order->mStockId)->select();
                $notifyStr = str_replace('%stockName%', $stock ? "“".$stock->mName."”" : "", "很遗憾地通知您，您订购的%stockName%无法买到，您支付的款项将在7个工作日内原路退还给您。希望您下次能买到喜欢的商品，客服咨询4008766388");
                Notification::sendNotification($order->mUserId,['title'=>$notifyStr,'type'=>'trade','from'=>'trade',
                    'data'=>[
                        'order_id'=>$order->mId,
                        'trade_title'=>$order->statusDesc(),
                        'stock_imageUrl'=>$stock->mImgs?json_decode($stock->mImgs,true)[0]:[],
                    ]
                ]);
                $order->save();
                //当order的payType=0时，需要更新payOrder的状态 todo: test
                $orderTemp = (new Order())->getOrderInfoByOrderId($orderId);
                if($orderTemp['pay_type'] == 0){
                    if(!empty($orderTemp['pay_order_id'])){
                        (new PayOrder())->updatePayOrderStatus($orderTemp['pay_order_id'],'prepayed','refund');
                    }
                }

                $userRefund = new UserRefund;
                $userRefund->mOrderId = $order->mId;
                $userRefund->mCreateTime = time();
                $userRefund->mUpdateTime = time();
                //备货失败，或者是买卖家协商过可退款
                $userRefund->mReason = $refund_reason;
                $userRefund->mCreator = 'buyer';
                $userRefund->mCreatorId = Buyer::getCurrentBuyer()->mId;
                $userRefund->save();
                GlobalMethod::orderLog($order, '', 'buyer', Buyer::getCurrentBuyer()->mId);

                //状态同步到pay_order add by hongjie
                $payOrderInfo['mStatus'] = 'refund';
                $payOrderInfo['id'] = $order->mPayOrderId;
                $payOrder = PayOrder::updatePayOrder($payOrderInfo); 
                if ($payOrder) {
                    GlobalMethod::orderLog($payOrder, '', 'buyer', Buyer::getCurrentBuyer()->mId, 1);
                }
            }
        }
        return ['json:',AppUtils::returnValue([],0)];
    }
    /*
    public function packListAction(){
        $live=new Live();
        $live=$live->addWhere('id',$this->_GET('live_id','',99999))->select();
        if(!$live){
            return ['json:',AppUtils::returnValue(['no live'],99999)];
        }

        $order=new Order();
        $payedOrders=$order->addWhere("live_id",$live->mId)->addWhere('status','payed')->orderBy('create_time')->find();

        $payedOrdersData=Order::getOrdersData($payedOrders);

        $waitPayOrders=$order->addWhere("live_id",$live->mId)->addWhere('status','wait_pay')->find();
        $waitPayOrdersData=Order::getOrdersData($waitPayOrders);

        return ['json:',AppUtils::returnValue([
            'payedOrders'=>$payedOrdersData,
            'waitPayOrders'=>$waitPayOrdersData,
            ],0)];
    }*/
    public function payedListAction(){
        $live=new Live();
        $live=$live->addWhere('id',$this->_GET('live_id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$live){
            return ['json:',AppUtils::returnValue(['no live'],99999)];
        }

        $order=new Order();
        $payedOrders=$order->addWhere("live_id",$live->mId)->addWhere('status','payed')->orderBy('create_time')->find();
        //$payedOrdersData=Order::getOrdersData($payedOrders);
        
        return ['json:',AppUtils::returnValue(Order::getOrderDataGroupByStockAmount($payedOrders),0)];
    }
    public function waitPayListAction(){
        $live=new Live();
        $live=$live->addWhere('id',$this->_GET('live_id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$live){
            return ['json:',AppUtils::returnValue(['no live'],99999)];
        }

        $order=new Order();
        $waitPayOrders=$order->addWhere("live_id",$live->mId)->addWhere('status','wait_pay')->find();
        //$waitPayOrdersData=Order::getOrdersData($waitPayOrders);
        return ['json:',AppUtils::returnValue(Order::getOrderDataGroupByStockAmount($waitPayOrders),0)];
    }
    public function finishPackAction(){
        $name=$this->_POST('name','',10001);
        
        $live=new Live();
        $live->setAutoClear(false);
        $live->addWhere('buyer_id', Buyer::getCurrentBuyer()->mId);
        $liveId = $this->_POST('live_id', 0);
        if(!empty($liveId)) {
            $live = $live->addWhere('id', $liveId)->select();
            if(!$live){
                return ['json:',AppUtils::returnValue(['no live'],10002)];
            }
        }

        $orderIds=json_decode($this->_POST('order_ids','',10003),true);
        if(!$orderIds){
            return ['json:',AppUtils::returnValue(['empty order ids'],10004)];
        }
        $order=new Order();
        $orders=$order->addWhere('status','payed')->addWhere("buyer_id", Buyer::getCurrentBuyer()->mId)->addWhere('id', $orderIds, 'in')->find();
        $liveIds = array_map(function($order) {
            return $order->mLiveId;
        }, $orders);
        
        $pack=new Pack();
        $pack->mName=$name;
        $pack->mLiveId= $liveIds[0];
        $pack->mLiveIds = implode(',', array_unique($liveIds));
        $pack->mBuyerId=Buyer::getCurrentBuyer()->mId;
        $pack->mCreateTime=time();
        $pack->mUpdateTime=time();
        $pack->mNote=$this->_POST('note',"");
        $imgs=$this->_POST('imgs','');
        $imgs=json_decode($imgs,true);
        if(!$imgs){
            $imgs=[];
        }
        $pack->mImgs=json_encode($imgs);
        $ret=$pack->save();
        if(!$ret){
            return ['json:',AppUtils::returnValue(['save pack failed'],99999)];
        }
        foreach($orders as $order){
            /*
            $packItem=new PackItem();
            $packItem->mOrderId=$order->mId;
            $packItem->mPackId=$pack->mId;
            $packItem->mCreateTime=time();;
            $packItem->save();
             */
            $order->mStatus='packed';
            $order->mPackId=$pack->mId;
            $order->mUpdateTime = time();
            $order->save();
            GlobalMethod::orderLog($order,'', 'buyer', Buyer::getCurrentBuyer()->mId);
            //状态同步到pay_order add by hongjie
            $payOrderInfo['mStatus'] = 'payed';
            $payOrderInfo['id'] = $order->mPayOrderId;
            $payOrder = PayOrder::updatePayOrder($payOrderInfo); 
            if ($payOrder) {
                GlobalMethod::orderLog($payOrder, '', 'buyer', Buyer::getCurrentBuyer()->mId, 1);
            }
        }
        // 标记仓储表
        $storage = new Storage;
        $stoarge = $storage->addWhere('order_id', $orderIds, 'in')->update(['pack_id' => $pack->mId]);
        if(!$storage) {
            PLogger::get("service_exception",['file_prefix'=>'service_exception_','level'=>PLogger::INFO,'path'=> ROOT_PATH."/log/"])->info(implode("\t",[
                "STORAGE:PACK",
                "packId=".$pack->mId
            ])
        );
        }
        $pack = new Pack();
        $dCount = $pack->addWhere('buyer_id', Buyer::getCurrentBuyer()->mId)->addWhere('status', 'not_send')->count();
        return ['json:',AppUtils::returnValue(['pending_count' => $dCount],0)];
    }
    public function callPayAction(){
        //催用户补款
        $orderIds=json_decode($this->_POST('order_ids',''),true);
        if($orderIds){
            $order=new Order();
            $orders=$order->addWhere('id',$orderIds,'in')->addWhere('status','wait_pay')->find();
            //TODO 此处应该检验订单是否属于该买手
            $stockIds=[];
            foreach($orders as $order){
                $stockIds[]=$order->mStockId;
            }
            $stock = new Stock();
            $stocks=$stock->addWhere("id",array_unique($stockIds),"in")->findMap();
            foreach($orders as $order){
                $stock=$stocks[$order->mStockId];
                Notification::sendNotification($order->mUserId,['title'=>"【淘世界】订单{$order->mId}已采购，请尽快完成付款",'type'=>'trade','from'=>'trade',
                    'data'=>[
                        'order_id'=>$order->mId,
                        'trade_title'=>$order->statusDesc(),
                        'stock_imageUrl'=>($stock&&$stock->mImgs)?json_decode($stock->mImgs,true)[0]:[],
                    ]
                ]);
            }
        }
        return ['json:',AppUtils::returnValue([],0)];
    }
    public function returnAction(){
        //用户没补款，退货
        $orderIds=json_decode($this->_POST('order_ids',''),true);
        //TODO 此处应该检验订单是否属于该买手
        if($orderIds){
            $order=new Order();
            $orders=$order->addWhere('id',$orderIds,'in')->addWhere('status','timeout')->orderBy('create_time', 'ASC')->find();
            foreach($orders as $order){
                $order->mStatus='returned';
                $order->mUpdateTime = time();
                $stock = new Stock();
                $stock = $stock->addWhere('id', $order->mStockId)->select();
                $smsStr = "【淘世界】订单{$order->mId}长时间未补款，已退货";
                if($stock) {
                    $smsStr = "【淘世界】您购买的商品“{$stock->mName}”因逾期未补款，买手已退货。";
                }
                Notification::sendNotification($order->mUserId,['title'=>$smsStr,'type'=>'trade','from'=>'trade',
                
                    'data'=>[
                        'order_id'=>$order->mId,
                        'trade_title'=>$order->statusDesc(),
                        'stock_imageUrl'=>$stock->mImgs?json_decode($stock->mImgs,true)[0]:[],
                    ]
                ]);
                $order->save();
                GlobalMethod::orderLog($order,'', 'buyer', Buyer::getCurrentBuyer()->mId);

                //状态同步到pay_order add by hongjie
                $payOrderInfo['mStatus'] = 'returned';
                $payOrderInfo['id'] = $order->mPayOrderId;
                $payOrder = PayOrder::updatePayOrder($payOrderInfo); 
                if ($payOrder) {
                    GlobalMethod::orderLog($payOrder, '', 'buyer', Buyer::getCurrentBuyer()->mId, 1);
                }
            }
        }
        return ['json:',AppUtils::returnValue([],0)];
    }

    //-----------------------------新版3.0的api---------------------------

    //获取买手名下已经付定金的订单对应的商品列表
    public function getPrepayedStockListAction() {
        $buyer_id = Buyer::getCurrentBuyer()->mId;

        $order = new Order();
        $list = $order->addWhere('buyer_id', $buyer_id)->addWhere('status', 'prepayed')->orderBy('update_time', 'ASC')->find();
        $stock_ids = $stocks = $first_times = array();
        foreach( $list as $v ) {
            $v = $v->getData();
            $stock_id = $v['stock_id'];
            $stock_ids[$stock_id]++;
            if( !isset($first_times[$stock_id]) ) {
                $first_times[$stock_id] = $v['update_time'];
            }
        }

        $order_nums = array();
        if( !empty($stock_ids) ) {
            $stock=new Stock();
            $stocks = $stock->addWhere('id', array_keys($stock_ids), 'in')->findMap('id');
            foreach( $stocks as $stock_id=>$stock ) {
                $stock = Stock::getDataFromObject($stock);
                $stock['order_num'] = $stock_ids[$stock_id];
                $stock['order_cost'] = $stock['order_num'] * $stock['priceout'];
                $stock['first_time'] = date('Y-m-d H:i:s', $first_times[$stock_id]);
                $stocks[$stock_id] = $stock;
                $order_nums[] = $stock['order_num'];
            }
        }

        //排序
        array_multisort($first_times, SORT_ASC, $order_nums, SORT_DESC, $stocks);

        $data = array( 'stocks' => $stocks );

        return ['json:',AppUtils::returnValue($data, 0)];
    }

    //根据stock_id获取sku列表
    public function getBuyStockAmountListAction() {
        $stock_id = $this->_GET('stockId');
        $buyer_id = Buyer::getCurrentBuyer()->mId;
        $stock = new Stock();
        $stock = $stock->addWhere('id', $stock_id)->addWhere('buyer_id', $buyer_id)->select();
        if( empty($stock) ) {
            return ['json:',AppUtils::returnValue(['msg'=>'no stock info'],99999)];
        }

        $order=new Order();
        $orders=$order->addWhere('stock_id',$stock_id)->addWhere('status','prepayed')->orderBy('update_time', 'ASC')->find();

        $data = array( 'stockInfos'=>Order::getOrderDataGroupByStockAmount($orders) );
        return ['json:',AppUtils::returnValue($data, 0)];
    }

    //获取等待打包的订单列表（按照商品维度）
    public function getWaitPackListAction() {
        $buyer_id = Buyer::getCurrentBuyer()->mId;
        $stocks = array();
        $order = new Order();
        $payedOrders=$order->addWhere('buyer_id', $buyer_id)->addWhere('status','payed')->orderBy('update_time', 'ASC')->find();
        if(!empty($payedOrders)) {
            $detail_orders = Order::genOrderDetail($payedOrders);
            foreach($detail_orders as $order) {
                if( !isset($stocks[$order['stock_id']]['start_time']) ) {
                    $stocks[$order['stock_id']]['start_time'] = $order['update_time'];
                }
                $stocks[$order['stock_id']]['stock'] = $order['stock_info'];
                unset($order['stock_info']);
                unset($order['user_addr_info']);
                $stocks[$order['stock_id']]['orders'][] = $order;
            }
            foreach( $stocks as $stock_id=>$stock ) {
                $stocks[$stock_id]['order_num'] = count($stock['orders']);
            }
        }

        return ['json:', AppUtils::returnValue(["stocks"=>$stocks], 0)];
    }

    //-----------------------------新版3.0的api[END]---------------------------

}
