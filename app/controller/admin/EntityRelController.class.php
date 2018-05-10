<?php
class EntityRelController extends Page_Admin_Base {
private $_objC;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new EntityRel();
        WinRequest::mergeModel(array(
            'controllerText'=>"投资主体关系",
        ));
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'subject_id','label'=>'目标主体','type'=>"choosemodel",'model'=>'Entity','default'=>null,'required'=>true,),
            array('name'=>'holder_id','label'=>'持有主体','type'=>"choosemodel",'model'=>'Entity','default'=>null,'required'=>true,),
            array('name'=>'ratio','label'=>'持股比例','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"hidden","readonly"=>'true','default'=>time(),'null'=>false,),
            array('name'=>'admin_id','label'=>'创建人ID','type'=>"hidden",'readonly'=>'true','default'=>Admin::getCurrentAdmin()->mId,'required'=>true,),
        ));
        $this->list_display=array(
            ['label'=>'目标主体','field'=>function($model){
		$ret = self::_getResource($model->mSubjectId, 'entity', new Entity());
		return ($ret ? $ret->mName : '(id='.$model->mSubjectId.')' );
            }],
            ['label'=>'持有主体','field'=>function($model){
		$ret = self::_getResource($model->mHolderId, 'entity', new Entity());
		return ($ret ? $ret->mName : '(id='.$model->mHolderId.')' );
            }],
            ['label'=>'持股比例','field'=>function($model){
                return $model->mRatio;
            }],
            ['label'=>'创建人','field'=>function($model){
		$ret = self::_getResource($model->mAdminId, 'admin', new Admin());
		return ($ret ? $ret->mName : '(id='.$model->mAdminId.')' );
            }],
            ['label'=>'创建时间','field'=>function($model){
                return date('Y-m-d H:i:s',$model->mCreateTime);
            }],
        );

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'目标ID','paramName'=>'subject_id','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'持有者ID','paramName'=>'holder_id','fusion'=>true]),
        );
    }
}


