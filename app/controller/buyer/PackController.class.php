<?php
class PackController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new BuyerLoginInterceptor());
    }
    public function listAction(){
        $pageId=$this->_GET('pageId', 1);
        $count=$this->_GET('count', 100);
        $liveId = $this->_GET('liveId', 0);
        if(empty($liveId)) {
            $liveId = $this->_GET('live_id', 0);
        }
        $status = trim($this->_GET('status', ''));
        $pack=new Pack();
        $pack->setAutoClear(false);
        //$packs=$pack->addWhere('live_id',$this->_GET('live_id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->orderBy('status','desc')->find();
        $pack=$pack->addWhere('buyer_id', Buyer::getCurrentBuyer()->mId);
        if(!empty($liveId)) {
            $pack=$pack->addWhere('live_id', $liveId);
        }
        if(!empty($status)) {
            switch($status) {
            case 'send':
                $pack=$pack->orderBy('update_time', 'ASC');
            case 'not_send':
                $pack=$pack->orderBy('update_time', 'DESC');
                $pack=$pack->addWhere('status', $status);
                break;
            default:
                break;
            }
        }
        $allCount=$pack->count();
        $pageInfo=$this->_PAGE($allCount, $pageId, $count);
        $packs=$pack->limit(($pageId - 1)*$count, $count)->find();
        
        return ['json:',AppUtils::returnValue([
            'packs'=>array_map(function($pack){
                $order = new Order;
                $data=$pack->getData();
                $data['imgs']=json_decode($data['imgs'],true);
                $data['imgs']=$data['imgs']?$data['imgs']:[];
                $data['order_num'] = $order->addWhere('pack_id', $pack->mId)->count();
                return $data;
            },$packs),
            "pageInfo"=>$pageInfo
            ],0)];
    }
    public function listOrderAction(){
        $pack=new Pack();
        $pack=$pack->addWhere('id',$this->_GET('id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$pack){
            return ['json:',AppUtils::returnValue(['no pack found'],99999)];
        }

        $order=new Order();
        $orders=$order->addWhere('pack_id',$pack->mId)->find();
        return ['json:',AppUtils::returnValue(Order::getOrderDataGroupByStockAmount($orders),0)];
    }
    public function showAction(){
        $pack=new Pack();
        $pack=$pack->addWhere('id',$this->_GET('id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$pack){
            return ['json:',AppUtils::returnValue(['no pack found'],99999)];
        }
        $data=$pack->getData();
        $data['imgs']=json_decode($data['imgs'],true);
        $data['imgs']=$data['imgs']?$data['imgs']:[];
        $order = new Order;
        $orders = $order->addWhere('pack_id', $pack->mId)->find();
        $data['orders'] = Order::genOrderDetail($orders);
        return ['json:',AppUtils::returnValue($data,0)];
    }

    public function sendAction(){
        $pack=new Pack();
        $pack=$pack->addWhere('id',$this->_POST('id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$pack){
            return ['json:',AppUtils::returnValue(['no pack found'],99999)];
        }
        $pack->mLogisticProvider=$this->_POST('logistic_provider','',99999);
        $pack->mLogisticNo=$this->_POST('logistic_no','',99999);
        $pack->mLogisticPrice=$this->_POST('logistic_price',0);
        $pack->mLogisticPriceUnit=$this->_POST('logistic_price_unit',"CNY");
        //$pack->mImgs=$this->_POST('imgs','',99999);
        $lImgs = json_decode($this->_POST('imgs', '', 99999),true);

        if(!$lImgs) {
            $lImgs = [];
        }
        $pack->mLogisticImgs = json_encode($lImgs);
        $pack->mStatus='send';
        $pack->mUpdateTime=time();
        $pack->mLogisticProviderFixed = Logistic::getGlobalFixedProvider($pack->mLogisticProvider);
        $res=$pack->save();

        //物流追踪订阅（快递100） by boshen@20141211
        if(!empty($pack->mLogisticProviderFixed)) {
            $logistic = new Logistic();
            $res = $logistic->registerLogic($pack->mLogisticNo, $pack->mLogisticProviderFixed);
        }

        #$orderTbl=new DBTable('order');
        #$orderTbl->addWhere('pack_id',$pack->mId)->update(['status'=>'to_demostic']);
        // 使用 Order 替代 DBTable统一log打印方式
        $order=new Order();
        foreach($order->addWhere("pack_id",$pack->mId)->find() as $order){
            $order->mStatus='to_demostic';
            $order->mUpdateTime = time();
            $stock = new Stock();
            $stock = $stock->addWhere('id', $order->mStockId)->select();
            $content = str_replace('%stockName%', $stock ? "商品“".$stock->mName."”" : "订单".$order->mId, "您的%stockName%已经从海外发出。一般在3-4周到达国内。国际包裹的物流单号是：%logistic%。客服咨询4008766388");
            $content = str_replace('%logistic%', $pack->mLogisticProvider."/".$pack->mLogisticNo, $content);
            $sms = new SmsQueue();
            $sms->mPhone = $order->mCellphone;
            $sms->mContent = $content;
            $sms->mOrderId = $order->mId;
            $sms->mCreateTime = time();
            $sms->save();
            if($order->save()){
                GlobalMethod::orderLog($order,'', 'buyer', Buyer::getCurrentBuyer()->mId);
            }

            //状态同步到pay_order add by hongjie
            $payOrderInfo['mStatus'] = 'payed';
            $payOrderInfo['id'] = $order->mPayOrderId;
            $payOrder = PayOrder::updatePayOrder($payOrderInfo); 
            if ($payOrder) {
                GlobalMethod::orderLog($payOrder, '', 'buyer', Buyer::getCurrentBuyer()->mId, 1);
            }

            $delivery = new DeliveryAbroad();
            $delivery = $delivery->addWhere('order_id', $order->mId)->select();
            if(empty($devlivery)) $delivery = new DeliveryAbroad;
            $delivery->mBuyerId = $order->mBuyerId;
            $delivery->mStockId = $order->mStockId;
            $delivery->mSkuId = $order->mStockAmountId;
            $delivery->mPackId = $order->mPackId;
            $delivery->mOrderId = $order->mId;
            $delivery->mLiveId = $order->mLiveId;
            $delivery->mDeliveryTime = $order->mUpdateTime;
            $delivery->save();
            $storage = new Storage;
            $storage = $storage->addWhere('order_id', $order->mId)->select();
            if(!$storage) {
                $storage = new Storage;
                $storage->mOrderId = $order->mId;
                $storage->mBuyerId = $order->mBuyerId;
                $storage->mUserId = $order->mUserId;
                $storage->mCreateTime = time();
            }
            $storage->mPackId = $order->mPackId;
            $storage->mSendTime = time();
            $storage->save();
        }
        return ['json:',AppUtils::returnValue([],0)];
    }
    public function unpackAction(){
        $pack=new Pack();
        $pack=$pack->addWhere('id',$this->_POST('id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$pack){
            return ['json:',AppUtils::returnValue(['no pack found'],99999)];
        }
        //$packItemTbl=new DBTable('pack_item');
        //$packItemTbl->addWhere('pack_id',$pack->mId)->delete();

        $orderTbl=new DBTable('order');
        $orderTbl->addWhere('pack_id',$pack->mId)->update(['pack_id'=>null,'status'=>'payed']);
        $pack->delete();
        return ['json:',AppUtils::returnValue([],0)];
    }
    public function addOrdersAction(){
        $pack=new Pack();
        $pack=$pack->addWhere('id',$this->_POST('id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$pack){
            return ['json:',AppUtils::returnValue(['no pack found'],99999)];
        }
        
        $orderIds=json_decode($this->_POST('order_ids','',10003),true);
        if(!$orderIds){
            return ['json:',AppUtils::returnValue(['empty order ids'],10004)];
        }
        $order=new Order();
        $orders=$order->addWhere('status','payed')->addWhere('id',$orderIds,'in')->findMap("id");
        
        $orderTbl=new DBTable("order");
        $orderTbl->addWhere("id",array_keys($orders),"in")->update(['status'=>'packed','pack_id'=>$pack->mId]);
        return ['json:',AppUtils::returnValue([],0)];

    }
    public function delOrdersAction(){

        $pack=new Pack();
        $pack=$pack->addWhere('id',$this->_POST('id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$pack){
            return ['json:',AppUtils::returnValue(['no pack found'],99999)];
        }


        $orderIds=json_decode($this->_POST('order_ids','',99999),true);
        if(!$orderIds){
            return ['json:',AppUtils::returnValue(['empty order ids'],99999)];
        }
        $order=new Order();
        $orders=$order->addWhere('status','packed')->addWhere("pack_id",$pack->mId)->addWhere('id',$orderIds,'in')->findMap("id");
        $orderTbl=new DBTable("order");
        $orderTbl->addWhere("id",array_keys($orders),"in")->update(['status'=>'payed','pack_id'=>null]);
        return ['json:',AppUtils::returnValue([],0)];
        
    }


    public function uploadImgsAction(){
        $paths=FileUtil::uploadFile(isset($_FILES['imgs_file'])?$_FILES['imgs_file']:$_POST['imgs_file'],PUBLIC_IMAGE_BASE,["png",'jpg','jpeg','gif'],PUBLIC_IMAGE_URI);
        if(!$paths){
            return ['json:',AppUtils::returnValue(['upload error'],99999)];
        }
        return ['json:',AppUtils::returnValue($paths,0)];
    }

}
