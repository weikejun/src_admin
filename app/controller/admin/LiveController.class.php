<?php
require_once('StockController.class.php');
class LiveController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Live();
        $this->model->orderBy("id","desc");
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'name','label'=>'直播名称','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'intro','label'=>'直播简介','type'=>"textarea",'default'=>null,'required'=>true,),
            array('name'=>'buyer_id','label'=>'买手ID','type'=>"text",'readonly'=>'true','default'=>null,'required'=>true,),
            array('name'=>'selector','label'=>'挑款师','type'=>"sug",'default'=>'','null'=>false,),
            array('name'=>'editor','label'=>'编辑','type'=>"sug",'url'=>"/admin/admin/sug?wd=\${word}",'default'=>'','null'=>false,),
            array('name'=>'country','label'=>'国家','type'=>"text",'default'=>null,'required'=>false,),
            //array('name'=>'province',      'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'city','label'=>'城市','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'address','label'=>'地址','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'brands','label'=>'品牌（json数组）',      'type'=>"jsonArray",'default'=>null,'required'=>false,),
            array('name'=>'start_time','label'=>'开始时间','type'=>"datetime",'default'=>null,'required'=>false,),
            array('name'=>'end_time','label'=>'结束时间','type'=>"datetime",'default'=>null,'required'=>false,),
            array('name'=>'valid','label'=>'',"choices"=>array(array('valid',"有效"),array('invalid',"无效"),), 'type'=>"choice",'default'=>'valid','null'=>false,),
            array('name'=>'imgs','label'=>'直播图片','type'=>"simpleJsonFiles",'default'=>null,'required'=>false,),
            //array('name'=>'product_type','label'=>'商品类型','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'fee','label'=>'直播费','type'=>"text","default"=>null,"required"=>false),
            array('name'=>'list_show','label'=>'列表显示','type'=>"choice",'choices'=>Live::getListShow(),"default"=>1,"required"=>false),
            //array('name'=>'update_time',      'type'=>"datetime",'default'=>null,'required'=>false,),
            array('name'=>'status','label'=>'申请状态',"choices"=>Live::getAllStatus(), 'type'=>"choice",'default'=>'notapply','null'=>false,),
            array('name'=>'type','label'=>'直播类型',"choices"=>Live::getAllTypes(), 'type'=>"choice",'default'=>'',"required"=>false),
            array('name'=>'check_words','label'=>'审核意见','type'=>"text",'default'=>'','null'=>false,),

        ));
        $this->list_display=array(
            ['label'=>'直播ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'直播名称','field'=>function($model){
                return ($model->mType?"【{$model->mType}】":"")."{$model->mName}";
            }],
            ['label'=>'买手ID','field'=>function($model,$pageAdmin,$modelList){
                static $buyerMap=false;
                if($buyerMap===false){
                    $buyer=new Buyer();
                    $buyerMap=$buyer->addWhere('id',
                        array_unique(
                            array_map(function($model){return $model->mBuyerId;},
                                $modelList)
                        ),
                        'in')->findMap();
                }
                return '<a href="/admin/live?__filter='.urlencode('buyer_id='.$model->mBuyerId).'">'.($buyerMap[$model->mBuyerId]?$buyerMap[$model->mBuyerId]->mName:$model->mBuyerId).'</a>';
            }],
            ['label'=>'挑款师','field'=>function($model){
                return '<a href="/admin/live?__filter='.urlencode('selector='.$model->mSelector).'">'.$model->mSelector.'</a>';
            }],
            ['label'=>'编辑','field'=>function($model){
                return '<a href="/admin/live?__filter='.urlencode('editor='.$model->mEditor).'">'.$model->mEditor.'</a>';
            }],
            ['label'=>'预约时间','field'=>function($model){
                $sDate = date('Y-m-d', $model->mStartTime);
                $eDate = date('Y-m-d', $model->mEndTime);
                $sTime = date('H:i', $model->mStartTime);
                $eTime = date('H:i', $model->mEndTime);
                return $sDate == $eDate 
                    ? "$sDate $sTime~$eTime"
                    : "$sDate $sTime~$eDate $eTime";
            }],
            ['label'=>'直播地点','field'=>function($model){
                return $model->mAddress.','.$model->mCity.','.$model->mCountry;
            }],
            ['label'=>'列表显示','field'=>function($model){
                return $model->mListShow ? '显示' : '不显示';
            }],
            ['label'=>'申请状态','field'=>function($model){
                foreach(Live::getAllStatus() as $status){
                    if($model->mStatus==$status[0]){
                        return $status[1];
                    }
                }
            }],
        );
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'直播ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'直播名','paramName'=>'name','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'买手ID','paramName'=>'buyer_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'挑款师','paramName'=>'selector','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'编辑','paramName'=>'editor','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>'开始时间','paramName'=>'start_time']),
            new Page_Admin_TimeRangeFilter(['name'=>'结束时间','paramName'=>'end_time']),
            new Page_Admin_ChoiceFilter(['name'=>'审核状态','paramName'=>'status','choices'=>Live::getAllStatus()]),
            new Page_Admin_ChoiceFilter(['name'=>'列表显示','paramName'=>'list_show','choices'=>Live::getListShow()]),
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
        );*/
        $this->multi_actions=array(
            array('label'=>'导出全部直播','required'=>false,'action'=>'/admin/live/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        );
        $inlineForenotice=new LiveForenoticeController();
        $inlineForenotice->setForeignKeyName("live_id");
        $inlineForenotice->setRelationship("single");
        $this->inline_admin=array(
            $inlineForenotice
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
            }],
            ['label'=>'直播流','action'=>function($model){
                return '/admin/liveFlow/flow?live_id='.$model->mId;
            }]
        ];
    }

    use ExportToCsvAction;

    public function downloadPicAction(){
        $id=intval($this->_GET("id",""));
        if(!$id){
            return ['text:输入参数错误'];
        }
        $oldmask=umask(0);
        $path=ROOT_PATH."/webroot/tmp/livepic/$id";
        @Utils::delTree($path);
        @mkdir($path,0777,true);
        
        $stock=new Stock();
        $stocks=$stock->addWhere('live_id',$id)->orderBy("id")->find();
        foreach($stocks as $stock){
            $_imgs=json_decode($stock->mImgs,true);
            if(is_array($_imgs)){
                foreach($_imgs as $i=> $img){
                    $file=ROOT_PATH."/webroot/".$img;
                    if(file_exists($file)){
                        symlink($file,"$path/{$stock->mId}_{$stock->mName}_{$i}".strstr($img,"."));
                    }
                }
            }
        }
        if(isset($img)){
            system("cd $path;tar -h -czf $id.tgz *");
            umask($oldmask);
            return ["redirect:/tmp/livepic/$id/$id.tgz"];
        }else{
            return ['text:没有图片'];
        }

    }
}

class LiveForenoticeController extends Page_Admin_Base{
    use Page_Admin_InlineBase;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new LiveForenotice();
        $this->model->orderBy("id","desc");
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'live_id','label'=>'直播id','model'=>"Live",'type'=>"choosemodel",'default'=>null,'required'=>true,),
            //array('name'=>'title','label'=>'简介标题','type'=>"textarea",'default'=>"no title",'required'=>false,),
            array('name'=>'content','label'=>'简介内容','type'=>"richTextarea",'default'=>null,'required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
        ));
    }
}
