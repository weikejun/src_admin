<?php
class StockBookController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new StockBook();
        $this->model->orderBy("update_time","desc")->orderBy("id","desc");

        $this->form=new Form(array(
            array('name'=>'stock_id','label'=>'商品ID','model'=>'Stock','type'=>"choosemodel",'default'=>null,'required'=>true,),
            array('name'=>'category_id','label' => '排序类型', "choices"=>array(
                ['1',"打折村"],
                ['2',"美容"],
                ['3',"服装"],
                ['4',"鞋包"],
                ['5',"母婴"],
                ['6',"珠宝配饰"],
                ['7',"生活保健"],
                ['8',"特色"],
            ), 'type'=>"choice",'default'=>1,'required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'update_time','label'=>'更新时间',      'type'=>"datetime",'default'=>null,'required'=>false,),
            //array('name'=>'valid','label'=>'',"choices"=>array(array('valid',"有效"),array('invalid',"无效"),), 'type'=>"choice",'default'=>'valid','null'=>false,),
            //array('name'=>'product_type','label'=>'商品类型','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'selector_id','label'=>'挑款师','type'=>"text","default"=>($_SESSION['admin']['id']), 'required'=>false),
            array('name'=>'comment','label'=>'审核意见','type'=>"text",'default'=>'','required'=>false,),
            array('name'=>'status','label'=>"状态","choices"=>array(
                ['1',"上架"],
                ['2',"下架"],
            ),'type'=>"choice",'default'=>1,'required'=>false,),
        ));
        $this->list_display=[
            ['label'=>'商品ID','field'=>function($model){
                    return '<a href="/admin/stock?__filter='.urlencode('id='.$model->mStockId).'">'.$model->mStockId.'</a>';
                }],
            ['label'=>'分类','field'=>function($model){
                foreach(StockBook::category() as $category){
                    if($model->mCategoryId==$category['categoryId']){
                        return $category['category'];
                    }
                }
                return "未知";
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
        ];
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

    /**
     * 不能进行更新操作
     * @return bool
     */
    public function _update(){
        $ret = parent::_update();
        return true;
    }
}