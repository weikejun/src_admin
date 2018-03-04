<?php

class Form_ModelTypeChoiceField extends Form_ChoiceField{
    public function foot_js(){
        $name=$this->name();
        $js=<<<EOF
<script>
$(function(){
    $("input[name='$name']").click(function(){
        var modelName=$("input[name='$name']:checked").val();
        $("input[name='model_id']").attr("model",modelName);
    });
    $("input[name='$name']:checked").click();
});
</script>
EOF;
        return $js;
    }
}
class IndexNewController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new IndexNew();
        $this->model->orderBy("order","desc")->orderBy("id","desc")->orderBy("valid");
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'title','label'=>'标题','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'order','label'=>'优先级','type'=>"text",'default'=>0,'required'=>false,),
            array('name'=>'type','label'=>'Banner类型',
                "choices"=>[['stock',"商品"],['live',"直播"],['buyer',"买手"],],
                'type'=>"modelTypeChoice",'default'=>'live','required'=>true,),
            array('name'=>'model_id','label'=>'ID（商品/直播/买手）','model'=>'Live','type'=>"choosemodel",'default'=>null,'required'=>false,),

            array('name'=>'url','label'=>'活动链接（与上面ID二选一即可，优先展示活动）','type'=>"text",'default'=>null,'required'=>false,),
            //array('name'=>'valid','label'=>'',"choices"=>array(array('valid',"有效"),array('invalid',"无效"),), 'type'=>"choice",'default'=>'valid','null'=>false,),
            array('name'=>'valid','label'=>'是否展示',
                "choices"=>[['valid',"显示"],['invalid',"隐藏"],],
                'type'=>"choice",'default'=>'invalid','null'=>false,),
            array('name'=>'imgs','label'=>'直播图片[老版本]','type'=>"simpleJsonFiles",'default'=>null,'required'=>false,),
            array('name'=>'imgs6','label'=>'直播图片[新版本]','type'=>"simpleJsonFiles",'default'=>null,'required'=>false,),
            //array('name'=>'product_type','label'=>'商品类型','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime",'default'=>null,'required'=>false,'readonly'=>true,'auto_update'=>true),
            //array('name'=>'status','label'=>'申请状态',"choices"=>Live::getAllStatus(), 'type'=>"choice",'default'=>'notapply','null'=>false,),
            //array('name'=>'check_words','label'=>'审核意见','type'=>"text",'default'=>'','null'=>false,),
            array('name'=>'channel','label' => 'banner位', "choices"=>array(
                ['1',"首页"],
                ['2',"买手频道"],
            ), 'type'=>"choice",'default'=>0,'required'=>true,),
        ));
        $this->list_display=array(
            ['label'=>'标题','field'=>function($model){
                return $model->mTitle;
            }],
            ['label'=>'Banner类型','field'=>function($model){
                if($model->mType == 'live') {
                    $live = new Live($model->mModelId);
                    $live = $live->addWhere('id', $model->mModelId)->select();
                    return '直播：'.$live->mName;
                } elseif($model->mType == 'stock') {
                    $stock = new Stock($model->mModelId);
                    $stock = $stock->addWhere('id', $model->mModelId)->select();
                    return '商品：'.$stock->mName;
                } elseif($model->mType== 'buyer'){
                    $buyer = (new Buyer())->addWhere('id', $model->mModelId)->select();
                    return '买手：'.$buyer->mName;
                }
            }],
            ['label'=>'图片[老版本]','field'=>function($model){
                $imgs = json_decode($model->mImgs, true);
                if($imgs) {
                    return '<a href="'.$imgs[0].'" target="_blank"><img src="'.$imgs[0].'" width="200" /></a>';
                }
                return '';
            }],
            ['label'=>'图片[新版本]','field'=>function($model){
                    $imgs = json_decode($model->mImgs6, true);
                    if($imgs) {
                        return '<a href="'.$imgs[0].'" target="_blank"><img src="'.$imgs[0].'" width="200" /></a>';
                    }
                    return '';
                }],
            ['label'=>'优先级','field'=>'order'],
            ['label'=>'创建时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
            ['label'=>'更新时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mUpdateTime);
            }],
            ['label'=>'是否显示','field'=>function($model){
                return $model->mValid == 'valid' ? '显示':'隐藏';
            }],
            ['label'=>'频道','field'=>function($model){
                $array = IndexNew::getChannelDesc();
                return $array[$model->mChannel];
            }],
        );
        $this->list_filter=array(
            new Page_Admin_ChoiceFilter(['name'=>'是否显示','paramName'=>'valid','choices'=>[['valid',"显示"],['invalid',"隐藏"],]]),
            new Page_Admin_ChoiceFilter(['name'=>'频道','paramName'=>'channel','choices'=>[['1',"首页频道"],['2',"买手频道"],]]),
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
}




