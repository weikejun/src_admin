<?php
class BuyerRankController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new BuyerRank();
        $this->model->orderBy("update_time","desc")->orderBy("id","desc")->addWhere('type',BuyerRank::RECOMMEND);

        $this->form=new Form(array(
            array('name'=>'buyer_id','label'=>'买手ID','model'=>'Buyer','type'=>"choosemodel",'default'=>null,'required'=>true,),
            array('name'=>'type','label' => '排序类型', "choices"=>array(
                ['1',"推荐排序"],
            ), 'type'=>"choice",'default'=>1,'required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'update_time','label'=>'更新时间',      'type'=>"datetime",'default'=>null,'required'=>false,),
            //array('name'=>'valid','label'=>'',"choices"=>array(array('valid',"有效"),array('invalid',"无效"),), 'type'=>"choice",'default'=>'valid','null'=>false,),
            //array('name'=>'product_type','label'=>'商品类型','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'selector_id','label'=>'挑款师','type'=>"text","default"=>($_SESSION['admin']['id']), 'required'=>false),
            array('name'=>'comment','label'=>'审核意见','type'=>"text",'default'=>'','required'=>false,),
            array('name'=>'soso_comment','label'=>'说说状态','type'=>"text",'default'=>false),
        ));
        $this->list_display=array(
            ['label'=>'买手','field'=>function($model){
                 $buyer = (new Buyer())->addWhere('id',$model->mBuyerId)->select();
                 return "买手：". $buyer->mName;
            }],
            ['label'=>'选款师','field'=>function($model){
                $admin = (new Admin())->addWhere('id',$model->mSelectorId)->select();
                return "挑款师：".$admin->mName;
            }],
            ['label'=>'评价','field'=>function($model){
                return $model->mComment;
            }],
            ['label'=>'创建时间','field'=>function($model){
                    return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
            ['label'=>'更新时间','field'=>function($model){
                    return date('Y-m-d H:i:s', $model->mUpdateTime);
            }],
        );
        $this->list_filter=array(
        );
        /*
        $this->list_filter=array(
            new Admin_SiteTagsFilter()
        );
        $this->inline_admin=array(
            new Page_Admin_InlineSiteModule($this,'site_id'),
        );
        $this->multi_actions=array(
            array('label'=>'添加到module','action'=>'javascript:add_to_module();return false;'),
        );

        $this->single_actions=[
            ['label'=>'图片下载','action'=>function($model){
                return '/admin/live/downloadPic?id='.$model->mId;
            }],
            ['label'=>'商品','action'=>function($model){
                return '/admin/stock?__filter='.urlencode('live_id='.$model->mId);
            }],
            ['label'=>'买手','action'=>function($model){
                return '/admin/buyer?__filter='.urlencode('id='.$model->mBuyerId);
            }]
        ];
         */
    }

    public function _create(){
        //$ret = parent::_create();
        $requestData=$_REQUEST;
        $action = $requestData['action'];
        $ret = false;

        if($this->form->bind($_REQUEST)){
            //创建stock的时候同步数据到live_stock表
            $data=$this->form->values();
            $buyerId = $data['buyer_id'];
            $selectorId = $data['selector_id'];
            $comment = $data['comment'];
            if($action == 'create'){
                $buyerRank = new BuyerRank();
                $ret =$buyerRank->recommendBuyer($buyerId,$selectorId,$comment);
            }
        }
        return $ret;
    }
}