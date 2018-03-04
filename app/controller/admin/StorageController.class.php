<?php
class StorageController extends Page_Admin_Base {
    public function bindModelEvent(){
        $this->model->on("after_update",function($model){
            if(($model->mStatus != "in" && $model->mStatus != "out") || !$model->mOrderId) {
                return;
            }
            $order = new Order;
            $order = $order->addWhere('id', $model->mOrderId)->select();
            if($order) {
                $order->mStatus = ($model->mStatus == "in" ? 'demostic' : 'to_user');
                $order->mUpdateTime = time();
                $order->save();
                GlobalMethod::orderLog($order, '', 'admin', Admin::getCurrentAdmin()->mId);
            }
        });
    }

    private function _setForm($type = null) {
        $fields = array(
            'order_id' => array('name'=>'order_id','label'=>'订单ID','type'=>"choosemodel",'model'=>'Order','default'=>null,'required'=>false,),
            'pack_id' => array('name'=>'pack_id','label'=>'包裹ID','type'=>"choosemodel",'model'=>'Pack','default'=>null,'required'=>false,),
            'logistic_id' => array('name'=>'logistic_id','label'=>'国内物流ID','type'=>"choosemodel",'model'=>'Logistic','default'=>null,'required'=>false,),
            'status' => array('name'=>'status','label'=>'库存状态','type'=>"choice",'choices'=>Storage::getAllStatus(), 'default'=>null,'required'=>false,),
            'stock_status' => array('name'=>'stock_status','label'=>'商品状态','type'=>"choice",'choices'=>Storage::getStockStatus(), 'default'=>null,'required'=>false,),
            'pu_status' => array('name'=>'pu_status','label'=>'采购处理','type'=>"choice",'choices'=>Storage::getPurchaseStatus(), 'default'=>null,'required'=>false,),
            'location' => array('name'=>'location','label'=>'货架分配','type'=>"text",'default'=>null,'required'=>false,),
            'create_time' => array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            'pending_time' => array('name'=>'pending_time','label'=>'操作时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            'in_time' => array('name'=>'in_time','label'=>'入库时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            'out_time' => array('name'=>'out_time','label'=>'出库时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            'memo' => array('name'=>'memo','label'=>'问题记录','type'=>"textarea",'default'=>null,'required'=>false,),
            'imgs' => array('name'=>'imgs','label'=>'问题图片','type'=>"simpleJsonFiles",'default'=>null,'null'=>false,'required'=>false,),
        );
        switch($type) {
        case 'in':
            $statusFields = $fields['status'];
            $statusFields['choices'] = [['in', '已收货']];
            $statusFields['checked'] = 'in';
            $orderIdFields = $fields['order_id'];
            $orderIdFields['type'] = 'hidden';
            return [
                $statusFields,
                $orderIdFields,
                $fields['stock_status'],
                $fields['memo'],
                $fields['imgs'],
                $fields['location'],
                $fields['in_time'],
            ];
        case 'cancel':
            $statusFields = $fields['status'];
            $statusFields['choices'] = [['canceled', '取消']];
            $statusFields['checked'] = 'canceled';
            return [
                $statusFields,
                $fields['memo'],
                $fields['pending_time'],
            ];
        default:
            return array_values($fields);
        }
    }

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Storage();
        $this->model->orderBy('status', 'desc')->orderBy('order_id', 'asc');
        $this->bindModelEvent();
        self::$PAGE_SIZE=20;

        $this->form=new Form($this->_setForm($this->_GET('type')));
        $this->list_display=[
            ['label'=>'库存ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'订单ID','field'=>function($model){
                return $model->mOrderId;
            }],
            ['label'=>'商品信息','field'=>function($model){
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                $stock = self::_getResource($order->mStockId, 'stock', new Stock);
                $stockAmount = self::_getResource($order->mStockAmountId, 'stockAmount', new StockAmount);
                $skuMeta = json_decode($stock->mSkuMeta, true);
                $imgs = json_decode($stock->mImgs, true);
                $imgLinks = [];
                foreach($imgs as $index => $img) {
                    $imgLinks[] = '<a href="'.$img.'" target=_blank>图'.($index+1).'</a>';
                }
                return "<a href='/admin/storage?__filter=".urlencode("stock_id|order_id=".$stock->mId)."'>" . $stock->mName . '</a><br />'. implode("/", array_keys($skuMeta)) . "-" . $stockAmount->mSkuValue . '<br />图片：'.implode(' ', $imgLinks);
            }],
            ['label'=>'发货时间','field'=>function($model){
                return $model->mSendTime ? date('Y-m-d H:i', $model->mSendTime) : '';
            }],
            ['label'=>'入库时间','field'=>function($model){
                return $model->mInTime ? date('Y-m-d H:i', $model->mInTime) : '';
            }],
            ['label'=>'出库时间','field'=>function($model){
                return $model->mOutTime ? date('Y-m-d H:i', $model->mOutTime) : '';
            }],
            ['label'=>'订单状态','field'=>function($model){
                $refunds = $this->_getResource($model->mOrderId, 'UserRefund', new UserRefund, 'order_id');
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
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                $memo = '<font color="red">' . $order->mNote . $order->mSysNote . '</font>';
                foreach(Order::getAllStatus() as $status){
                    if($order->mStatus==$status[0]){
                        if($refund && !in_array($refund->mStatus, [2, 3, 4])) {
                            return $status[1].'，<font color=red>全额退款申请中</font><a href="/admin/UserRefund?action=read&type=cancel&id='.$refund->mId.'">[取消退款]</a>'.$refundDesc.'<br />'.$memo;
                        } else {
                            return $status[1].$refundDesc.'<br />'.$memo;
                        }
                    }
                }
            }],
            ['label'=>'库存状态','field'=>function($model){
                $allStatus = Storage::getAllStatus();
                $statusDesc = '';
                foreach($allStatus as $status) {
                    if($model->mStatus == $status[0]) {
                        $statusDesc = $status[1];
                    }
                }
                return $statusDesc;
            }],
            ['label'=>'商品状态','field'=>function($model){
                $stockDesc = '';
                $stockStatus = Storage::getStockStatus();
                foreach($stockStatus as $status) {
                    if($model->mStockStatus == $status[0]) {
                        $stockDesc = $status[1];
                    }
                }
                return $stockDesc;
            }],
            ['label'=>'问题跟踪','field'=>function($model){
                $memo = '';
                if($model->mMemo) {
                    $memo = "问题：".$model->mMemo;
                    $imgs = json_decode($model->mImgs, true);
                    if($imgs) {
                        foreach($imgs as $i => $img) {
                            $memo .= ' <a target="_blank" href="'.$img.'">图'.($i+1).'</a>';
                        }
                    }
                }
                if($model->mPuStatus) {
                    $memo = $memo . '<br />采购：'.$model->mPuMemo;
                }
                if($model->mCsStatus) {
                    $memo = $memo . '<br />客服：'.$model->mCsMemo;
                }
                if($model->mStockStatus != 'normal') {
                    if($model->mPuStatus&&$model->mCsStatus) {
                        $memo = '处理完毕<br />' . $memo;
                    } else {
                        $memo = '<font color="red">处理中</font><br />' . $memo;
                    }
                }
                return $memo;
            }],
            ['label'=>'收货信息','field'=>function($model){
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                if($order) {
                    return "$order->mName<br />$order->mPhone $order->mCellphone<br />$order->mProvince,$order->mCity,$order->mAddr";
                } 
                return '';
            }],
            ['label'=>'国际物流','field'=>function($model){
                $pack = self::_getResource($model->mPackId, 'pack', new Pack);
                return $pack->mLogisticProvider . '<br />' . $pack->mLogisticNo;
            }],
            ['label'=>'国内物流','field'=>function($model){
                $logistic = self::_getResource($model->mLogisticId, 'logistic', new Logistic);
                return $logistic->mLogisticProvider . '<br />' . $logistic->mLogisticNo;
            }],
            ['label'=>'包裹信息','field'=>function($model){
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                $buyer = self::_getResource($model->mBuyerId, 'buyer', new Buyer);
                $live = self::_getResource($order->mLiveId, 'live', new Live);
                $pack = self::_getResource($model->mPackId, 'pack', new Pack);
                return "包裹ID：".$model->mPackId."<br />包裹名：".$pack->mName . "<br />买手：" . $buyer->mName . "<br />挑款：" . $live->mSelector . "<br />直播：<a href='/admin/storage?__filter=".urlencode("live_id|order_id=".$live->mId)."'>" . $live->mId . "</a>";
            }],
            ['label'=>'货架分配','field'=>function($model){
                return $model->mLocation;
            }],
        ];
        
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'包裹ID','paramName'=>'pack_id','fusion'=>false,'in'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'包裹名','paramName'=>'name|pack_id','fusion'=>true,'foreignTable'=>'Pack']),
            new Page_Admin_TextForeignFilter(['name'=>'海外快递单号','paramName'=>'logistic_no|pack_id','fusion'=>true,'foreignTable'=>'Pack']),
            new Page_Admin_TextForeignFilter(['name'=>'直播ID','paramName'=>'live_id|order_id','fusion'=>true,'foreignTable'=>'Order']),
            new Page_Admin_TextForeignFilter(['name'=>'商品ID','paramName'=>'stock_id|order_id','fusion'=>true,'foreignTable'=>'Order']),
            new Page_Admin_TextFilter(['name'=>'订单ID','paramName'=>'order_id','fusion'=>false,'in'=>true]),
            new Page_Admin_TextFilter(['name'=>'买手ID','paramName'=>'buyer_id','fusion'=>false,'in'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>'入库时间','paramName'=>'in_time']),
            new Page_Admin_TimeRangeFilter(['name'=>'出库时间','paramName'=>'out_time']),
            new Page_Admin_ChoiceFilter(['name'=>'库存状态','paramName'=>'status','choices'=>Storage::getAllStatus()]),
            new Page_Admin_ChoiceFilter(['name'=>'商品状态','paramName'=>'stock_status','choices'=>Storage::getStockStatus()]),
        );

        $this->single_actions_default = [
            'edit' => false, 
            'delete' => false
        ];

        $this->multi_actions=array(
            array('label'=>'批量打印快递单','action'=>'/admin/storage/printSelect?ids=__ids__','target'=>'_blank'),
            array('label'=>'批量打印发货单','action'=>'/admin/storage/printSelectSend?ids=__ids__','target'=>'blank'),
        );

        $this->single_actions=[
            ['label'=>'收货', 'target'=>'_self', 'action'=>function($model){
                return '/admin/storage?action=read&type=in&id='.$model->mId;
            },'enable' => function($model) {
                return ($model->mStatus == 'waiting') ? true : false;
            }],
            ['label'=>'发货', 'target'=>'_self', 'action'=>function($model){
                return '/admin/logistic?action=read'.($model->mLogisticId?"&id=$model->mLogisticId":'').'&fields='.urlencode('order_id='.$model->mOrderId);
            },'enable' => function($model) {
                return $model->mStatus == 'in' ? true : false;
            }],
            ['label'=>'取消', 'target'=>'_self', 'action'=>function($model){
                return '/admin/storage?action=read&type=cancel&id='.$model->mId;
            },'enable' => function($model) {
                return ($model->mStatus != 'canceled') ? true : false;
            }],
            ['label'=>'打印快递单', 'target'=>'_blank', 'action'=>function($model){
                return '/admin/storage/printSelect?ids='.$model->mId;
            },'enable' => function($model) {
                return $model->mStatus == 'in' ? true : false;
            }],
            ['label'=>'打印发货单', 'target'=>'_blank', 'action'=>function($model){
                return '/admin/storage/loss?ids='.$model->mId;
            },'enable' => function($model) {
                return $model->mStatus == 'in' ? true : false;
            }],
            ['label'=>'查看订单', 'target'=>'_blank', 'action'=>function($model){
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                return '/admin/order?action=read&id='.$order->mId;
            }]
        ];
    }

    public function printSelectAction() {
        $ids = $this->_GET('ids', 0);
        if(empty($ids)) {
            echo 'ID不正确';
            exit;
        }
        $finder = new Storage;
        $orderIds = $finder->addWhere('id', explode(',', $ids), 'in')->setCols(['order_id'])->findMap('order_id');
        if(empty($orderIds)) {
            echo 'ID不正确';
            exit;
        }
        return ['redirect: /admin/order/printSelect?ids='.implode(',', array_keys($orderIds))];
        //return ['redirect: /admin/expressPrint?action=read&fields='.urlencode('storage_ids='.$ids)];
    }

    public function printSelectSendAction() {
        $ids = $this->_GET('ids', 0);
        if(empty($ids)) {
            echo 'ID不正确';
            exit;
        }
        $finder = new Storage;
        $orderIds = $finder->addWhere('id', explode(',', $ids), 'in')->setCols(['order_id'])->findMap('order_id');
        if(empty($orderIds)) {
            echo 'ID不正确';
            exit;
        }
        return ['redirect: /admin/expressPrint?action=read&fields='.urlencode('storage_ids='.$ids)];
        //return ['redirect: /admin/order/print?vendor=_sender&ids='.implode(',', array_keys($orderIds))];
    }
}
