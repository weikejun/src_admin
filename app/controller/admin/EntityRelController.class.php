<?php
class EntityRelController extends Page_Admin_Base {
    private $_objC;
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_EntityRel();
        WinRequest::mergeModel(array(
            'controllerText'=>"投资主体关系",
        ));
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'subject_id','label'=>'目标主体','type'=>"choosemodel",'model'=>'Model_Entity','default'=>null,'required'=>true,),
            array('name'=>'holder_id','label'=>'股东主体','type'=>"choosemodel",'model'=>'Model_Entity','default'=>null,'required'=>true,),
            array('name'=>'ratio','label'=>'持股比例','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime","readonly"=>'true','default'=>time(),'null'=>false,),
        ));
        $this->list_display=array(
            ['label'=>'股东主体','field'=>function($model){
                $ret = self::_getResource($model->mHolderId, 'entity', new Model_Entity());
                return ($ret ? $ret->mName : '(id='.$model->mHolderId.')' );
            }],
            ['label'=>'目标主体','field'=>function($model){
                $ret = self::_getResource($model->mSubjectId, 'entity', new Model_Entity());
                return ($ret ? $ret->mName : '(id='.$model->mSubjectId.')' );
            }],
            ['label'=>'持股比例','field'=>function($model){
                return $model->mRatio;
            }],
            ['label'=>'更新时间','field'=>function($model){
                return date('Y-m-d H:i:s',$model->mUpdateTime);
            }],
        );

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'目标ID','paramName'=>'subject_id','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'持有者ID','paramName'=>'holder_id','fusion'=>true]),
        );
    }
}


