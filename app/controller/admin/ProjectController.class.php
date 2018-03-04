<?php
class ProjectController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Company();
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'name','label'=>'企业名称','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime","readonly"=>'true','default'=>null,'null'=>false,),
            array('name'=>'admin_id','label'=>'创建人ID','type'=>"text",'readonly'=>'true','default'=>null,'required'=>true,),
        ));
        $this->list_display=array(
            ['label'=>'id','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'企业名称','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'创建人ID','field'=>function($model){
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
                return '/admin/project?__filter='.urlencode('company_id='.$model->mId);
            }],
        ];

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'企业ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'企业名称','paramName'=>'name','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>'创建时间','paramName'=>'create_time']),
        );
    }
}


