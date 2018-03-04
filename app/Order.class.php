<?php
class Order extends Base_Order{

    //预付款模式
    const PRE_PAY = 0;

    //全款模式
    const ALL_PAY = 1;

    public static function getAllStatus(){
        return [
            ['wait_prepay','订单生成'],
            ['prepayed','已支付定金'],
            ['wait_pay','备货完毕'],
            ['payed','已支付全款'],
            ['packed','商品打包完毕'],
            ['wait_refund','备货失败，商品缺货'],
            ['refund','已退定金'],
            ['full_refund','已退全款'],
            ['returned','买手已退货'],
            //['fail','订单已失效'],
            ['to_demostic','商品海外发出'],
            ['demostic','国内入库'],
            ['to_user','商品国内发出'],
            ['post_sale','售后流程'],
            ['success','订单完成'],
            ['canceled','订单取消'],
            ['timeout','未按时支付全款'],
        ];
    }

    public static function genOrderNum($stockIds) {
        $order=new DBTable('order');
        // 符合预支付，支付，成功的订单就算购买数量，显得购买人数较大:)
        $order=$order->setCols(['stock_id'])->addComputedCol('count(*) num')->addWhere("stock_id",$stockIds,'in')->addWhere('status',['wait_prepay','canceled'],'not in')->groupBy('stock_id')->find();
        $orderNumMap = array();
        foreach($order as $r){
            $orderNumMap[$r['stock_id']] = $r['num'];
        }
        return $orderNumMap;
    }

    public static function getStatusDesc($status) {
        switch($status) {
        case 'wait_prepay':
            return '订单生成，等待支付定金';
        case 'prepayed':
            return '已支付定金';
        case 'wait_pay':
            return '备货成功，等待支付余款';
        case 'payed':
        case 'packed':
            return '已补全款，等待发货';
        case 'to_demostic':
            return '商品海外发货';
        case 'demostic':
            return '商品国内入库';
        case 'to_user':
            return '商品国内发出';
        case 'wait_refund':
            return '备货失败，订单关闭';
        case 'refund':
            return '定金已退回，订单关闭';
        case 'full_refund':
            return '全款已退回，订单关闭';
        case 'returned':
        case 'fail':
        case 'canceled':
            return '订单已取消';
        case 'timeout':
            return '余款支付过期，订单关闭';
        case 'post_sale':
        case 'success':
            return '已确认收货，交易成功';
        default:
            return '订单异常';
        }
    }

    public static function getStatusDescV2($status,$payType) {
        switch($status) {
            case 'wait_prepay':
                if($payType == 0)
                    return '订单生成，等待支付定金';
                else{
                    return '订单生成，等待支付';
                }
            case 'prepayed':
                if($payType == 0){
                    return '已支付定金';
                }else{
                    return '已支付，等待发货';
                }
            case 'wait_pay':
                return '备货成功，等待支付余款';
            case 'payed':
            case 'packed':
                return '已补全款，等待发货';
            case 'to_demostic':
                return '商品海外发货';
            case 'demostic':
                return '商品国内入库';
            case 'to_user':
                return '商品国内发出';
            case 'wait_refund':
                return '备货失败，订单关闭';
            case 'refund':
                return '定金已退回，订单关闭';
            case 'full_refund':
                return '全款已退回，订单关闭';
            case 'returned':
            case 'fail':
            case 'canceled':
                return '订单已取消';
            case 'timeout':
                return '余款支付过期，订单关闭';
            case 'post_sale':
            case 'success':
                return '已确认收货，交易成功';
            default:
                return '订单异常';
        }
    }

    public static function getStatusDescForOrderDetail($status) {
        switch($status) {
        case 'wait_prepay':
            return '订单生成，等待支付定金';
        case 'prepayed':
            return '已支付定金';
        case 'wait_pay':
            return '备货成功，请在3天内支付尾款，逾期订单自动关闭';
        case 'payed':
        case 'packed':
            return '已补全款，等待发货';
        case 'to_demostic':
            return '商品海外发货';
        case 'demostic':
            return '商品国内入库,等待发货';
        case 'to_user':
            return '商品国内发出';
        case 'wait_refund':
            return '备货失败，可申请退还定金';
        case 'refund':
            return '定金已退回，订单关闭';
        case 'full_refund':
            return '全款已退回，订单关闭';
        case 'returned':
        case 'fail':
        case 'canceled':
            return '订单已取消';
        case 'timeout':
            return '余款支付过期，订单关闭';
        case 'post_sale':
        case 'success':
            return '已确认收货，交易成功';
        default:
            return '订单异常';
        }
    }

    public function statusDesc(){
        return self::getStatusDesc($this->mStatus);
    }

    public static function getOrdersData($orders){
        $stockAmountIds=array_map(function($order){
            return $order->mStockAmountId;
        },$orders);
        $stockAmountsInfoMap=StockAmount::getStockAmountsInfo($stockAmountIds);
        $ordersData=array_map(function($order)use($stockAmountsInfoMap){
            $data=$order->getData();
            $data=array_merge($data,$stockAmountsInfoMap[$data['stock_amount_id']]);
            return $data;
        },$orders);
        return $ordersData;
    }
    public static function getOrderDataGroupByStockAmount($orders){
        if(!$orders||count($orders)==0){
            return [];
        }
        $stockAmountIds=[];
        $stockIds=[];
        $stockAmountInfos=[];
        $stockAmountMap=[];
        foreach($orders as $order){
            if(isset($stockAmountMap["{$order->mStockId},{$order->mStockAmountId}"])){
                continue;
            }else{
                $stockAmountMap["{$order->mStockId},{$order->mStockAmountId}"]=1;
            }
            $stockIds[]=$order->mStockId;
            $stockAmountIds[]=$order->mStockAmountId;
            $stockAmountInfos[]=[$order->mStockId,$order->mStockAmountId];
        }
        //$stockAmountInfos=array_unique($stockAmountInfos);
        //$stockAmountIds=array_keys($stockAmountMap);
        $stockAmount=new StockAmount();
        $stockAmounts=$stockAmount->addWhere('id',array_unique($stockAmountIds),'in')->findMap("id");
        $stock=new Stock();
        $stocks=$stock->addWhere('id',array_unique($stockIds),'in')->findMap("id");
        
        $stockInfos=[];
        foreach ($stockAmountInfos as $info){
            list($stockId,$stockAmountId)=$info;
            $findStockAmount=$stockAmounts[$stockAmountId];
            $findStock=$stocks[$stockId];
            $stockOrders=array_values(array_filter($orders,function($order)use($findStock,$findStockAmount){
                return $order->mStockAmountId==$findStockAmount->mId;
            }));
            $stockInfos[]=['stock_id'=>$stockId,'stock_amount_id'=>$stockAmountId,
                'name'=>$findStock?$findStock->mName:'',
                'sku'=>$findStockAmount?$findStockAmount->mSkuValue:'',
                'pricein'=>$findStock?$findStock->mPricein:'',
                'priceout'=>$findStock?$findStock->mPriceout:'',
                'imgs'=>$findStock?json_decode($findStock->mImgs,true):[],
                'pricein_unit'=>$findStock?$findStock->mPriceinUnit:'',
                'total_cash'=>array_reduce($stockOrders,function($res,$order){
                    return $res+=$order->mSumPrice;
                },0),
                'orders'=>array_map(
                    function($order){
                            return $order->getData();
                    },$stockOrders)
                ];
        }
        return $stockInfos;
    }

    public static function genOrderDetail($orders){
        if(count($orders) == 0) return $orders;
        $stockIds = array();
        $stockAmountIds = array();
        $userIds = array();
        #$logisticIds = array();
        foreach($orders as $order){
            array_push($stockIds, $order->mStockId);
            array_push($stockAmountIds, $order->mStockAmountId);
            $userIds[$order->mUserId] = $order->mUserId;
        #    array_push($logisticIds, $order->mLogisticId);
        }

        $users = array();
        if(!empty($userIds)) {
            $user = new User();
            $newUsers = $user->getUserListByIdList(array_keys($userIds));
            foreach($newUsers as $k=>$user) {
                $users[$user['id']] = $user;
            }
        }

        $stock = new Stock();
        $stockMap = $stock->addWhere("id",$stockIds,'in')->findMap("id");
        $stockAmount = new StockAmount();
        $stockAmountMap = $stockAmount->addWhere("id",$stockAmountIds,'in')->findMap("id");
        #$logistic = new Logistic();
        #$logisticMap = $logistic->addWhere("id",$logisticIds,'in')->addWhere('valid', 'valid')->findMap("id");

        #$orders=array_map(function($order)use($stockMap,$stockAmountMap,$logisticMap){
        $orders=array_map(function($order)use($stockMap,$stockAmountMap, $users){
            $stock = $stockMap[$order->mStockId];
            $stockAmount = $stockAmountMap[$order->mStockAmountId];
        #    $logistic = $logisticMap[$order->mLogisticId];
            if(!$stock || !$stockAmount)
                return null;
        #$orders=array_map(function($order){
            $data = $order->getData();
            $stockInfo = $stock->getData();
            $stockAmountInfo = $stockAmount->getData();
            #if(!$data['stock_snapshot'] || !$data['stock_amount_snapshot'])  return null;
            #$stockInfo = $data['stock_snapshot'] ? json_decode($data['stock_snapshot'],true) : json_decode('{}');
            #$stockAmountInfo = $data['stock_amount_snapshot'] ? json_decode($data['stock_amount_snapshot'],true) : json_decode('{}');
            $meta = json_decode($stockInfo['sku_meta']);
            $meta_arr = [];
            $i = 0;
            foreach($meta as $key=>$val){
                $meta_arr[$i++] = ['meta'=>$key, 'value'=>$val];
            }
            $stockInfo['sku_meta'] = $meta_arr;
            $stockInfo['priceout_unit_show']=TableUtils::getUnitShow($stockInfo['priceout_unit']);
            $stockInfo['imgs'] = $stockInfo['imgs'] ? json_decode($stockInfo['imgs']) : [];
            $stockInfo['name'] = mb_substr($stockInfo['name'], 0, 64, 'UTF-8');
            $stockInfo['note'] = mb_substr($stockInfo['note'], 0, 64, 'UTF-8');
            $data['stock_info'] = $stockInfo;
            $stockAmountInfo['sku_value'] = ($stockAmountInfo['sku_value'] || strval($stockAmountInfo['sku_value']) === '0') ? preg_replace('/ +/',"\t",$stockAmountInfo['sku_value']) : null;
            $data['stock_amount_info'] = $stockAmountInfo;
            #$data['prepay'] = $data['num'] * $stockInfo['prepay'];
            #$data['pay'] = $data['num'] * $stockInfo['priceout'];
            #$total_pay = $data['num'] * $stockInfo['priceout'];
            $total_pay = $data['sum_price'];
            $data['pay'] = $total_pay;
            $data['prepay'] = GlobalMethod::countPrepay($total_pay,$order->mPayType);
            if($order->mPrePaymentId) {
                $py = new Payment();
                $py = $py->addWhere('id', $order->mPrePaymentId)->select();
                $data['prepay'] = ($py && $py->mStatus == 'payed') ? $py->mAmount : $data['prepay'];
            }
            $data['spare_pay'] = $total_pay - $data['prepay'];

            #$data['priceout_unit']=$stock->mPriceoutUnit;
            #$data['priceout_unit_show']=TableUtils::getUnitShow($stock->mPriceoutUnit);
            // 买家端一般都为CNY，也就是人民币为单位
            $data['pay_unit']=$stock->mPriceoutUnit;
            $data['pay_show']=TableUtils::getUnitShow($stock->mPriceoutUnit);
            $status = $data['status'];
            $data['status_desc'] = Order::getStatusDesc($status);
            $data['status_desc_detail'] = Order::getStatusDescForOrderDetail($status);
            $update_time = $data['update_time'];
            if($status == 'wait_prepay'){
                $data['end_time'] = $update_time + 600;
                $data['expire_time_show'] = '下单后请10分钟内付款，超时未支付订单将被自动取消哦。';
            }else if($status == 'wait_pay'){
                $data['end_time'] = $update_time + 3*86400;
                $data['expire_time_show'] = '补款提醒开始后，请在3天内补完全款，否则取消订单哦！';
            }
            $data['left_time'] = $data['end_time'] - time();
            #if($logistic)   $data['logistic_info'] = $logistic->getData();
            #$data['user_addr_info'] = $data['user_addr_snapshot'] ? json_decode($data['user_addr_snapshot']) : json_decode('{}');
            // 返回与之前一致的格式，客户端无需修改
            $data['user_addr_info'] = [
                'country' => $order->mCountry,
                'province' => $order->mProvince,
                'city' => $order->mCity,
                'addr' => $order->mAddr,
                'postcode' => $order->mPostcode,
                'name' => $order->mName,
                'phone' => $order->mPhone,
                'cellphone' => $order->mCellphone,
                'email' => $order->mEmail
            ];
            $user = $users[$order->mUserId];
            $data['user_easemob_username'] = $user['easemob_username'];
            $data['user_name'] = $user['name'];
            $data['user_avatar_url'] = $user['avatar_url'];
            unset($data["country"]);
            unset($data["province"]);
            unset($data["city"]);
            unset($data["addr"]);
            unset($data["postcode"]);
            unset($data["name"]);
            unset($data["phone"]);
            unset($data["cellphone"]);
            unset($data["email"]);
            #unset($data['user_addr_snapshot']);
            #unset($data['stock_snapshot']);
            #unset($data['stock_amount_snapshot']);
            return $data;
        },$orders);
        $orders=array_values(array_filter($orders));
        return $orders;
    }

    public static function statusFlowValid($before, $after) {
        if($before == $after) {
            return true;
        }
        switch($before) {
        case 'wait_prepay':
            if($after != 'prepayed' && $after != 'canceled') {
                return false;
            }
            break;
        case 'prepayed':
            if($after != 'wait_pay' && $after != 'wait_refund') {
                return false;
            }
            break;
        case 'wait_pay':
            if($after != 'payed' && $after != 'timeout' && $after != 'canceled') {
                return false;
            }
            break;
        case 'payed':
            if($after != 'packed' && $after != 'full_refund') {
                return false;
            }
            break;
        case 'packed':
            if($after != 'to_demostic') {
                return false;
            }
            break;
        case 'to_demostic':
            if($after != 'to_user' && $after != 'full_refund' && $after != 'demostic') {
                return false;
            }
            break;
        case 'demostic':
            if($after != 'to_user' && $after != 'full_refund') {
                return false;
            }
            break;
        case 'to_user':
            if($after != 'success' && $after != 'full_refund') {
                return false;
            }
            break;
        case 'wait_refund':
            if($after != 'refund') {
                return false;
            }
            break;
        case 'timeout':
            if($after != 'refund' && $after != 'returned') {
                return false;
            }
            break;
        case 'returned':
            if($after != 'refund') {
                return false;
            }
            break;
        case 'full_refund':
        case 'refund':
        case 'fail':
        case 'canceled':
        case 'success':
            return false;
        }
        return true;
    }

    /**
     * 根据用户id查询订单列表
     * @param $userId
     * @return array|null
     */
    public function getOrderListByUserId($userId){
        if(empty($userId)){
            return null;
        }else{
            $orderList=$this->addWhere('user_id',$userId)->find();
            $orderList = array_map(function($order){
                return $order->getData();
            },$orderList);
            return $orderList;
        }
    }

    /**
     * 根据orderId来找订单详细信息
     * @param $orderId
     * @return array
     */
    public function getOrderInfoByOrderId($orderId){
        if(empty($orderId)){
            return null;
        }else{
            $ret = $this->addWhere('id',$orderId)->select();
            if(!empty($ret)){
                $ret = $ret->getData();
            }
            return $ret;
        }
    }
    public static function getUserMap($orderIds) {
        $orders = new Order;
        $orders = $orders->addWhere('id', $orderIds, 'in')->orderBy('id', 'asc')->find();
        $usersMap = [];
        array_map(function($order)use(&$usersMap) {
            $order = $order->getData();
            $usersMap[md5($order['name'].$order['phone'].$order['cellphone'].$order['country'].$order['province'].$order['city'].$order['addr'])][] = $order;
        }, $orders);
        ksort($usersMap);
        return $usersMap;
    }

    /**
     * 收货地址转换函数
     * @param $order
     * @return array
     */
    public static function addressUtils($order)
    {
        return array(
            'address' => $order['province'] . "省" . $order['city'] . "市" . $order['addr'],
            'name' => $order['name'],
            'phone' => empty($order['cellphone']) ? $order['phone'] : $order['cellphone']);
    }

    /**
     * 等待支付列表
     * @param $userId
     * @param $pageId
     * @param $count
     * @return null | array
     */
    public function wait2pay($userId, $pageId, $count)
    {
        $payOrderModel = new PayOrder();
        $payOrderList = $payOrderModel->getBaseListByUserId($userId, array("wait_prepay", "wait_pay"), $pageId, $count);
        if(count($payOrderList)<=0){
            return null;
        }
        $payOrderIdList = array_map(function ($payOrder) {
            return $payOrder['id'];
        }, $payOrderList);
        $orderList = $this->getOrderListByPayOrderId($payOrderIdList);
        //商品信息,sku信息,优惠券的信息,买手信息

        $resourceInfo = $this->getRichInfoOfOrderIdList($orderList);
        $buyerList = $resourceInfo['buyerList'];
        $stockList = $resourceInfo['stockList'];
        $stockAmountList = $resourceInfo['stockAmountList'];
        $couponList = $resourceInfo['couponList'];

        $cellList = $this->waitPayOrderList($payOrderList, $buyerList, $stockList, $stockAmountList, $couponList);
        return $cellList;

    }

    /**
     * 根据状态去生成订单列表
     * @param $userId
     * @param $status
     * @param int $pageId
     * @param int $count
     * @return array
     */
    public function getOrderListByStatus($userId, $status, $pageId = 0, $count = 20)
    {
        switch ($status) {
            case "":
            case "all":
                return $this->getAllOrderList($userId, $pageId, $count);
            case "wait2pay":
                return $this->wait2pay($userId, $pageId, $count);
            case "wait2deliver":
            case "wait2receive":
            case "success":
            case "refund":
                $orderList = $this->orderListByStatus($userId, $status, $pageId, $count);
                $resourceInfo = $this->getRichInfoOfOrderIdList($orderList);
                return $this->enrichOrderList($orderList, $resourceInfo['buyerList'], $resourceInfo['stockList'], $resourceInfo['stockAmountList'], $resourceInfo['couponList']);
                break;
            default:
                return null;
        }
    }

    /**
     * 根据用户+状态获取order列表
     * @param $userId
     * @param $status
     * @param $pageId
     * @param $count
     * @return array
     */
    public function orderListByStatus($userId, $status, $pageId, $count)
    {
        $offset = $pageId * $count;
        switch ($status) {
            case "wait2pay":
                $orderList = $this->addWhere('user_id', $userId)->addWhere('status', array('wait_prepay', 'wait_pay'), 'in')->orderBy('id','desc')->limit($offset, $count)->find();
                break;
            case "wait2deliver":
                $orderList = $this->addWhere('user_id', $userId)->addWhereRaw(" and( (`pay_type`=1 and `status` = 'prepayed') or `status` = 'payed')")->orderBy('id','desc')->limit($offset, $count)->find();
                break;
            case "wait2receive":
                $orderList = $this->addWhere('user_id', $userId)->addWhere('status', array('to_demostic', 'to_user', 'demostic'), 'in')->orderBy('id','desc')->limit($offset, $count)->find();
                break;
            case "success":
                $orderList = $this->addWhere('user_id', $userId)->addWhere('status', 'success')->orderBy('id','desc')->limit($offset, $count)->find();
                break;
            case "refund":
                $orderList = $this->addWhere('user_id', $userId)->addWhere('status', array('wait_refund', 'full_refund', 'refund'), 'in')->orderBy('id','desc')->limit($offset, $count)->find();
                break;
        }
        $orderList = array_map(function ($order) {
            return $order->getData();
        }, $orderList);
        return $orderList;
    }

    /**
     * 根据支付id列表获取订单列表
     * @param $payOrderIdList
     * @return array
     */
    public function getOrderListByPayOrderId($payOrderIdList)
    {
        if(count($payOrderIdList) >=1){
            $orderList = $this->addWhere('pay_order_id', $payOrderIdList, 'in')->find();
            $orderList = array_map(function ($order) {
                return $order->getData();
            }, $orderList);
            return $orderList;
        }else{
            return null;
        }

    }

    /**
     * 根据支付订单信息获取买手列表，商品列表，sku存库信息，优惠券信息
     * @param $orderList
     * @return array
     */
    protected function getRichInfoOfOrderIdList($orderList)
    {
        //商品信息,sku信息,优惠券的信息,买手信息
        $stockIdList = array();
        $stockAmountIdList = array();
        $couponIdList = array();
        $buyerIdList = array();
        foreach ($orderList as $order) {
            array_push($stockIdList, $order['stock_id']);
            array_push($stockAmountIdList, $order['stock_amount_id']);
            array_push($couponIdList, $order['coupon_id']);
            array_push($buyerIdList, $order['buyer_id']);
            $couponIdList = array_filter($couponIdList);
            $stockIdList = array_filter($stockIdList);
            $stockAmountIdList = array_filter($stockAmountIdList);
            $buyerIdList = array_filter($buyerIdList);

        }
        $stockList = (new Stock())->genStockDetailOfStockList(array_filter($stockIdList));
        foreach ($stockList as $stock) {
            $newStockList[$stock['id']] = $stock;
        }
        $stockList = $newStockList;
        $stockAmountList = (new StockAmount())->getStockAmountList(array_filter($stockAmountIdList));
        foreach ($stockAmountList as $stockAmount) {
            $newStockAmountList[$stockAmount['id']] = $stockAmount;
        }
        $stockAmountList = $newStockAmountList;
        $couponList = (new Coupon())->getListByCouponIdList(array_filter($couponIdList));
        foreach ($couponList as $coupon) {
            $newCouponList[$coupon['coupon_id']] = $coupon;
        }
        $couponList = $newCouponList;
        $buyerList = (new Buyer())->getListByIdList(array_filter($buyerIdList));
        foreach ($buyerList as $buyer) {
            $newBuyerList[$buyer['id']] = $buyer;
        }
        $buyerList = $newBuyerList;

        return array(
            'buyerList' => $buyerList,
            'stockAmountList' => $stockAmountList,
            'stockList' => $stockList,
            'couponList' => $couponList,
        );
    }

    /**
     * 丰富订单列表信息
     * @param $orderList
     * @param $buyerList
     * @param $stockList
     * @param $stockAmountList
     * @param $couponList
     * @return array
     */
    public function enrichOrderList($orderList, $buyerList, $stockList, $stockAmountList, $couponList)
    {
        $cellList = array();
        foreach ($orderList as $order) {
            //resource
            $couponInfo = $couponList[$order['coupon_id']];
            $buyerInfo = $buyerList[$order['buyer_id']];
            $stockAmountInfo = $stockAmountList[$order['stock_amount_id']];
            $stockInfo = $stockList[$order['stock_id']];
            $payOrderInfo = (new PayOrder())->getPayOrderInfoById($order['pay_order_id']);

            $cell = $this->enrichOtherPayOrder($order,$payOrderInfo, $buyerInfo, $stockInfo, $stockAmountInfo, $couponInfo);
            $cellList [] = $cell;
        }
        return $cellList;
    }

    /**
     * 获取所有状态的订单列表
     * @param $userId
     * @param $pageId
     * @param $count
     * @return array
     */
    public function getAllOrderList($userId, $pageId, $count)
    {
        $payOrderList = (new PayOrder())->getBaseListByUserId($userId, "", $pageId, $count);

        $payOrderIdList = array_map(function ($payOrder) {
            return $payOrder['id'];
        }, $payOrderList);
        $orderList = $this->getOrderListByPayOrderId($payOrderIdList);
        //商品信息,sku信息,优惠券的信息,买手信息

        $resourceInfo = $this->getRichInfoOfOrderIdList($orderList);
        $buyerList = $resourceInfo['buyerList'];
        $stockList = $resourceInfo['stockList'];
        $stockAmountList = $resourceInfo['stockAmountList'];
        $couponList = $resourceInfo['couponList'];

        //分开两拨，等待付款、其他状态
        $waitPayOrderList = array();
        $otherPayOrderList = array();

        /**
         * 分成两拨处理处理，待付款需要进行合并/其他状态不用合并
         */
        foreach ($payOrderList as $payOrder) {
            if (!PayOrder::isPayed($payOrder['status'], $payOrder['pay_type'])) {
                $waitPayOrderList[] = $payOrder;
            } else {
                $otherPayOrderList[] = $payOrder;
            }
        }
        $cellList = array();
        $waitPayOrderList = $this->waitPayOrderList($waitPayOrderList, $buyerList, $stockList, $stockAmountList, $couponList);
        $otherPayOrderList = $this->otherPayOrderList($otherPayOrderList, $buyerList, $stockList, $stockAmountList, $couponList);
        foreach ($payOrderIdList as $payOrderId) {
            foreach ($waitPayOrderList as $waitPayOrder) {
                if ($waitPayOrder['payOrderId'] == $payOrderId) {
                    $cellList[] = $waitPayOrder;
                }
            }
            foreach ($otherPayOrderList as $otherPayOrder) {
                if ($otherPayOrder['payOrderId'] == $payOrderId) {
                    $cellList [] = $otherPayOrder;
                }
            }
        }
        return $cellList;
    }

    /**
     * 丰富待付订单的信息
     * @param $payOrderList
     * @param $buyerList
     * @param $stockList
     * @param $stockAmountList
     * @param $couponList
     * @return array
     */
    protected function waitPayOrderList($payOrderList, $buyerList, $stockList, $stockAmountList, $couponList)
    {
        $payOrderIdList = array_map(function ($payOrder) {
            return $payOrder['id'];
        }, $payOrderList);
        $orderList = $this->getOrderListByPayOrderId($payOrderIdList);
        //商品信息,sku信息,优惠券的信息,买手信息

        $cellList = array();
        foreach($payOrderList as $payOrder){
            $cell = $this->enrichWaitPayOrder($orderList,$payOrder,$buyerList,$stockList,$stockAmountList,$couponList);
            $cellList []= $cell;
        }
        return $cellList;
    }

    /**
     * 除去代付款订单进行内容信息丰富
     * @param $payOrderList
     * @param $buyerList
     * @param $stockList
     * @param $stockAmountList
     * @param $couponList
     * @return array
     */
    protected function otherPayOrderList($payOrderList, $buyerList, $stockList, $stockAmountList, $couponList)
    {
        $payOrderIdList = array_map(function ($payOrder) {
            return $payOrder['id'];
        }, $payOrderList);
        if(count($payOrderIdList) >=1){
            $orderList = $this->getOrderListByPayOrderId($payOrderIdList);
            $cellList = $this->enrichOrderList($orderList, $buyerList, $stockList, $stockAmountList, $couponList);
            return $cellList;
        }else{
            return null;
        }

    }

    /**
     * 订单详情
     */
    public function detailByOrderId($orderId)
    {
        if (empty($orderId)) {
            return null;
        } else {
            $orderInfo = $this->getOrderInfoByOrderId($orderId);
            $payOrderInfo = (new PayOrder())->getPayOrderInfoById($orderInfo['pay_order_id']);
            if (!PayOrder::isPayed($payOrderInfo['status'], $payOrderInfo['pay_type'])) {
                $orderList = $this->getOrderListByPayOrderId(array($orderInfo['pay_order_id']));
                $resourceInfo = $this->getRichInfoOfOrderIdList($orderList);
                return $this->enrichWaitPayOrder($orderList,$payOrderInfo,$resourceInfo['buyerList'],$resourceInfo['stockList'],$resourceInfo['stockAmountList'],$resourceInfo['couponList'],true);
            } else {
                $stockInfo = (new Stock())->getStockInfoByStockId($orderInfo['stock_id']);
                $buyerInfo = (new Buyer())->getBuyerInfo($orderInfo['buyer_id']);
                $stockAmountInfo = (new StockAmount())->getBaseInfoById($orderInfo['stock_amount_id']);
                $couponInfo = (new Coupon())->getCouponInfo($orderInfo['coupon_id']);
                return $this->enrichOtherPayOrder($orderInfo,$payOrderInfo,$buyerInfo,$stockInfo,$stockAmountInfo,$couponInfo,true);
            }
        }
    }

    /**
     * 丰富等待支付订单的订单信息
     * @param $orderList
     * @param $payOrder
     * @param $buyerList
     * @param $stockList
     * @param $stockAmountList
     * @param $couponList
     * @param $detail
     * @return array
     */
    protected function enrichWaitPayOrder($orderList, $payOrder, $buyerList, $stockList, $stockAmountList, $couponList,$detail=false)
    {
        $status = $payOrder['status'];
        $payOrderId = $payOrder['id'];
        //获取skuOrder的信息
        $tempData = array();
        $skuOrderList = array();
        $total = 0;
        foreach ($orderList as $order) {
            //按照stock_amount_id进行聚合
            if ($order['pay_order_id'] == $payOrderId) {
                $stockAmountInfo = $stockAmountList[$order['stock_amount_id']];
                $stockAmount = $stockAmountInfo;
                $buyerInfo = $buyerList[$order['buyer_id']];
                $order['buyerInfo'] = $buyerInfo;
                $stockInfo = $stockList[$order['stock_id']];
                $order['stockInfo'] = $stockInfo;
                $skuInfoTemp = $stockAmount['sku_value'];
                $skuMeta = $stockInfo['sku_meta'];
                $skuInfo=[];
                for ($i = 0; $i < count($skuInfoTemp); $i++) {
                    $skuInfo[$i]['meta'] = $skuMeta[$i]['meta'];
                    $skuInfo[$i]['value'] [] = $skuInfoTemp[$i];
                }
                $stockInfo['sku_meta'] = $skuInfo;
                $order['recipientInfo'] = self::addressUtils($order);
                $order['couponInfo'] = $couponList[$order['coupon_id']];

                //现金券信息
                if (!$tempData['couponInfo'] && $order['couponInfo']) {
                    $tempData['couponInfo'] = Coupon::couponUtils($order['couponInfo']);
                }

                //收货信息
                if (!$tempData['recipientInfo']) {
                    $tempData['recipientInfo'] = self::addressUtils($order);
                }

                //物流信息
                $tempData['logisticsInfo'] = array('show'=>false,'desc'=>null,"update_time"=>null);

                $stockAmountId = $order['stock_amount_id'];
                if (!$skuOrderList[$stockAmountId]) {
                    $newOrder = array(
                        'buyerInfo' => $buyerInfo,
                        'id' => $order['id'],
                        'stockAmountId' => $order['stock_amount_id'],
                        'stockInfo' => $stockInfo,
                        'note' => $order['note'],
                        'price' => $order['sum_price'],
                        'num' => 1,
                    );
                    $skuOrderList[$stockAmountId] = $newOrder;
                } else {
                    $skuOrderList[$stockAmountId]['num']++;
                }
                $total += $order['sum_price'];
            }
        }

        $tempData['skuOrderList'] = array_values($skuOrderList);

        //orderLogInfo
        $orderLog = (new OrderLog())->getOrderLastLog($order['id']);
        $tempData['orderLogInfo'] = array(
            "desc" => self::getStatusDescV2($orderLog['op_type'],$payOrder['pay_type']),
            "updateTime" => $orderLog['create_time'],
        );
        if($detail){
            $tempData['orderLogInfo']['show'] = true;
        }else{
            $tempData['orderLogInfo']['show'] = false;
        }

        //recipientInfo
        if($detail){
            $tempData['recipientInfo']['show'] = true;
        }else{
            $tempData['recipientInfo']['show'] = false;
        }

        //consumerServiceInfo
        if($detail){
            $tempData['consumerServiceInfo'] = array("show" => true, "desc" => "联系爱美直播客服", "phone" => "4008-766-388");
        }else{
            $tempData['consumerServiceInfo'] = array("show" => true, "desc" => "联系爱美直播客服", "phone" => "4008-766-388");
        }

        //couponInfo
        if(!empty($tempData['couponInfo'])){
            $tempData['couponInfo']['show'] = true;
        }else{
            $tempData['couponInfo']['show'] = false;
        }

        //refundInfo
        $tempData['refundInfo'] = array("show" => false, "desc" => null, "phone" => null);

        //orderDesc
        $tempData['orderDesc'] = array(
            'show' => true,
            'status' => $status,
            'desc' => self::getStatusDescV2($status,$payOrder['pay_type']),
            'payTime' => $payOrder['update_time'],
            'leftTime' => $status == 'wait_prepay' ? ($payOrder['update_time'] + 600 - time()) : ($payOrder['update_time'] + 3 * 86400 - time()),
            'payType' => $payOrder['pay_type'],
            'canComment' => false,
        );

        //payOrderId
        $tempData['payOrderId'] = $payOrder['id'];

        $cut = empty($tempData['couponInfo']['cut']) ? 0 : $tempData['couponInfo']['cut'];
        //paymentInfo
        if ($payOrder['status'] == 'wait_pay') {
            $tempData["paymentInfo"] =
                array(
                    "show" => true,
                    "total" => $total,
                    "prepay" => GlobalMethod::countPrepay($total),
                    "pay" => ($total - GlobalMethod::countPrepay($total)),
                    "cut" => $cut,
                    "curPay" => GlobalMethod::getPayOrderAmount(($total - GlobalMethod::countPrepay($total)),$cut),
                );
        } else if ($payOrder['status'] == 'wait_prepay') {
            $tempData["paymentInfo"] =
                array(
                    "show" => true,
                    "total" => $total,
                    "prepay" => $total,
                    "pay" => 0,
                    "cut" => $cut,
                    "curPay" => GlobalMethod::getPayOrderAmount($total, $cut),
                );
        }
        return $tempData;
    }


    /**
     * @param $order
     * @param $buyerInfo
     * @param $payOrderInfo
     * @param $stockInfo
     * @param $stockAmountInfo
     * @param $couponInfo
     * @param $detail
     * @return array
     */
    protected function enrichOtherPayOrder($order,$payOrderInfo, $buyerInfo, $stockInfo, $stockAmountInfo, $couponInfo, $detail = false)
    {
        $tempData = array();
        $status = $order['status'];

        //payOrderId
        $payOrder = (new PayOrder())->getPayOrderInfoById($order['pay_order_id']);
        $tempData['payOrderId'] = $order['pay_order_id'];

        //canComment
        $canCommentList = (new TradeRate())->canComment(array($order));

        //orderDesc
        $tempData['orderDesc'] = array(
            'show' => true,
            'status' => $order['status'],
            'desc' => self::getStatusDescV2($status,$payOrder['pay_type']),
            'payTime' => $order['create_time'],
            'canComment' => $canCommentList[$order['id']], //todo是否可以评价
            'payType' => $payOrder['pay_type'],
        );

        //refundInfo
        $refundDesc = null;
        if(in_array($status, array('wait_refund', 'full_refund', 'refund'))){
            $refundInfo = UserRefund::getInstance()->getInfoByOrderId($order['id']);
            $refundDesc = UserRefund::utils($refundInfo);
        }
        $tempData['refundInfo'] = array(
            'desc' => $refundDesc,
            'refundTime' => in_array($status, array('wait_refund', 'full_refund', 'refund')) ? $order['update_time'] : null,
            'show' => in_array($status, array('wait_refund', 'full_refund', 'refund')) ? true : false,
        );

        //orderLogInfo
        $orderLog = (new OrderLog())->getOrderLastLog($order['id']);
        $tempData['orderLogInfo'] = array(
            "desc" => self::getStatusDescV2($orderLog['op_type'],$payOrder['pay_type']),
            "updateTime" => $orderLog['create_time'],
        );
        $tempData['orderLogInfo']['show'] = $detail ? true : false;

        //logisticInfo fixed by @boshen
        if( in_array($status, array('to_demostic', 'demostic')) ) {
            $logisticInfo = (new Pack())->getLogisticById($order['pack_id']);
        } else {
            $logisticInfo = (new Logistic())->getLogisticOfOrderId($order['id']);
        }
        $logisticTrackInfo = LogisticTracking::getNewTracking($logisticInfo['logistic_no'], $logisticInfo['logistic_provider_fixed']);
        $logisticOrderLog = (new OrderLog())->getLogByStatus($order['id'],'to_demostic');
        $tempData['logisticsInfo'] = array(
            'desc' => $logisticTrackInfo['context'],
            'update_time' => empty($logisticTrackInfo['ftime'])?0:$logisticTrackInfo['ftime'],
            'show' => !empty($logisticOrderLog) ? true:false,
        );

        //recipientInfo
        $tempData['recipientInfo'] = self::addressUtils($order);
        $tempData['recipientInfo']['show'] = $detail ? true : false;


        $stockAmount = $stockAmountInfo;
        $skuInfoTemp = $stockAmount['sku_value'];
        $skuMeta = $stockInfo['sku_meta'];
        $skuInfo=[];
        for ($i = 0; $i < count($skuInfoTemp); $i++) {
            $skuInfo[$i]['meta'] = $skuMeta[$i]['meta'];
            $skuInfo[$i]['value'] [] = $skuInfoTemp[$i];
        }
        $stockInfo['sku_meta'] = $skuInfo;

        //skuOrderList
        $tempData['skuOrderList'] []= array(
            'buyerInfo' => $buyerInfo,
            'id' => $order['id'],
            'stockAmountId' => $order['stock_amount_id'],
            'stockInfo' => $stockInfo,
            'note' => $order['note'],
            'price' => $order['sum_price'],
            'num' => 1,
        );

        //consumerServiceInfo
        $tempData['consumerServiceInfo'] = array("show"=> false,"desc" => "联系爱美直播客服", "phone" => "4008-766-388");

        //couponInfo
        $tempData['couponInfo'] = Coupon::couponUtils($couponInfo);
        $tempData['couponInfo']['show'] = true;

        //paymentInfo
        $cut = empty($tempData['couponInfo']['cut']) ? 0 : $tempData['couponInfo']['cut'];
        if ($payOrder['pay_type'] == 1) {
            $tempData["paymentInfo"] =
                array(
                    "total" => $order['sum_price'],
                    "prepay" => $order['sum_price'],
                    "pay" => 0,
                    "cut" => $cut,
                    "curPay" => 0,
                );
        } else {
            $tempData["paymentInfo"] =
                array(
                    "total" => $order['sum_price'],
                    "prepay" => GlobalMethod::countPrepay($order['sum_price']),
                    "pay" => ($order['sum_price'] - GlobalMethod::countPrepay($order['sum_price'])),
                    "cut" => $cut,
                    "curPay" => 0,
                );
        }
        return $tempData;
    }

    /**
     * 根据订单Id列表获取订单信息
     * @param $orderIdList
     * @return array|null
     */
    public function getListByIdList($orderIdList){
        if(count($orderIdList) <= 0){
            return null;
        }else{
            $rets = $this->addWhere('id',$orderIdList,'in')->find();
            $orderList = array();
            foreach($rets as $ret){
                $orderList []= $ret->getData();
            }
            return $orderList;
        }
    }

    /**
     * 根据支付id获取订单id列表
     * @param $payOrderId
     * @return array
     */
    public function getListByPayOrderId($payOrderId){
        if(empty($payOrderId)){
            return false;
        }else{
            $rets = $this->addWhere('pay_order_id', $payOrderId)->find();
            if(!empty($rets)){
                $rets = array_map(function($order){
                    return $order->getData();
                },$rets);
                return $rets;
            }
        }
    }
}
