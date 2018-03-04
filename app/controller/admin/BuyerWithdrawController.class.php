<?php
class BuyerWithdrawController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new BuyerWithdraw();
        $this->model->orderBy("id","desc");
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'buyer_id','type'=>"choosemodel",'model'=>'Buyer','default'=>null,'required'=>true,),
            array('name'=>'admin_id','type'=>"choosemodel",'model'=>'Admin','default'=>null,'required'=>true,),
            array('name'=>'create_time','type'=>"datetime",'default'=>null,'required'=>false,),
            array('name'=>'update_time','auto_update'=>true,'type'=>"datetime",'default'=>null,'required'=>false,),
            array('name'=>'account_type','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'account_no','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'account_name','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'account_address','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'account_bank','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'account_swift','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'account_routing','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'account_country','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'account_city','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'amount','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'log','readonly'=>true,'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'note','label'=>'买手提款的备注','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'admin_note','label'=>'财务打款的备注','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'status','label'=>'申请状态',"choices"=>BuyerWithdraw::getAllStatus(), 'type'=>"choice",'default'=>'begin','null'=>false,),

        ));
        $this->list_display=array('id',
            ['label'=>'买手','field'=>function($model){
                return "<a href='/admin/buyer?action=read&id={$model->mBuyerId}'>{$model->mBuyerId}</a>";
            }],
            ['label'=>'状态','field'=>function($model){
                foreach(BuyerWithdraw::getAllStatus() as $status){
                    if($model->mStatus==$status[0]){
                        return $status[1];
                    }
                }
            }],
            array('label'=>'创建时间','field'=>[$this,'display_ctime']),
        );
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'买手id','paramName'=>'buyer_id','fusion'=>true]),
            new Page_Admin_ChoiceFilter(['name'=>'状态','paramName'=>'status','choices'=>BuyerWithdraw::getAllStatus()]),
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
        
        $this->search_fields=array('buyer_id','note','admin_note');
    }
    public function display_ctime($modelData){
        return date("%Y-%m-%d",$modelData->mCreateTime);
    }


}




