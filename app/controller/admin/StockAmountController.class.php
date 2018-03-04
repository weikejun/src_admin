<?php

class StockAmountController extends Page_Admin_Base {
    use Page_Admin_InlineBase;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new StockAmount();
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'stock_id','label'=>'商品ID','type'=>"text",'readonly'=>true,'default'=>null,'required'=>true,),
            array('name'=>'sku_value','label'=>'SKU描述','type'=>"textarea",'readonly'=>true,'default'=>null,'null'=>false,),
            array('name'=>'amount','label'=>'剩余库存','type'=>"text",'default'=>null,'null'=>false,),
            array('name'=>'locked_amount','label'=>'锁定库存','type'=>"text",'readonly'=>true,'default'=>null,'null'=>false,),
            array('name'=>'sold_amount','label'=>'已售库存','type'=>"text",'readonly'=>true,'default'=>null,'null'=>false,),
        ));
        $this->list_display=array(
            ['label'=>'SKU ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'商品ID','field'=>function($model){
                return $model->mStockId;
            }],
            ['label'=>'SKU描述','field'=>function($model){
                return $model->mSkuValue;
            }],
            ['label'=>'剩余库存','field'=>function($model){
                return $model->mAmount;
            }],
            ['label'=>'锁定库存','field'=>function($model){
                return $model->mlockedAmount;
            }],
            ['label'=>'已售库存','field'=>function($model){
                return $model->msoldAmount;
            }],
        );

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'SKU ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'商品ID','paramName'=>'stock_id','fusion'=>false]),
        );
        /*
        $this->inline_admin=array(
            new StockAmountController($this,'site_id'),
        );
        $this->list_filter=array(
            new Admin_SiteTagsFilter()
        );
        $this->multi_actions=array(
            array('label'=>'添加到module','action'=>'javascript:add_to_module();return false;'),
        );
         */
        $this->single_actions=[
            ['label'=>'商品','action'=>function($model){
                return '/admin/stock?__filter='.urlencode('id='.$model->mStockId);
            }],
        ];
        $this->single_actions_default = [
            'edit' => true,
            'delete' => false
        ];
    }
}

