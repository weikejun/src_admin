<?php
class EntityController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Entity();
        WinRequest::mergeModel(array(
            'controllerText'=>"投资主体",
        ));
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'name','label'=>'主体名称','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'tp','label'=>'类型','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'currency','label'=>'货币','type'=>"choice",'choices'=>[['RMB','RMB'],['USD','USD'],['HKD','HKD']], 'default'=>'USD','required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"hidden","readonly"=>'true','default'=>time(),'null'=>false,),
            array('name'=>'admin_id','label'=>'创建人ID','type'=>"hidden",'readonly'=>'true','default'=>Admin::getCurrentAdmin()->mId,'required'=>true,),
        ));
        $this->list_display=array(
            ['label'=>'id','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'主体名称','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'类型','field'=>function($model){
                return $model->mTp;
            }],
            ['label'=>'货币','field'=>function($model){
                return $model->mCurrency;
            }],
            ['label'=>'创建人','field'=>function($model){
		$admin = new Admin();
		$ret = $admin->addWhere("id", $model->mAdminId)->select();
		return ($ret ? $admin->mName : '(id='.$model->mAdminId.')' );
            }],
            ['label'=>'创建时间','field'=>function($model){
                return date('Y-m-d H:i:s',$model->mCreateTime);
            }],
        );

        $this->single_actions=[
            ['label'=>'投资记录','action'=>function($model){
                return '/admin/project?__filter='.urlencode('entity_id='.$model->mId);
            }],
            ['label'=>'股权结构','action'=>function($model){
                return '/admin/entityRel?__filter='.urlencode('subject_id='.$model->mId);
            }],
        ];

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'主体名称','paramName'=>'name','fusion'=>true]),
        );
    }
}


