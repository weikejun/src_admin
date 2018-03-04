<?php
class LiveStockController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new LiveStock();
        $this->model->orderBy("id","desc");
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'live_id','label'=>'直播名称','model'=>'Live','type'=>"choosemodel",'default'=>null,'required'=>true,),
            array('name'=>'stock_type','label'=>'类型',"choices"=>[['1',"商品"],['2',"状态"],],'type'=>"choice",'default'=>'1','null'=>false,),
            array('name'=>'stock_id','label'=>'ID（商品）','model'=>'stock','type'=>"choosemodel",'default'=>null,'required'=>true,),
            array('name'=>'status','label'=>'审核状态',"choices"=>Stock::getAllStatus(), 'type'=>"choice",'default'=>'verifying','null'=>false,),
            array('name'=>'checker_id','label'=>'挑款师','type'=>"text","default"=>($_SESSION['admin']['id']), 'required'=>false),
            array('name'=>'check_words','label'=>'审核意见','type'=>"text",'default'=>'','null'=>false,),
            array('name'=>'flow_time','label'=>'流时间','readonly'=>false,'type'=>"datetime",'default'=>null,'required'=>false,),
            array('name'=>'sell_time','label'=>'售卖时间','readonly'=>false,'type'=>"datetime",'default'=>null,'required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','readonly'=>true,'type'=>"datetime",'default'=>null,'required'=>false),
            array('name'=>'update_time','label'=>'更新时间','readonly'=>false,'type'=>'datetime','default'=>null,'required'=>true),
        ));
        $this->list_display=array(
            ['label'=>'直播ID','field'=>function($model){
                    return '<a href="/admin/live?__filter='.urlencode('id='.$model->mLiveId).'">'.$model->mLiveId.'</a>';
                }],
            ['label'=>'买手ID','field'=>function($model,$pageAdmin,$modelList){
                    $live = (new Live())->addWhere('id', $model->mLiveId)->select();
                    $buyerId = $live->mBuyerId;
                    $buyer = (new Buyer())->addWhere('id',$buyerId)->select();
                    return '<a href="/admin/buyer?__filter='.urlencode('id='.$buyer->mId).'">'.$buyer->mName.'</a>';
                }],
            ['label'=>'类型[商品/状态]','field'=>function($model){
                    if($model->mStockType == '1') {
                        $stock = new Stock($model->mStockId);
                        $stock = $stock->addWhere('id', $model->mStockId)->select();
                        $imgs = json_decode($stock->mImgs);
                        return '商品Id：<a href="/admin/Stock?__filter='.urlencode('id='.$model->mStockId).'">'.$model->mStockId.'</a>'.'<a href="'.$imgs[0].'" target="_blank"><img src="'.$imgs[0].'" width="200" /></a>';
                    } elseif($model->mStockType == '2') {
                        $buyerPic = new BuyerPic();
                        $buyerPic = $buyerPic->addWhere('id', $model->mStockId)->select();
                        $imgs = json_decode($buyerPic->mImgs);
                        return '状态：<a href="'.$imgs[0].'" target="_blank"><img src="'.$imgs[0].'" width="200" /></a>';
                    }
                }],
            ['label'=>'审核人员','field'=>function($model){
                    $admin = (new Admin())->addWhere('id',$model->mCheckerId)->select();
                    return $admin->mName;
                }],
            ['label'=>'评论','field'=>function($model){
                    return $model->mCheckWords;
                }],
            ['label'=>'审核状态','field'=>function($model){
                    foreach(Stock::getAllStatus() as $status){
                        if($model->mStatus==$status[0]){
                            return $status[1];
                        }
                    }
                }],
        );
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'直播ID','paramName'=>'live_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'审核人员','paramName'=>'checker_id','fusion'=>true]),
            new Page_Admin_ChoiceFilter(['name'=>'审核状态','paramName'=>'status','choices'=>Live::getAllStatus()]),
        );
    }
}
