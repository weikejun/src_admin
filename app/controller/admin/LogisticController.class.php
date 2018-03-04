<?php
class LogisticController extends Page_Admin_Base {
    private function _doStorageOut($model) {
        $storage = new Storage;
        $storage = $storage->addWhere('order_id', $model->mOrderId)->update(
            [
                'logistic_id' => $model->mId, 
                'status' => 'out',
                'out_time' => time(),
            ]
        );
        $order = new Order;
        $order = $order->addWhere('id', $model->mOrderId)->select();
        if($order) {
            $order->mStatus = 'to_user';
            $order->mUpdateTime = time();
            $order->save();
            GlobalMethod::orderLog($order, '', 'admin', Admin::getCurrentAdmin()->mId);
        }

        //物流追踪订阅（快递100）
        $logistic = new Logistic();
        $logistic_provider_fixed = Logistic::getFixedProvider($model->mLogisticProvider);
        $logistic->addWhere('id', $model->mId)->update(array('logistic_provider_fixed'=>$logistic_provider_fixed));
        $res = $logistic->registerLogic($model->mLogisticNo, $logistic_provider_fixed);
    }
    public function bindModelEvent(){
        $this->model->on("after_insert",function($model){
            $this->_doStorageOut($model);
        });
        $this->model->on("after_update",function($model){
            $this->_doStorageOut($model);
        });
        $modelId = $this->_GET('id', 0);
        $this->model->on("after_delete",function($model)use($modelId){
            if(!$modelId) {
                return;
            }
            $storage = new Storage;
            $storage = $storage->addWhere('logistic_id', $modelId)->update(
                [
                    'logistic_id' => null, 
                    'status' => 'in',
                    'out_time' => null,
                ]
            );
        });
    }
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Logistic();
        $this->model->setAutoClear(false);
        $this->model->orderBy('id', 'desc');
        $this->bindModelEvent();

        $fieldsDefault=explode('&', $this->_GET('fields'));
        foreach($fieldsDefault as $field) {
            list($fKey, $fValue) = explode('=', $field);
            $this->fieldsDefault[$fKey] = $fValue;
        }

        $this->form=new Form(array(
            array('name'=>'order_id','label'=>'订单ID','type'=>"choosemodel",'model'=>'Order','default'=>$this->fieldsDefault['order_id'],'required'=>false,),
            //array('name'=>'live_id','label'=>'直播ID','type'=>"choosemodel",'model'=>'Live','default'=>null,'required'=>false,),
            //array('name'=>'user_id','type'=>"choosemodel",'model'=>'Live','default'=>null,'required'=>false,),
            //array('name'=>'buyer_id','type'=>"choosemodel",'model'=>'Live','default'=>null,'required'=>false,),
            array('name'=>'logistic_provider','label'=>'快递公司','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'logistic_no','label'=>'快递单号','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'logistic_price','label'=>'运费','type'=>"text",'default'=>0,'required'=>true,),
//            array('name'=>'receiver_name','type'=>"text",'default'=>null,'required'=>false,),
//            array('name'=>'receiver_addr','type'=>"text",'default'=>null,'required'=>false,),
//            array('name'=>'receiver_email','type'=>"text",'default'=>null,'required'=>false,),
//            array('name'=>'receiver_phone','type'=>"text",'default'=>null,'required'=>false,),
//            array('name'=>'sender_name','type'=>"text",'default'=>null,'required'=>false,),
//            array('name'=>'sender_addr','type'=>"text",'default'=>null,'required'=>false,),
//            array('name'=>'sender_email','type'=>"text",'default'=>null,'required'=>false,),
//            array('name'=>'sender_phone','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'create_time','label'=>'发货时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
//            array('name'=>'valid',"choices"=>array(array('valid',"有效"),array('invalid',"无效"),), 'type'=>"choice",'default'=>'valid','null'=>false,),
        ));
        $this->list_display=[
            ['label'=>'流水号','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'订单ID','field'=>function($model){
                return $model->mOrderId;
            }],
            ['label'=>'快递公司','field'=>function($model){
                return $model->mLogisticProvider;
            }],
            ['label'=>'快递单号','field'=>function($model){
                return $model->mLogisticNo;
            }],
            ['label'=>'运费','field'=>function($model){
                return $model->mLogisticPrice;
            }],
            ['label'=>'发货时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
        ];
        /*
        $this->list_filter=array(
            new Admin_SiteTagsFilter()
        );
        $this->inline_admin=array(
            new Page_Admin_InlineSiteModule($this,'site_id'),
        );
        $this->multi_actions=array(
            array('label'=>'添加到module','action'=>'javascript:add_to_module();return false;'),
        );*/
        
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'订单ID','paramName'=>'order_id','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'快递公司','paramName'=>'logistic_provider','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'快递单号','paramName'=>'logistic_no','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>'创建时间','paramName'=>'create_time']),
        );

        $this->single_actions=[
            ['label'=>'订单','action'=>function($model){
                return '/admin/order?__filter='.urlencode('id='.$model->mOrderId);
            }],
        ];
    }

    //测试kuaidi100的推送接口
    //by boshen@20141209
    public function testPushAction() {
        $logistic_no = '905625047466';
        $logistic_provider = 'shunfeng';
        $to = '广东,清远,佛冈县106国道城东派出所';

        $logistic = new Logistic();
        $res = $logistic->registerLogic($logistic_no, $logistic_provider, $to);
        //var_dump($res); exit;

        return $res;
    }
}
