<?php
class StockFeedbackController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new StockFeedback();
        $this->model->orderBy("id","desc");
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'stock_id','label'=>'商品ID','type'=>'text','readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'live_id','label'=>'直播ID','type'=>"text",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'user_id','label'=>'用户ID','type'=>"text",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'type','label'=>'反馈类型','type'=>"text",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'info','label'=>'反馈文字','type'=>"text",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            //array('name'=>'valid',"choices"=>array(array('valid',"有效"),array('invalid',"无效"),), 'type'=>"choice",'default'=>'valid','null'=>false,),
        ));
        $this->list_display=[
            ['label'=>'反馈ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'商品ID','field'=>function($model){
                return "<a href='/admin/stock?__filter=id%3D{$model->mStockId}'>{$model->mStockId}</a>";
            }],
            ['label'=>'直播ID','field'=>function($model){
                return "<a href='/admin/live?__filter=id%3D{$model->mLiveId}'>{$model->mLiveId}</a>";
            }],
            ['label'=>'买手ID','field'=>function($model){
                return "<a href='/admin/buyer?__filter=id%3D{$model->mBuyerId}'>{$model->mBuyerId}</a>";
            }],
            ['label'=>'用户ID','field'=>function($model){
                return "<a href='/admin/user?__filter=id%3D{$model->mUserId}'>{$model->mUserId}</a>";
            }],
            ['label'=>'反馈类型','field'=>function($model){
                return htmlspecialchars($model->mType);
            }],
            ['label'=>'反馈文字','field'=>function($model){
                return htmlspecialchars($model->mInfo);
            }],
            ['label'=>'创建时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
        ];
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'反馈ID','paramName'=>'id']),
            new Page_Admin_TextFilter(['name'=>'商品ID','paramName'=>'stock_id']),
            new Page_Admin_TextFilter(['name'=>'直播ID','paramName'=>'live_id']),
            new Page_Admin_TextFilter(['name'=>'买手ID','paramName'=>'buyer_id']),
            new Page_Admin_TextFilter(['name'=>'用户ID','paramName'=>'user_id']),
            new Page_Admin_TextFilter(['name'=>'反馈类型','paramName'=>'type','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'反馈文字','paramName'=>'info','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>'创建时间','paramName'=>'create_time']),
        );
        //$this->search_fields=array('name', 'id');
    }
}





