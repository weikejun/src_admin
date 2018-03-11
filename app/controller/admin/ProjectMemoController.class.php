<?php
class ProjectMemoController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new ProjectMemo();
        $this->model->orderBy('create_time', 'desc');
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'project_id','label'=>'项目ID','type'=>"choosemodel",'model'=>'Project','default'=>WinRequest::getParameter('project_id'),'required'=>true,),
            array('name'=>'admin_id','label'=>'创建人ID','type'=>"hidden",'readonly'=>'true','default'=>Admin::getCurrentAdmin()->mId,'required'=>true,),
            array('name'=>'message','label'=>'事项说明','type'=>"textarea",'default'=>'','required'=>false,),
            array('name'=>'memo','label'=>'工作记录','type'=>"textarea",'default'=>'','required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"hidden","readonly"=>'true','default'=>time(),'null'=>false,),
        ));
        $this->list_display=array(
            ['label'=>'记录ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'项目名称','field'=>function($model){
		$project = new Project();
		$ret = $project->addWhere("id", $model->mProjectId)->select();
		$pName = ($ret ? $project->mName : '' );
                return '<a href="/admin/project?__filter='.urlencode('live_id='.$model->mProjectId).'">'.$pName.'</a>';
            }],
            ['label'=>'事项说明','field'=>function($model){
                return $model->mMessage;
            }],
            ['label'=>'工作记录','field'=>function($model){
                return $model->mMemo;
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
	$this->hide_action_new = true;
    }
}


