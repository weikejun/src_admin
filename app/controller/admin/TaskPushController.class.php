<?php
class TaskPushController extends Page_Admin_Base {
    private function _setForm($type = null) {
        $fields = array(
            'status' => array('name'=>'status','label'=>'处理状态','type'=>"choice",'choices'=>TaskPush::getAllStatus(), 'default'=>0,'required'=>false,),
            'content' => array('name'=>'content','label'=>'消息内容','type'=>"textarea",'default'=>null,'required'=>true,),
            'type' => array('name'=>'type','label'=>'用户范围','type'=>"choice",'choices'=>TaskPush::getAllType(), 'default'=>0,'required'=>false,),
            'user_ids' => array('name'=>'user_ids','label'=>'推送用户ID','type'=>"textarea",'default'=>null,'required'=>false,),
            'push_time' => array('name'=>'push_time','label'=>'推送时间','type'=>"datetime",'readonly'=>false,'default'=>null,'required'=>true),
            'creator_id' => array('name'=>'creator_id','label'=>'创建人ID','type'=>"text",'default'=>Admin::getCurrentAdmin()->mId,'readonly'=>true,'required'=>false,),
            'create_time' => array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
        );
        return array_values($fields);
    }

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new TaskPush();
        $this->model->orderBy('create_time', 'desc');

        $this->form=new Form($this->_setForm($this->_GET('type')));
        $this->list_display=[
            ['label'=>'流水ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'处理状态','field'=>function($model){
                $allStatus = TaskPush::getAllStatus();
                foreach($allStatus as $status) {
                    if($model->mStatus == $status[0]) {
                        $statusDesc = $status[1];
                    }
                }
                return $statusDesc;
            }],
            ['label'=>'创建时间','field'=>function($model){
                return $model->mCreateTime ? date('Y-m-d H:i', $model->mCreateTime) : '';
            }],
            ['label'=>'创建人','field'=>function($model){
                $res = self::_getResource($model->mCreatorId, 'admin', new Admin);
                return $res->mName;
            }],
            ['label'=>'推送内容','field'=>function($model){
                return $model->mContent;
            }],
            ['label'=>'推送范围','field'=>function($model){
                $allType = TaskPush::getAllType();
                foreach($allType as $type) {
                    if($model->mType == $type[0]) {
                        $typeDesc = $type[1];
                    }
                }
                return $typeDesc;
            }],
            ['label'=>'成功数','field'=>function($model){
                return $model->mSuccess;
            }],
            ['label'=>'失败数','field'=>function($model){
                return $model->mFail;
            }],
            ['label'=>'推送时间','field'=>function($model){
                return $model->mPushTime ? date('Y-m-d H:i', $model->mPushTime) : '';
            }],
            ['label'=>'完成时间','field'=>function($model){
                return $model->mEndTime ? date('Y-m-d H:i', $model->mEndTime) : '';
            }],
        ];

        $this->single_actions_default = [
            'edit' => true, 
            'delete' => false
        ];
        
        $this->list_filter=array(
            new Page_Admin_ChoiceFilter(['name'=>'处理状态','paramName'=>'status','choices'=>TaskPush::getAllStatus()]),
            new Page_Admin_TimeRangeFilter(['name'=>'推送时间','paramName'=>'create_time']),
        );
    }
}
