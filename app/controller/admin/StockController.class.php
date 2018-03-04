<?php
require_once("StockAmountController.class.php");

class StockController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Stock();
        $this->model->orderBy("id","desc");
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            /*
            array('type'=>'text',"name"=>"name","label"=>'name',"required"=>true,'class'=>'wide'),
            array('type'=>'text',"name"=>"url","label"=>'url',"required"=>true,'class'=>'wide'),
            array('type'=>'text',"name"=>"img","label"=>'img','class'=>'wide'),
            array('type'=>'text',"name"=>"tags","label"=>'tags','class'=>'wide'),
            array('type'=>'text',"name"=>"status","label"=>'status','default'=>1,'class'=>'wide'),
            array('type'=>'text',"name"=>"ctime","label"=>'ctime','default'=>time(),'class'=>'wide'),
             */

            array('name'=>'live_id','label'=>'直播ID','type'=>"choosemodel",'model'=>'Live','readonly'=>'true','default'=>null,'required'=>true,),
            array('name'=>'name','label'=>'商品名称','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'note','label'=>'商品简介','type'=>"textarea",'default'=>null,'required'=>true,),
            //array('name'=>'brand','type'=>"text",'default'=>null,'required'=>false,),
            //array('name'=>'update_time','type'=>"datetime",'default'=>null,'null'=>false,),
            array('name'=>'imgs','label'=>'商品图片','type'=>"simpleJsonFiles",'default'=>null,'null'=>false,),
            array('name'=>'sku_meta','label'=>'商品规格','type'=>"skumeta",'default'=>'{"颜色":["红","黄","蓝"],"尺寸":["大","中","小"]}','null'=>false,),
            //array('name'=>'valid',"choices"=>array(array('valid',"有效"),array('invalid',"无效"),), 'type'=>"choice",'default'=>null,'null'=>false,),
            array('name'=>'pricein','label'=>'进价' ,'type'=>"text",'default'=>null,'null'=>false,),
            array('name'=>'pricein_unit','label'=>'进价货币单位',"choices"=>Stock::getCurrencyUnit(), 'type'=>"choice",'default'=>'CNY','null'=>false,),
            array('name'=>'priceout','label'=>'卖价',   'type'=>"text",'default'=>null,'null'=>false,),
            array('name'=>'priceout_unit','label'=>'卖价货币单位',"choices"=>Stock::getCurrencyUnit(), 'type'=>"choice",'default'=>'CNY','null'=>false,),
            array('name'=>'onshelf','label' => '是否上架', "choices"=>array(
                ['0',"下架"],
                ['1',"上架"],
            ), 'type'=>"choice",'default'=>0,'null'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>'true','default'=>null,'null'=>false,),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime",'readonly'=>'true','auto_update'=>true,'default'=>null,'null'=>false,),
            array('name'=>'flow_time','label'=>'流时间','type'=>"datetime",'readonly'=>false,'default'=>null,'null'=>false,),
            array('name'=>'check_words','label'=>'审核意见','type'=>"text",'default'=>null,'null'=>false,),
            //array('name'=>'checker_id','type'=>"choosemodel",'model'=>'Admin','default'=>Admin::getCurrentAdmin()?Admin::getCurrentAdmin()->mId:null,'null'=>false,),
            //20140106 dingping note
            //array('name'=>'status','label'=>'审核状态',"choices"=>Stock::getAllStatus(), 'type'=>"choice",'default'=>'verifying','null'=>false,),
        ));
        $this->list_display=array(
            ['label'=>'商品ID', 'field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'商品名称', 'field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'商品进价', 'field'=>function($model){
                return $model->mPricein.' '.$model->mPriceinUnit;
            }],
            ['label'=>'商品售价', 'field'=>function($model){
                return $model->mPriceout.' '.$model->mPriceoutUnit;
            }],
            ['label'=>'订单数（含取消）', 'field'=>function($model){
                $order = new Order;
                $orderN = $order->addWhere('stock_id', $model->mId)->count();
                return $orderN;
            }],
            ['label'=>'直播ID', 'field'=>function($model){
                return '<a href="/admin/stock?__filter='.urlencode('live_id='.$model->mLiveId).'">'.$model->mLiveId.'</a>';
            }],
            ['label'=>'是否上架','field'=>function($model){
                return $model->mOnshelf == 0 ? '下架' : '上架';
            }],
            //2015-01-06 dingping note；
//            ['label'=>'审核状态','field'=>function($model){
//                foreach(Stock::getAllStatus() as $status){
//                    if($model->mStatus==$status[0]){
//                        return $status[1];
//                    }
//                }
//            }],
            ['label'=>'创建时间','field'=>function($model){
                return date('Y-m-d H:i:s',$model->mCreateTime);
            }],
        );
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'商品ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'直播ID','paramName'=>'live_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'商品名称','paramName'=>'name','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'买手id','paramName'=>'buyer_id','fusion'=>false]),
            new Page_Admin_TimeRangeFilter(['name'=>'创建时间','paramName'=>'create_time']),
//            new Page_Admin_ChoiceFilter([
//                'paramName'=>'status',
//                'name'=>'审核状态',
//                'choices'=>Stock::getAllStatus()
//            ]),
        );

        $inlineStockAmount=new StockAmountController();
        $inlineStockAmount->setForeignKeyName("stock_id");
        $this->inline_admin=array(
            $inlineStockAmount
        );
        $inlineStockAmount->setCouldCreate(false);
        /*
        $this->list_filter=array(
            new Admin_SiteTagsFilter()
        );
        $this->multi_actions=array(
            array('label'=>'添加到module','action'=>'javascript:add_to_module();return false;'),
        );*/
        $this->single_actions_default = [
            'edit' => true,
            'delete' => false
        ];
        $this->single_actions=[
            ['label'=>'删除','confirm'=>'商品删除后将不能恢复，确认删除么？','action'=>function($model){
                return '/admin/stock?action=delete&id='.$model->mId;
            }, 'enable'=>function($model) {
                $order = new Order;
                $orderN = $order->addWhere('stock_id', $model->mId)->count();
                return !!(!$orderN);
            }],
            ['label'=>'直播','action'=>function($model){
                return '/admin/live?__filter='.urlencode('id='.$model->mLiveId);
            }],
            ['label'=>'SKU','action'=>function($model){
                return '/admin/stockAmount?__filter='.urlencode('stock_id='.$model->mId);
            }]
        ];
        
        //$this->search_fields=array('name', 'id');
    }
    public function generateStockAmountAction(){
        $sku_meta=json_decode($this->_POST('sku_meta'));
        if(!$sku_meta){
            return ["json:",['json'=>['errno'=>1,'msg'=>'格式错误']]];
        }
        $stock=new Stock();
        $stock=$stock->addWhere("id",$this->_POST('id'))->select();
        if(!$sku_meta){
            return ["json:",['json'=>['errno'=>2,'msg'=>'没有stock']]];
        }

        $stockAmount=new StockAmount();

        $stockAmount->addWhere("stock_id",$stock->mId)->update(['valid'=>'0']);
        
        $sku_values=Stock::calcCombinedValues($sku_meta);
        
        foreach($sku_values as $combine_value){
            $stockAmount=new StockAmount();
            if($stockAmount->addWhere('stock_id',$stock->mId)->addWhere('sku_value',$combine_value)->select()){
                $stockAmount->mValid=1;
                $stockAmount->save();
            }else{
                $stockAmount->clear();
            }
            $stockAmount->mStockId=$stock->mId;
            $stockAmount->mSkuValue=$combine_value;
            $stockAmount->mAmount=1;
            $stockAmount->mCreateTime=time();
            $stockAmount->save();
            $stockAmountsData[]=$stockAmount->getData();
        }
        $stock->mSkuMeta=json_encode($sku_meta,JSON_UNESCAPED_UNICODE);
        $stock->save();
        return ["json:",['json'=>['errno'=>0,'msg'=>'成功']]];
    }

    public function _update(){
        $ret = parent::_update();
        $requestData=$_REQUEST;
        $action = $requestData['action'];
        if($this->form->bind($requestData)){
            $data=$this->form->values();
            $data['id']=$requestData["id"];
            $this->model->setData($data);
            $id = $this->model->mId;

            //商品更新操作时候触发的事情
            if($action == 'update'){
                //1. live_stock双写
                $liveStock = new LiveStock();
                $liveStock->stockSynLiveStock($data, "update");
                //2. 图墙商品下架
                StockBook::getInstance()->stockSyncBook($data,"update");
            }
        }
        return $ret;
    }

    public function _create(){
        $ret = parent::_create();
        $requestData=$_REQUEST;
        $action = $requestData['action'];

        if($this->form->bind($_REQUEST)){
            //创建stock的时候同步数据到live_stock表
            $data=$this->form->values();
            $stockId = $this->model->mId;
            $data['id'] = $stockId;
            if($action == 'create'){
                $liveStock = new LiveStock();
                $liveStock->stockSynLiveStock($data, "insert");
            }
        }
        return $ret;
    }

    public function _delete(){
        parent::_delete();
        $requestData=$_REQUEST;
        $action = $requestData['action'];
        $stockId = $_REQUEST['id'];

        if($action == 'delete'){
            //同步删除live_stock
            $liveStock = new LiveStock();
            $liveStock->stockSynLiveStock(array('id' => $stockId),'delete');
            //下架图墙的商品
            StockBook::getInstance()->stockSyncBook(array('id' => $stockId),'delete');
        }
    }

}


class Form_SkumetaField extends Form_Field{
    
    public function to_html($is_new){
        $class=$this->config['class'];
        $html="<div class='control-group'>";
        $html.= "<label class='control-label'>".htmlspecialchars($this->label)."</label>".
            "<div class='controls skumeta'>".
                '<textarea name="'.$this->name.'" class="span6">'.htmlspecialchars($this->value).'</textarea>';
        if(!$is_new){
            $html.="<div>".
                    "<span class='span2'><a href='javascript:;' class='btn check' type='button'>check格式</a></span>".
                    "<span class='span2'><a href='javascript:;' class='btn generate' type='button'>生成属性记录</a></span>".
                    "</div>";
        }
        
        
        if($this->error){
            $html.="<span class='help-inline'>".$this->error."</span>";
        }
        $html.='</div>';
        $html.='</div>';
        return $html;
    }

    public function head_css(){
        $css=<<<EOF
<style>
</style>
EOF;
        return $css;
    }
    public function foot_js(){
        $js=<<<EOF
<script>
(function(){
    if(window.__init_skumeta_field){
        return;
    }
    window.__init_skumeta_field=true;
    $(".skumeta").each(function(i,e){
        var textarea=$(e).find("textarea");
        var checkBtn=$(e).find(".check");
        var generateBtn=$(e).find(".generate");
        var id=$(e).parents("form").find("[name='id']").val();
        checkBtn.click(function(){
            try{
                var json=JSON.parse(textarea.val());
            }catch(e){}
            if(!json){
                alert('格式出错，不符合json规范，比如{"颜色":["红","黄","蓝"],"尺寸":["大","中","小"]}');
            }else{
                alert('格式正确');
            }
            return false;
        });
        generateBtn.click(function(){
            $.ajax({
                'dataType':'json',
                'type':'post',
                'data':{'id':id,'sku_meta':textarea.val()},
                'url':'/admin/stock/generateStockAmount',
                'success':function(data){
                    if(data.errno){
                        alert(data.msg);
                    }else{
                        location.reload();
                    }
                }
            });
            return false;
        });
    });
})();
</script>
EOF;
        return $js;
    }

}
