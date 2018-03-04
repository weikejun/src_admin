<?php
class StoragePurchasePendingController extends Page_Admin_Base {
    public function bindModelEvent(){
        $this->model->on("after_update",function($model){
            if($model->mPuStatus == '4') {
                $storage = new Storage;
                $storage = $storage->addWhere('id', $model->mId)->select();
                if($storage) {
                    //$storage->mStockStatus = 'normal';
                    //$storage->mPuStatus = 0;
                    $storage->save();
                }
            }
        });
    }

    private function _setForm($type = 0) {
        $fields = array(
            'pu_status' => array('name'=>'pu_status','label'=>'处理状态','type'=>"choice",'choices'=>[Storage::getPurchaseStatus()[$type]], 'default'=>$type,'required'=>false,),
            'memo' => array('name'=>'memo','label'=>'问题描述','type'=>"text",'default'=>null,'required'=>false,'readonly'=>true),
            'imgs' => array('name'=>'imgs','label'=>'问题图片','type'=>"simpleJsonFiles",'default'=>null,'null'=>false,'required'=>false,),
            'pu_memo' => array('name'=>'pu_memo','label'=>'处理意见','type'=>"textarea",'default'=>'买手补发，买手不补发，通知客人退款','required'=>false,),
            'pending_time' => array('name'=>'pending_time','label'=>'问题时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            'pu_time' => array('name'=>'pu_time','label'=>'处理时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,'auto_update'=>true),
        );
        return array_values($fields);
    }

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Storage();
        $this->model->setAutoClear(false);
        $this->model->addWhereRaw('(stock_status != "normal" or pu_status != 0)');
        $this->bindModelEvent();

        $this->form=new Form($this->_setForm($this->_GET('type', 0)));
        $this->list_display=[
            ['label'=>'问题描述','field'=>function($model){
                $memo = $model->mMemo;
                $imgs = json_decode($model->mImgs, true);
                if($imgs) {
                    foreach($imgs as $i => $img) {
                        $memo .= ' <a target="_blank" href="'.$img.'">图'.($i+1).'</a>';
                    }
                }
                return $memo;
            }],
            ['label'=>'处理状态','field'=>function($model){
                $puStatus = Storage::getPurchaseStatus();
                $statusDesc = '';
                foreach($puStatus as $status) {
                    if($model->mPuStatus == $status[0]) {
                        $statusDesc = $status[1];
                    }
                }
                return $statusDesc;
            }],
            ['label'=>'处理时间','field'=>function($model){
                return $model->mPuTime ? date("Y-m-d H:i:s", $model->mPuTime) : '';
            }],
            ['label'=>'处理结果','field'=>function($model){
                return $model->mPuMemo;
            }],
            ['label'=>'买手发货时间','field'=>function($model){
                return $model->mSendTime ? date('Y-m-d H:i', $model->mSendTime) : '';
            }],
            ['label'=>'国际物流','field'=>function($model){
                $pack = self::_getResource($model->mPackId, 'pack', new Pack);
                return $pack->mLogisticProvider . '<br />' . $pack->mLogisticNo;
            }],
            ['label'=>'收货信息','field'=>function($model){
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                if($order) {
                    return "用户ID：".$order->mUserId."<br />姓名：$order->mName<br />电话：$order->mPhone $order->mCellphone<br />地址：$order->mProvince,$order->mCity,$order->mAddr";
                } 
                return '';
            }],
            ['label'=>'订单信息','field'=>function($model){
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                return "订单ID：" . $model->mOrderId . "<br />订单金额：" . $order->mSumPrice . "<br />下单时间：" . date("Y-m-d H:i:s", $order->mCreateTime) . "<br />买家备忘：" . $order->mNote . "<br />系统备忘：" . $order->mSysNote;
            }],
            ['label'=>'商品信息','field'=>function($model){
                $buyer = self::_getResource($model->mBuyerId, 'buyer', new Buyer);
                $order = self::_getResource($model->mOrderId, 'order', new Order);
                $stock = self::_getResource($order->mStockId, 'stock', new Stock);
                $live = self::_getResource($order->mLiveId, 'stock', new Live);
                $stockAmount = self::_getResource($order->mStockAmountId, 'stockAmount', new StockAmount);
                $skuMeta = json_decode($stock->mSkuMeta, true);
                $imgs = json_decode($stock->mImgs, true);
                $imgLinks = [];
                foreach($imgs as $index => $img) {
                    $imgLinks[] = '<a href="'.$img.'" target=_blank>图'.($index+1).'</a>';
                }
                return "买手：" . $buyer->mName . "<br />挑款：" . $live->mSelector . "<br />直播：<a href='/admin/live?action=read&id=".$live->mId."' target=_blank>" . $live->mName . "</a><br />商品：<a href='/admin/stock?action=read&id=".$stock->mId."' target=_blank>" . $stock->mName . '</a><br />SKU：'. implode("/", array_keys($skuMeta)) . "-" . $stockAmount->mSkuValue . '<br />图片：'.implode(' ', $imgLinks);
            }],
        ];
        
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'买手ID','paramName'=>'buyer_id','fusion'=>false,'in'=>true]),
            new Page_Admin_ChoiceFilter(['name'=>'处理状态','paramName'=>'pu_status','choices'=>Storage::getPurchaseStatus()]),
        );

        $this->single_actions_default = [
            'edit' => false, 
            'delete' => false
        ];

        $this->single_actions=[
            ['label'=>'完成处理', 'target'=>'_self', 'action'=>function($model){
                return '/admin/storagePurchasePending?action=read&type=1&id='.$model->mId;
            },'enable' => function($model) {
                return $model->mPuStatus == 0 && $model->mStockStatus == 'pending'  ? true : false;
            }],
            ['label'=>'补充备注', 'target'=>'_self', 'action'=>function($model){
                return '/admin/storagePurchasePending?action=read&type=1&id='.$model->mId;
            },'enable' => function($model) {
                return $model->mPuStatus == 1 ? true : false;
            }],
        ];
    }
}
