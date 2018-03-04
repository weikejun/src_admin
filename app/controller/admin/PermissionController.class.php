<?php
class PermissionController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Permission();

        $this->form=new Form(array(
            array('name'=>'name','label'=>'权限名','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'description','label'=>'权限说明','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'default'=>null,'null'=>false,'readonly'=>true),
        ));
        $this->list_display=array(
            ['label'=>'权限ID','field'=>function($model) {
                return $model->mId;
            }],
            ['label'=>'权限名','field'=>function($model) {
                return $model->mName;
            }],
            ['label'=>'权限说明','field'=>function($model) {
                return $model->mDescription;
            }],
            ['label'=>'创建时间','field'=>function($model) {
                return date("Y-m-d H:i:s", $model->mCreateTime);
            }],
        );
        
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'权限ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'权限名','paramName'=>'name','fusion'=>true]),
        );

        $this->single_actions=[
            ['label'=>'权限分配','action'=>function($model){
                return '/admin/rolePermission?__filter='.urlencode('permission_id='.$model->mId);
            }],
        ];
    }
    public function display_ctime($modelData){
        return strftime("%Y-%m-%d",$modelData->mCreateTime);
    }   

}


