<?php
class CouponController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Coupon();
        $this->model->orderBy("id","desc");

        $this->form=new Form(array(
//            array('name'=>'coupon_id','label'=>'代金券id','type'=>"choosemodel",'model'=>'Live','readonly'=>'true','default'=>null,'required'=>true,),
            array('name'=>'num','label'=>'生成数量','type'=>"text",'model'=>'Live','default'=>null,'required'=>true,),
            array('name'=>'name','label'=>'代金券名称','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'desc','label'=>'代金券说明','type'=>"textarea",'default'=>null,'required'=>true,),
            array('name'=>'value','label'=>'扣减金额','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'low_price','label'=>'最小使用金额','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'live_id','label'=>'直播id','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>'true','default'=>null,'null'=>false,),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime",'readonly'=>'true','auto_update'=>true,'default'=>null,'null'=>false,),
            array('name'=>'expire_time','label'=>'过期时间','type'=>"datetime",'default'=>null,'required'=>true,),
            array('name'=>'status','label'=>'代金券状态','type'=>"choice",'choices'=>Coupon::getAllStatus(),'default'=>null,'required'=>false,),
        ));
        $this->list_display=array(
            ['label'=>'生成日期', 'field'=>function($model){
                return date("Y-m-d H:i:s",$model->mCreateTime);
            }],
            ['label'=>'代金券ID', 'field'=>function($model){
                return $model->mCouponId;
            }],
            ['label'=>'代金券名称', 'field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'代金券说明', 'field'=>function($model){
                return $model->mDesc;
            }],
            ['label'=>'扣减金额', 'field'=>function($model){
                return $model->mValue;
            }],
            ['label'=>'最小使用金额','field'=>function($model){
                return $model->mLowPrice;
            }],
            ['label'=>'使用直播','field'=>function($model){
                return $model->mLiveId;
            }],
            ['label'=>'过期时间','field'=>function($model){
                return date("Y-m-d H:i:s",$model->mExpireTime);
            }],
            ['label'=>'使用状态','field'=>function($model){
                foreach(Coupon::getAllStatus() as $status){
                    if($model->mStatus==$status[0]){
                        return $status[1];
                    }
                }
            }],
            ['label'=>'使用信息','field'=>function($model){
                $order = new Order();
                $order = $order->addWhere('coupon_id', $model->mCouponId)->select();
                if($order) {
                    return '直播id: <a href="/admin/order?__filter='.urlencode('live_id='.$order->mLiveId).'">'.$order->mLiveId.'</a>
                            订单id: <a href="/admin/order?__filter='.urlencode('id='.$order->mId).'">'.$order->mId.'</a>';

                }
                return "";
            }]
        );
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'代金券ID','paramName'=>'coupon_id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'扣减金额','paramName'=>'value','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'最小使用金额','paramName'=>'low_price','fusion'=>false]),
            new Page_Admin_ChoiceFilter(['name'=>'代金券状态','paramName'=>'status','fusion'=>true,'choices'=>Coupon::getAllStatus()]),
            new Page_Admin_TimeRangeFilter(['name'=>'生成时间','paramName'=>'create_time']),
            new Page_Admin_TimeRangeFilter(['name'=>'过期时间','paramName'=>'expire_time']),
        );

        $this->multi_actions=array(
            array('label'=>'添加到module','action'=>'javascript:add_to_module();return false;'),
        );
        $this->single_actions=[
        ];
        
        //$this->search_fields=array('name', 'id');
    }
 
    public function create() {
        $data['total'] = $_REQUEST['num'];
        $data['name'] = $_REQUEST['name'];
        $data['value'] = $_REQUEST['value'];
        $data['low_price'] = $_REQUEST['low_price'];
        if ($_REQUEST['low_price']==0 && $_REQUEST['live_id']==0) {
            $data['scene'] = 0;
        } elseif($_REQUEST['low_price']!=0 && $_REQUEST['live_id']==0) {
            $data['scene'] = 2;
        } else{
            $data['scene'] = 1;
        }
        $data['live_id'] = $_REQUEST['live_id'];
        $data['desc'] = $_REQUEST['desc'];
        $data['status'] = 'unclaimed';
        $data['user_id'] = 0;
        $data['expireTime'] = $_REQUEST['expire_time'];
        $data['source'] = '';
        $ret = Coupon::createCoupon($data);
        $__success_url=$_REQUEST['__success_url'];
        if ($ret) {
            $this->back("插入成功",$__success_url);
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
