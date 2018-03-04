<?php
class PackController extends Page_Admin_Base {

    private function _updateLogistic($model) {
        $logistic_provider_fixed = Logistic::getGlobalFixedProvider($model->mLogisticProvider);

        $pack = new Pack();
        $pack->addWhere('id', $model->mId)->update(array('logistic_provider_fixed'=>$logistic_provider_fixed));
        $logistic = new Logistic();
        $res = $logistic->registerLogic($model->mLogisticNo, $logistic_provider_fixed);
    }

    public function bindModelEvent(){
        $this->model->on("after_insert",function($model){
            $this->_updateLogistic($model);
        });
        $this->model->on("after_update",function($model){
            $this->_updateLogistic($model);
        });
    }

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Pack();
        $this->model->orderBy("id","desc");
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->bindModelEvent();

        $this->form=new Form(array(
            array('name'=>'name','label'=>'包裹名','type'=>"text",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'status','label'=>'状态',"choices"=>Pack::getAllStatus(), 'type'=>"choice",'default'=>'notapply','null'=>false,),
            array('name'=>'logistic_provider','label'=>'快递公司','type'=>"text",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'logistic_no','label'=>'快递单号','type'=>"text",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'imgs','label'=>'购物小票','type'=>"simpleJsonFiles",'default'=>null,'required'=>false,),
            array('name'=>'logistic_imgs','label'=>'快递扫描','type'=>"simpleJsonFiles",'default'=>null,'required'=>false,),
            array('name'=>'buyer_id','label'=>'买手ID','type'=>"text",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'live_id','label'=>'直播ID','type'=>'text','readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'logistic_price','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'logistic_price_unit','type'=>"choice","choices"=>Stock::getCurrencyUnit(),'default'=>'CNY','required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,'auto_update'=>true,),
            //array('name'=>'valid',"choices"=>array(array('valid',"有效"),array('invalid',"无效"),), 'type'=>"choice",'default'=>'valid','null'=>false,),
        ));
        $this->list_display=[
            ['label'=>'包裹ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'包裹名','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'发货状态','field'=>function($model){
                foreach(Pack::getAllStatus() as $status){
                    if($model->mStatus==$status[0]){
                        return $status[1];
                    }
                }
            }],
            ['label'=>'快递公司','field'=>function($model){
                return $model->mLogisticProvider;
            }],
            ['label'=>'快递单号','field'=>function($model){
                $imgs = json_decode($model->mLogisticImgs, true);
                $imgsStr = '';
                for($i = 0; $i < count($imgs); $i++) {
                    $imgsStr .= "&nbsp;&nbsp;<a href='".$imgs[$i]."' width='200px' target='_blank'>截图".($i+1)."</a>";
                }
                return $model->mLogisticNo.'<br />'.$imgsStr;
            }],
            ['label'=>'邮费','field'=>function($model){
                return $model->mLogisticPriceUnit."\t".$model->mLogisticPrice;
            }],
            ['label'=>'直播ID','field'=>function($model){
                return $model->mLiveIds;
            }],
            ['label'=>'买手ID','field'=>function($model){
                return $model->mBuyerId;
            }],
            ['label'=>'创建时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
            ['label'=>'更新时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mUpdateTime);
            }],
        ];
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'包裹名','paramName'=>'name','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'包裹ID','paramName'=>'id','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'直播ID','paramName'=>'live_ids','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'买手ID','paramName'=>'buyer_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'快递单号','paramName'=>'logistic_no','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>'创建时间','paramName'=>'create_time']),
            new Page_Admin_TimeRangeFilter(['name'=>'更新时间','paramName'=>'update_time']),
            new Page_Admin_ChoiceFilter(['name'=>'发货状态','paramName'=>'status','choices'=>Pack::getAllStatus()]),
        );
        $this->single_actions=[
            ['label'=>'订单','action'=>function($model){
                return '/admin/order?__filter='.urlencode("pack_id={$model->mId}");
            }]
        ];
        //$this->search_fields=array('name', 'id');
    }
}




