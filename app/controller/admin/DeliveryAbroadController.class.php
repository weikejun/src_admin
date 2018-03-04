<?php

class DeliveryAbroadController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new DeliveryAbroad();
        $this->model->setAutoClear(false);
        $this->model->orderBy('delivery_time', 'DESC');
        $this->model->orderBy('id', 'DESC'); // mysql排序问题，如果delivery_time相同，排序好像不稳定？
        self::$_objCache = array(
            'live' => [],
            'buyer' => [],
            'pack' => [],
            'stock' => [],
            'sku' => []
        );

        $this->form=new Form(array(
            array('name'=>'order_id','label'=>'商品ID','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'buyer_id','label'=>'买手ID','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'pack_id','label'=>'包裹ID','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'sku_id','label'=>'SKU ID','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'live_id','label'=>'直播ID','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'delivery_time','label'=>'发货时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
        ));
        $this->list_display=array(
            ['label'=>'买手发货时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mDeliveryTime);
            }],
            ['label'=>'入库时间','field'=>function($model){
                $storage = new Storage;
                $storage->addWhere('in_time', 0, '>');
                $storage = self::_getResource($model->mOrderId, 'storage', $storage, 'order_id');
                if($storage && $storage[0]->mInTime) {
                    return date('Y-m-d H:i:s', $storage[0]->mInTime);
                }
            }],
            ['label'=>'入库情况','field'=>function($model){
                $storage = self::_getResource($model->mOrderId, 'storage', $storage, 'order_id');
                if($storage) {
                    $storage = $storage[0];
                } else {
                    return;
                }
                $stockDesc = '';
                $stockStatus = Storage::getStockStatus();
                foreach($stockStatus as $status) {
                    if($storage->mStockStatus == $status[0]) {
                        $stockDesc = $status[1];
                    }
                }
                $memo = "";
                if($storage->mMemo) {
                    $memo .= "问题：".$storage->mMemo;
                    $imgs = json_decode($storage->mImgs, true);
                    if($imgs) {
                        foreach($imgs as $i => $img) {
                            $memo .= ' <a target="_blank" href="'.$img.'">图'.($i+1).'</a>';
                        }
                    }
                }
                if($storage->mPuStatus) {
                    $memo = $memo . '<br />采购：'.$storage->mPuMemo;
                }
                if($storage->mCsStatus) {
                    $memo = $memo . '<br />客服：'.$storage->mCsMemo;
                }
                if($storage->mStockStatus != 'normal') {
                    if($storage->mPuStatus&&$storage->mCsStatus) {
                        $memo = '处理完毕<br />' . $memo;
                    } else {
                        $memo = '<font color="red">处理中</font><br />' . $memo;
                    }
                }
                return ($storage->mStockStatus != 'normal' ? "<font color='red'>$stockDesc</font>" : $stockDesc) . "<br />$memo";
            }],
            ['label'=>'结算标志','field'=>function($model){
                foreach(DeliveryAbroad::getStatusChoice() as $status) {
                    if($status[0] === $model->mStatus) {
                        return $status[1];
                    }
                }
                return $model->mStatus;
            }],
            ['label'=>'订单ID','field'=>function($model){
                return $model->mOrderId;
            }],
            ['label'=>'买手ID','field'=>function($model){
                return '<a href="/admin/deliveryAbroad?__filter='.urlencode('buyer_id='.$model->mBuyerId).'">'.$model->mBuyerId.'</a>';
            }],
            ['label'=>'买手名','field'=>function($model){
                $res = self::_getResource($model->mBuyerId, 'buyer', new Buyer);
                return $res->mName;
            }],
            ['label'=>'买手真名','field'=>function($model){
                $res = self::_getResource($model->mBuyerId, 'buyer', new Buyer);
                return $res->mRealName;
            }],
            ['label'=>'商品名称','field'=>function($model){
                $res = self::_getResource($model->mStockId, 'stock', new Stock);
                return $res->mName;
            }],
            ['label'=>'SKU描述','field'=>function($model){
                $res = self::_getResource($model->mSkuId, 'sku', new StockAmount);
                return str_replace("\t", "/", $res->mSkuValue);
            }],
            ['label'=>'入库数量','field'=>function($model){
                return 1;
            }],
            ['label'=>'订单金额','field'=>function($model){
                $res = self::_getResource($model->mOrderId, 'order', new Order);
                return $res->mSumPrice;
            }],
            ['label'=>'预付款比率','field'=>function($model){
                $res = self::_getResource($model->mBuyerId, 'buyer', new Buyer);
                return $res->mShipPercent;
            }],
            ['label'=>'预付款金额','field'=>function($model){
                $buyer = self::_getResource($model->mBuyerId, 'buyer', new Buyer);
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                return sprintf("%.2f", floatval($buyer->mShipPercent)/100 * floatval($order->mSumPrice));
            }],
            ['label'=>'进货成本','field'=>function($model){
                $res = self::_getResource($model->mStockId, 'stock', new Stock);
                return $res->mPricein;
            }],
            ['label'=>'进货成本货币','field'=>function($model){
                $res = self::_getResource($model->mStockId, 'stock', new Stock);
                return $res->mPriceinUnit;
            }],
            ['label'=>'包裹ID','field'=>function($model){
                return '<a href="/admin/deliveryAbroad?__filter='.urlencode('pack_id='.$model->mPackId).'">'.$model->mPackId.'</a>';
            }],
            ['label'=>'快递公司','field'=>function($model){
                $res = self::_getResource($model->mPackId, 'pack', new Pack);
                return $res->mLogisticProvider;
            }],
            ['label'=>'快递单号','field'=>function($model){
                $res = self::_getResource($model->mPackId, 'pack', new Pack);
                return $res->mLogisticNo;
            }],
            ['label'=>'直播ID','field'=>function($model){
                return '<a href="/admin/deliveryAbroad?__filter='.urlencode('live_id='.$model->mLiveId).'">'.$model->mLiveId.'</a>';
            }],
            ['label'=>'挑款师','field'=>function($model){
                $res = self::_getResource($model->mLiveId, 'live', new Live);
                return $res->mSelector;
            }],
            ['label'=>'直播开始时间','field'=>function($model){
                $live = self::_getResource($model->mLiveId, 'live', new Live);
                return date('Y-m-d H:i:s', $live->mStartTime);
            }],
            ['label'=>'直播结束时间','field'=>function($model){
                $live = self::_getResource($model->mLiveId, 'live', new Live);
                return date('Y-m-d H:i:s', $live->mEndTime);
            }],
        );

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'订单ID','paramName'=>'order_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'直播ID','paramName'=>'live_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'买手ID','paramName'=>'buyer_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'包裹ID','paramName'=>'pack_id','fusion'=>false]),
            new Page_Admin_ChoiceFilter(['name'=>'结算标志','paramName'=>'status','choices'=>DeliveryAbroad::getStatusChoice()]),
            new Page_Admin_TimeRangeFilter(['name'=>'买手发货时间','paramName'=>'delivery_time']),
            new Page_Admin_TimeRangeFilter(['name'=>'入库时间','paramName'=>'in_time|order_id','foreignTable'=>'Storage','inKey'=>'order_id']),
        );

        $this->single_actions=[
            ['label'=>'结算预付款','confirm'=>'确认结算该订单的预付款么？','target'=>'_self', 'action'=>function($model){
                return '/admin/DeliveryAbroad/finishPay?status=1&ids='.$model->mId;
            },'enable' => function($model) {
                return $model->mStatus == 0 && $model->mStatus != 3 ? true : false;
            }],
            ['label'=>'结算全款','confirm'=>'确认结算该订单的全款么？','target'=>'_self', 'action'=>function($model){
                return '/admin/DeliveryAbroad/finishPay?status=2&ids='.$model->mId;
            },'enable' => function($model) {
                return in_array($model->mStatus, [0, 1]) && $model->mStatus != 3 ? true : false;
            }],
            ['label'=>'恢复未结算','confirm'=>'确认恢复该订单为未结算么？','target'=>'_self', 'action'=>function($model){
                return '/admin/DeliveryAbroad/finishPay?status=0&ids='.$model->mId;
            },'enable' => function($model) {
                return $model->mStatus != 0 ? true : false;
            }],
            ['label'=>'取消结算','confirm'=>'确认取消该订单的结算么？','target'=>'_self', 'action'=>function($model){
                return '/admin/DeliveryAbroad/finishPay?status=3&ids='.$model->mId;
            },'enable' => function($model) {
                return $model->mStatus != 3 ? true : false;
            }],
        ];

        $this->single_actions_default = [
            'edit' => false,
            'delete' => false,
        ];

        $this->multi_actions=array(
            array('label'=>'批量结算预付款','action'=>'/admin/deliveryAbroad/finishPay?status=1&ids=__ids__'),
            array('label'=>'批量结算全款','action'=>'/admin/deliveryAbroad/finishPay?status=2&ids=__ids__'),
            array('label'=>'批量取消结算','action'=>'/admin/deliveryAbroad/finishPay?status=3&ids=__ids__'),
            array('label'=>'导出全部记录','required'=>false,'action'=>'/admin/deliveryAbroad/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        );
    }

    use ExportToCsvAction;

    public function finishPayAction() {
        $ids = $this->_GET('ids', 0);
        if(empty($ids)) {
            echo 'ID不正确';
            exit;
        }
        $statusMap = DeliveryAbroad::getStatusChoice();
        $status = $this->_GET('status');
        $statusVaild = false;
        foreach($statusMap as $statusItem) {
            if($status == $statusItem[0]) {
                $statusValid = true;
                break;
            }
        }
        if(!$statusValid) {
            echo '请求不正确';
            exit;
        }
        $finder = new DeliveryAbroad;
        $finder
            ->addWhere('id', explode(',', $ids), 'IN')
            ->update(['status' => $status, 'pay_time' => time()]);
        return array("admin/delivery_abroad/finish_pay.tpl", array('back_url' => $this->_REQUEST('__success_url',Utils::get_default_back_url())));
    }
}

