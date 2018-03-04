<?php
class StatusFilter extends Page_Admin_ChoiceFilter{
    public function __construct(){
        $this->paramName='status';
        $this->name='订单状态';
        $this->choices=Order::getAllStatus();
    }
}
class ParamFilter extends Page_Admin_HiddenFilter{
    public function __construct(){
        $this->paramName=['pack_id'];
    }
    
}
class OrderController extends Page_Admin_Base {
    public function bindModelEvent(){
        $before_status=false;
        $this->model->on("before_update",function($model)use(&$before_status){
            //update 之前，没有查询老的值，只能再查一次
            $class=get_class($model);
            $curModel=new $class();
            $curModel=$curModel->addWhere("id",$model->mId)->select();
            if(!$curModel){
                return false;
            }
            $before_status=$curModel->mStatus;
        });
        $this->model->on("after_update",function($model)use(&$before_status){
            $after_status=$model->mStatus;
            Logger::debug("after_update $before_status.$after_status");
            if(!Order::statusFlowValid($before_status, $after_status)) {
                EMail::send([
                    'title'=>"[warning]订单操作异常",
                    'content'=> '订单' . $model->mId . '状态由<font color="red">' . Order::getStatusDesc($before_status) . '</font>变为<font color="red">' . Order::getStatusDesc($after_status) .'</font>，操作者：<font color="red">' . Admin::getCurrentAdmin()->mName . '</font><br />备注：' . $model->mSysNote,
                    'to' => 'op.leaders@aimeizhuyi.com',
                ]);
            }
            if($model->mStatus == 'to_user') { // 添加短信队列
                $stock = new Stock();
                $stock = $stock->addWhere('id', $model->mStockId)->select();
                $content = str_replace('%stockName%', $stock ? "商品“".$stock->mName."”" : "订单".$model->mId, "您订购的%stockName%已到达国内，经淘世界检验确认合格后将尽快发送给您。客服咨询4008766388");
                $sms = new SmsQueue;
                $sms->mPhone = $model->mCellphone;
                $sms->mCreateTime = time();
                $sms->mOrderId = $model->mId;
                $sms->mContent = $content;
                $sms->save();
            }
            if($model->mStatus == 'to_demostic') { // 添加结算表
                $order = new Order;
                $order = $order->addWhere('id', $model->mId)->select();
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
            }
            if($model->mStatus == 'wait_refund' && $before_status != 'wait_refund') { // 备货失败，生成退款单
                $refund = new UserRefund;
                $refund->mReason = '备货失败';
                $refund->mCreateTime = time();
                $refund->mUpdateTime = time();
                $refund->mCreator = 'admin';
                $refund->mCreatorId = Admin::getCurrentAdmin()->mId;
                $refund->mOrderId = $model->mId;
                $refund->save();
            }
            if($before_status&&$after_status&&$before_status!=$after_status){
                // 订单操作日志
                GlobalMethod::orderLog($model, '', 'admin', Admin::getCurrentAdmin()->mId);
                if($after_status=='payed'){
                    $payment = new Payment();
                    if($model->mPaymentId) {
                        $payment = $payment->addWhere('id', $model->mPaymentId)->find(); 
                        if($payment) {
                            $payment = $payment[0];
                        }
                    }
                    if(!$model->mPaymentId || !$payment) {
                        $payment = new Payment();
                    } 
                    $payment->mOrderId=$model->mId;
                    $payment->mUserId=$model->mUserId;
                    $payment->mType='pay';
                    $payment->mSource = 'kefu';
                    $payment->mAmount=$model->mSumPrice - GlobalMethod::countPrepay($model->mSumPrice);
                    $payment->mCreateTime=time();
                    $payment->mStatus = 'payed';
                    $payment->mUpdateTime = time();
                    $payment->save();
                    $model->mPaymentId = $payment->mId;
                    $model->save();
                }
                if($after_status=='prepayed'){
                    $payment = new Payment();
                    if($model->mPrePaymentId) {
                        $payment = $payment->addWhere('id', $model->mPrePaymentId)->find(); 
                        if($payment) {
                            $payment = $payment[0];
                        }
                    }
                    if(!$model->mPrePaymentId || !$payment) {
                        $payment = new Payment();
                    } 
                    $payment->mOrderId=$model->mId;
                    $payment->mUserId=$model->mUserId;
                    $payment->mType='prepay';
                    $payment->mSource = 'kefu';
                    $payment->mAmount=GlobalMethod::countPrepay($model->mSumPrice);
                    $payment->mCreateTime=time();
                    $payment->mStatus = 'payed';
                    $payment->mUpdateTime = time();
                    $payment->save();
                    $model->mPrePaymentId = $payment->mId;
                    $model->save();
                }
            }
        });
    }

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Order();
        $this->model->orderBy("id",$this->_GET('__order', "desc"));
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);
        
        $this->bindModelEvent();

        $this->form=new Form(array(
            //array('name'=>'id','label'=>'订单ID','type'=>"hidden",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'user_id','label'=>'用户ID','type'=>"choosemodel",'model'=>'User','readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'status','label'=>'订单状态','type'=>"choice",'choices'=>Order::getAllStatus(),'default'=>null,'required'=>false,),
            array('name'=>'live_id','label'=>'直播ID','type'=>"choosemodel",'model'=>'Live','readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'stock_id','label'=>'商品ID','type'=>"choosemodel",'model'=>'Stock','readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'stock_amount_id','label'=>'SKU ID','type'=>"choosemodel",'model'=>'StockAmount','readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'num','label'=>'商品数量','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'sum_price','label'=>'订单金额','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'logistic_id','label'=>'物流流水号','type'=>"choosemodel",'model'=>'Logistic','readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'pack_id','label'=>'包裹ID','type'=>"text",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'create_time','label'=>'下单时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false, 'auto_update' => true),
            array('name'=>'note','label'=>'买家备注','type'=>"textarea",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'pre_payment_id','label'=>'定金支付ID','type'=>"choosemodel",'model'=>'Payment','readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'payment_id','label'=>'全款支付ID','type'=>"choosemodel",'model'=>'Payment','readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'province','label'=>'收件省份','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'city','label'=>'收件城市(区)','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'addr','label'=>'收件地址','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'postcode','label'=>'收件邮编','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'name','label'=>'收件人','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'cellphone','label'=>'收件联系电话','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'sys_note','label'=>'后台备注','type'=>"textarea",'readonly'=>false,'default'=>null,'required'=>false,),
        ));
        $this->multi_actions=array(
            array('label'=>'批量打印订单','action'=>'/admin/order/printSelect?ids=__ids__'),
            array('label'=>'导出全部订单','required'=>false,'action'=>'/admin/order/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        );
        $this->list_display=[
            ['label'=>'订单ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'下单时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
            ['label'=>'更新时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mUpdateTime);
            }],
            ['label'=>'商品信息','field'=>function($model){
                $stock = new Stock();
                $stock = $stock->addWhere('id', $model->mStockId)->select();
                $stockAmount = new StockAmount();
                $stockAmount = $stockAmount->addWhere('id', $model->mStockAmountId)->select();
                if($stock) {
                    return $stock->mName . "<br />" . implode('/',array_keys(json_decode($stock->mSkuMeta, true))) . ':' . str_replace("\t", '/', $stockAmount->mSkuValue) . ($model->mNote ? '<br /><font color="red">买家备注：' . $model->mNote . '</font>' : '');
                }
            }],
            ['label'=>'商品原价','field'=>function($model){
                $stock = new Stock();
                $stock = $stock->addWhere('id', $model->mStockId)->select();
                if($stock) {
                    return $stock->mPricein . "\t" . $stock->mPriceinUnit;
                }
            }],
            ['label'=>'订单状态','field'=>function($model){
                $refunds = $this->_getResource($model->mId, 'UserRefund', new UserRefund, 'order_id');
                $refund = null;
                $refundAmount = 0;
                foreach($refunds as $refObj) {
                    if($refObj->mStatus == 0) {
                        $refund = $refObj;
                    } elseif($refObj->mStatus == 2 && $refObj->mRange == 1) {
                        $refundAmount += $refObj->mAmount;
                    }
                }
                $refundDesc = '';
                if($refundAmount) {
                    $refundDesc = "，<font color=red>已部分退款".$refundAmount."元</font>";
                }
                foreach(Order::getAllStatus() as $status){
                    if($model->mStatus==$status[0]){
                        if($refund && !in_array($refund->mStatus, [2, 3, 4])) {
                            return $status[1].'，<font color=red>全额退款申请中</font><a href="/admin/UserRefund?action=read&type=cancel&id='.$refund->mId.'">[取消退款]</a>'.$refundDesc;
                        } else {
                            return $status[1].$refundDesc;
                        }
                    }
                }
            }],
            ['label'=>'订单金额','field'=>function($model){
                return $model->mSumPrice;
            }],
            ['label'=>'已付金额','field'=>function($model,$pageAdmin,$modelDataList){
                $payments = new Payment;
                //$payments = $payments->addWhere('order_id', $model->mId)->addWhere('status', 'payed')->find();

                $paymentIds = array();
                if($model->mPrePaymentId){
                    $paymentIds []= $model->mPrePaymentId;
                }
                if($model->mPaymentId){
                    $paymentIds []= $model->mPaymentId;
                }
                if(count($paymentIds) >=1 ){
                    $payments = $payments->addWhere('id',$paymentIds,'in')->find();
                }
                $payAmount = 0;
                foreach($payments as $i => $payment) {
                    if(!$payment||$payment->mStatus!='payed'){
                        continue;
                    }
                    $payAmount += $payment->mAmount;
                }
                return sprintf('%.2f', $payAmount);
            }],
            ['label'=>'收货信息','field'=>function($model){
                return "$model->mName $model->mPhone $model->mCellphone<br /> $model->mProvince,$model->mCity,$model->mAddr";
            }],
            ['label'=>'用户ID','field'=>function($model){
                return '<a href="/admin/order?__filter='.urlencode('user_id='.$model->mUserId).'">'.$model->mUserId.'</a>';
            }],
            ['label'=>'直播ID','field'=>function($model){
                $res = self::_getResource($model->mLiveId, 'live', new Live);
                return '<a href="/admin/order?__filter='.urlencode('live_id='.$model->mLiveId).'">'.$model->mLiveId.'</a><br />挑款师：'.$res->mSelector.'<br />编辑：'.$res->mEditor;
            }],
            ['label'=>'商品ID','field'=>function($model){
                return '<a href="/admin/order?__filter='.urlencode('stock_id='.$model->mStockId).'">'.$model->mStockId.'</a>';
            }],
            //,'stock_amount_id','pre_payment_id','payment_id','logistic_id','pack_id'
            ];
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'订单ID','paramName'=>'id','fusion'=>false, 'in'=>true]),
            new Page_Admin_TextFilter(['name'=>'用户ID','paramName'=>'user_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'直播ID','paramName'=>'live_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'商品ID','paramName'=>'stock_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'包裹ID','paramName'=>'pack_id','fusion'=>false, 'in'=>true]),
            new Page_Admin_TextFilter(['name'=>'买手ID','paramName'=>'buyer_id','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>'下单时间','paramName'=>'create_time']),
            new Page_Admin_TimeRangeFilter(['name'=>'更新时间','paramName'=>'update_time']),
            new Page_Admin_RangeFilter(['name'=>'订单金额','paramName'=>'sum_price']),
            new StatusFilter(),
        );
        
        /*
        $this->inline_admin=array(
            new Page_Admin_InlineSiteModule($this,'site_id'),
        );
        $this->multi_actions=array(
            array('label'=>'添加到module','action'=>'javascript:add_to_module();return false;'),
        );*/
        $this->single_actions=[
            ['label'=>'商品','action'=>function($model){
                return '/admin/stock?__filter='.urlencode('id='.$model->mStockId);
            }],
            ['label'=>'SKU','action'=>function($model){
                return '/admin/stockAmount?__filter='.urlencode('id='.$model->mStockAmountId);
            }],
            ['label'=>'支付','action'=>function($model){
                return '/admin/payment?__filter='.urlencode('order_id='.$model->mId);
            }],
            ['label'=>'国内快递','action'=>function($model){
                return '/admin/logistic?__filter='.urlencode('id='.$model->mId);
            }],
            ['label' => '打印快递单', 'action' => function($model) {
                return '/admin/order/printSelect?ids='.$model->mId;
            }],
            ['label'=>'申请退款','action'=>function($model){
                return '/admin/userRefund?action=read&type=create&fields='.urlencode('order_id='.$model->mId);
            },'enable'=>function($model) {
                $finder = new UserRefund;
                $refunds = $this->_getResource($model->mId, 'UserRefund', $finder, 'order_id');
                $pending = false;
                foreach($refunds as $refund) {
                    if($refund->mStatus == 0) {
                        $pending = true;
                    }
                }
                return !$pending && !in_array($model->mStatus, ['wait_prepay', 'canceled', 'full_refund', 'refund']);
            }],
        ];
        
        $this->search_fields=[];//array('live_id','stock_id','user_id');
    }
    use ExportToCsvAction;
    public function printSelectAction() {
        $ids = $this->_GET('ids', 0);
        if(empty($ids)) {
            echo '订单ID不正确';
            exit;
        }
        return array("admin/order/print_select.tpl",array('ids'=>$ids));
    }

    public function printAction() {
        $usersMap = $this->_printAction();
        $tpl = 'admin/order/print_';
        switch($vendor = $this->_GET('vendor', 'shunfeng')) {
        case 'yuantong':
            $tpl = $tpl . 'yuantong.tpl';
            break;
        case 'shunfeng_new':
            $tpl = $tpl . 'shunfeng_new.tpl';
            break;
        case '_sender':
            $tpl = $tpl . '_sender.tpl';
            break;
        default:
            $tpl = $tpl . 'shunfeng.tpl';
            break;
        }
        return array($tpl,array('users'=>$usersMap));
    }

    private function _printAction() {
        $ids = $this->_GET('ids', 0);
        $ids = explode(',', $ids);
        if(empty($ids)) {
            echo '订单ID不正确';
            exit;
        }
        $finder = new Order;
        $orders = $finder->addWhere('id', $ids, 'IN')->orderBy('id', 'asc')->find();
        $stockIds = array();
        $skuIds = array();
        foreach($orders as $index => $order) {
            $stockIds[] = $order->mStockId;
            $skuIds[] = $order->mStockAmountId;
        }
        $finder = new Stock;
        $stocks = $finder->addWhere('id', $stockIds, 'IN')->find();
        $finder = new StockAmount;
        $skus = $finder->addWhere('id', $skuIds, 'IN')->find();
        $stocksMap = array();
        $skusMap = array();
        array_map(function($stock)use(&$stocksMap) {
            $stocksMap[$stock->mId] = $stock->getData();
        }, $stocks);
        array_map(function($sku)use(&$skusMap) {
            $skusMap[$sku->mId] = $sku->getData();
        }, $skus);
        $orders = array_map(function($order)use($stocksMap, $skusMap) {
            $order = $order->getData();
            $order['stockObj'] = $stocksMap[$order['stock_id']];
            $order['skuObj'] = $skusMap[$order['stock_amount_id']];
            return $order;
        }, $orders);
        $usersMap = array();
        array_map(function($order)use(&$usersMap) {
            $usersMap[md5($order['name'].$order['phone'].$order['cellphone'].$order['country'].$order['province'].$order['city'].$order['addr'])][] = $order;
        }, $orders);
        ksort($usersMap);
        return $usersMap;
    }
    public function getPayment($model,$pageAdmin,$modelDataList){
        static $_payments;
        if(!is_array($_payments)){
            $paymentids=[];
            foreach($modelDataList as $_model){
                $paymentids[]=$_model->mPaymentId;
                $paymentids[]=$_model->mPrePaymentId;
            }
            $payment=new Payment();
            $_payments=$payment->addWhere("id",$paymentids,'in')->find();
            if(!$_payments){
                $_payments=[];
            }
        }
        $payment=null;
        $prepayment=null;
        foreach($_payments as $_payment){
            if($model->mPaymentId==$_payment->mId){
                $payment=$_payment;
            }
            if($model->mPrePaymentId==$_payment->mId){
                $prepayment=$_payment;
            }
        }
        return [$payment,$prepayment];
    }
}



