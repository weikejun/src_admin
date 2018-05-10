<?php
class CompanyController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        WinRequest::mergeModel(array(
            'controllerText'=>"目标公司",
        ));
        $this->model=new Company();
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'name','label'=>'公司全称','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'proj_name','label'=>'项目名称','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'proj_code','label'=>'项目编号','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'bussiness','label'=>'所属行业','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'init_res_person','label'=>'初始负责人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'current_person','label'=>'现负责人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'legal_person','label'=>'法务负责人','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'director','label'=>'董事','type'=>"text", 'default'=>'无董事席位','required'=>false,),
            array('name'=>'director_turn','label'=>'董事委派轮次','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'director_status','label'=>'董事状态','type'=>"choice",'choices'=>[['不适用','不适用'],['在职','在职'],['取消原席位','取消原席位'],['待工商登记','待工商登记']], 'default'=>'不适用','required'=>true,),
            array('name'=>'observer','label'=>'观察员','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'info_right','label'=>'信息权','type'=>"choice",'choices'=>[['有','有'],['无','无']], 'default'=>'有','required'=>true,),            array('name'=>'create_time','label'=>'创建时间','type'=>"hidden","readonly"=>'true','default'=>time(),'null'=>false,),
            array('name'=>'info_right_threshold','label'=>'信息权门槛','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'stock_num','label'=>'总股数','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'admin_id','label'=>'创建人ID','type'=>"hidden",'readonly'=>'true','default'=>Admin::getCurrentAdmin()->mId,'required'=>true,),
        ));
        $this->list_display=array(
            ['label'=>'id','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'公司名称','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'所属行业','field'=>function($model){
                return $model->mBussiness;
            }],
            /*['label'=>'创建人ID','field'=>function($model){
		$admin = new Admin();
		$ret = $admin->addWhere("id", $model->mAdminId)->select();
		return ($ret ? $admin->mName : '(id='.$model->mAdminId.')' );
            }],*/
            ['label'=>'录入时间','field'=>function($model){
                return date('Y-m-d H:i:s',$model->mCreateTime);
            }],
        );

        $this->single_actions=[
            ['label'=>'投资记录','action'=>function($model){
                return '/admin/project?__filter='.urlencode('company_id='.$model->mId);
            }],
        ];

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'公司ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'公司名称','paramName'=>'name','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>'录入时间','paramName'=>'create_time']),
        );
    }
}


