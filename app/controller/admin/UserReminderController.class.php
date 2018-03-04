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
});
</script>
EOF;
        return $js;
    }
}
class UserReminderController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new UserReminder();
        $this->model->orderBy("id","desc");
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'model_type','label'=>'类型',
                "choices"=>[
                //['buyer',"买手"],
                //['stock',"商品"],
                ['live',"直播"],
                ],
                'type'=>"modelTypeChoice",'default'=>'live','required'=>true,),
            
            array('name'=>'model_id','label'=>'model_id','model'=>'Live','type'=>"choosemodel",'default'=>null,'required'=>true,),
            array('name'=>'user_id','label'=>'用户','model'=>'User','type'=>"choosemodel",'default'=>null,'required'=>true,),
            /*
            array('type'=>'text',"name"=>"type","label"=>'类型',"required"=>true,),
            array('type'=>'text',"name"=>"url","label"=>'url',"required"=>true,'class'=>'wide'),
            array('type'=>'text',"name"=>"img","label"=>'img','class'=>'wide'),
            array('type'=>'text',"name"=>"tags","label"=>'tags','class'=>'wide'),
            array('type'=>'text',"name"=>"status","label"=>'status','default'=>1,'class'=>'wide'),
            array('type'=>'text',"name"=>"ctime","label"=>'ctime','default'=>time(),'class'=>'wide'),
             */
            array('name'=>'type',"choices"=>[['before5',"开始前5分钟"]], 'type'=>"choice",'default'=>'before5','null'=>false,),
            array('name'=>'status',"choices"=>[["not_send"],["send"]], 'type'=>"choice","default"=>"not_send",'null'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
            array('name'=>'update_time',      'type'=>"datetime",'default'=>null,'required'=>false,),
        ));
        $this->list_display=array('id','user_id','model_type','model_id','type','status',
            ['label'=>'创建时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
            ['label'=>'更新时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mUpdateTime);
            }],
        );
        /*
        $this->list_filter=array(
            new Admin_SiteTagsFilter()
        );
        $this->inline_admin=array(
            new Page_Admin_InlineSiteModule($this,'site_id'),
        );
         */
        //$this->search_fields=array('name','email');
    }

}


