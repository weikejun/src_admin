<?php
class StorageOutController extends Page_Admin_Base {
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
        $fields = array();
        return array_values($fields);
    }

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        //$this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Storage();
        $this->model->orderBy('status', 'desc')->orderBy('order_id', 'asc');
        $this->bindModelEvent();
        self::$PAGE_SIZE=20;

        $filterStr = $this->_GET('__filter');
        if($filterStr) {
            $filters = explode('&', $filterStr);
            foreach($filters as $filter) {
                list($fKey, $fVal) = explode('=', $filter);
                if($fKey == 'logistic_no|logistic_id') {
                    $logistics = new Logistic;
                    $logistics = $logistics->addWhere('logistic_no', $fVal)->setCols(['id', 'order_id'])->groupBy('order_id')->findMap('order_id');
                    foreach($logistics as $orderId => $logistic) {
                        $storage = new Storage;
                        $storage = $storage->addWhere('order_id', $orderId)->addWhere('status', 'waiting')->update(
                            [
                                'memo' => '系统自动操作入库',
                                'status' => 'in',
                                'in_time' => time(),
                            ]
                        );
                        $storage = new Storage;
                        $storage = $storage->addWhere('order_id', $orderId)->addWhere('status', 'in')->update(
                            [
                                'logistic_id' => $logistic->mId, 
                                'status' => 'out',
                                'out_time' => time(),
                            ]
                        );
                        $order = new Order;
                        $order = $order->addWhere('id', $orderId)->select();
                        if($order) {
                            if($order->mStatus != 'demostic') {
                                $order->mStatus = 'demostic';
                                $order->mUpdateTime = time();
                                $order->save();
                                GlobalMethod::orderLog($order, '', 'admin', Admin::getCurrentAdmin()->mId);
                            }
                            $order->mStatus = 'to_user';
                            $order->mUpdateTime = time();
                            $order->save();
                            GlobalMethod::orderLog($order, '', 'admin', Admin::getCurrentAdmin()->mId);

                            //状态同步到pay_order add by hongjie
                            $payOrderInfo['mStatus'] = 'payed';
                            $payOrderInfo['id'] = $order->mPayOrderId;
                            $payOrder = PayOrder::updatePayOrder($payOrderInfo); 
                            if ($payOrder) {
                                GlobalMethod::orderLog($payOrder, '', 'admin', Admin::getCurrentAdmin()->mId, 1);
                            }
                        }
                    }
                }
                break;
            }
        }

        $this->form=new Form($this->_setForm($this->_GET('type')));
        $this->list_display=[
            ['label'=>'库存ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'订单ID','field'=>function($model){
                return $model->mOrderId;
            }],
            ['label'=>'入库时间','field'=>function($model){
                return $model->mInTime ? date('Y-m-d H:i', $model->mInTime) : '';
            }],
            ['label'=>'出库时间','field'=>function($model){
                return $model->mOutTime ? date('Y-m-d H:i', $model->mOutTime) : '';
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
            ['label'=>'国内物流','field'=>function($model){
                $logistic = self::_getResource($model->mLogisticId, 'logistic', new Logistic);
                return $logistic->mLogisticProvider . '<br />' . $logistic->mLogisticNo;
            }],
        ];
        
        $this->list_filter=array(
            new Page_Admin_TextForeignFilter(['name'=>'国内快递单号','paramName'=>'logistic_no|logistic_id','fusion'=>true,'foreignTable'=>'Logistic']),
        );

        $this->hide_action_new = true;

        $this->single_actions_default = [
            'edit' => false, 
            'delete' => false
        ];

        $this->single_actions=[
        ];
    }
}
