<?php
class SystemLogController extends Page_Admin_Base {
    public function diffAction() {
        $model = new Model_SystemLog;
        if (!isset($_GET['resource']) || !isset($_GET['res_id'])) {
            return ['redirect: /admin/index'];
        }
        if (isset($_GET['diff']) && count($_GET['diff']) == 2) {
            $model->addWhere('id', $_GET['diff'], 'IN');
        } else {
            $model->addWhere('resource', $_GET['resource']);
            $model->addWhere('res_id', $_GET['res_id']);
            $model->addWhere('method', 'autosave', '!=');
            $model->orderBy('id', 'DESC');
            $model->limit(2);
        }
        $logs = $model->find();
        if (count($logs) >= 1) {
            parse_str(str_replace('|', '&', trim($logs[0]->mDetail, '|')), $kvs1);
            parse_str(str_replace('|', '&', trim($logs[1]->mDetail, '|')), $kvs2);
            $kvs = array_merge($kvs1, $kvs2);
            foreach($kvs as $k => $v) {
                if (in_array($k, ['decision_date', 'close_date', 'loan_expiration', 'term_buyback_start', 'term_buyback_date'])) {
                    $kvs1[$k] = $kvs1[$k] ? date('Ymd', $kvs1[$k]) : '';
                    if (isset($kvs2[$k])) {
                        $kvs2[$k] = $kvs1[$k] ? date('Ymd', $kvs2[$k]) : '';
                    }
                } else if (in_array($k, ['update_time'])) {
                    $kvs1[$k] = $kvs1[$k] ? date('Ymd H:i:s', $kvs1[$k]) : '';
                    if (isset($kvs2[$k])) {
                        $kvs2[$k] = $kvs1[$k] ? date('Ymd H:i:s', $kvs2[$k]) : '';
                    }
                } else if (in_array($k, ['company_id'])) {
                    $finder = new Model_Company;
                    $finder->mId = $kvs1[$k];
                    $finder->select();
                    $kvs1[$k] = $finder->mName;
                    if (isset($kvs2[$k])) {
                        $finder = new Model_Company;
                        $finder->mId = $kvs2[$k];
                        $finder->select();
                        $kvs2[$k] = $finder->mName;
                    }
                } else if (in_array($k, ['entity_id'])) {
                    $finder = new Model_Entity;
                    $finder->mId = $kvs1[$k];
                    $finder->select();
                    $kvs1[$k] = $finder->mName;
                    if (isset($kvs2[$k])) {
                        $finder = new Model_Entity;
                        $finder->mId = $kvs2[$k];
                        $finder->select();
                        $kvs2[$k] = $finder->mName;
                    }
                }
                if ($kvs1[$k] != $kvs2[$k]) {
                    $kvs1[$k] = "<font color=red>".$kvs1[$k]."</font>";
                    if (isset($kvs2[$k]))
                        $kvs1[$k] = "<s>".$kvs2[$k]."</s>&nbsp;".$kvs1[$k];
                }
            }
        }
        $formClass = 'Form_'.ucwords($_GET['resource']);
        return ['admin/system_log/diff.tpl', array('kvs1'=>$kvs1,'kvs2'=>$kvs2,'logs1'=>$logs[0],'logs2'=>$logs[1],'formClass'=>$formClass)];
    }
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_SystemLog();
        $this->model->orderBy("create_time","DESC");
        WinRequest::mergeModel(array(
            'controllerText'=>"系统日志",
            'tableWrap' => '1024px',
        ));
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(

        ));
        $this->list_display=array(
            ['label'=>'流水号','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'操作人','field'=>function($model){
                $admin = $this->_getResource($model->mOperatorId, 'Admin', new Model_Admin, 'id');
                return $admin->mName;
            }],
            ['label'=>'操作IP','field'=>function($model){
                return $model->mOperatorIp;
            }],
            ['label'=>'资源','field'=>function($model){
                return $model->mResource.":".$model->mResId;
            }],
            ['label'=>'方法','field'=>function($model){
                return $model->mMethod;
            }],
            ['label'=>'动作','field'=>function($model){
                return $model->mAction;
            }],
            ['label'=>'内容','field'=>function($model){
                return $model->mDetail;
            }],
            ['label'=>'操作时间','field'=>function($model){
                return date('Y-m-d H:i:s',$model->mCreateTime);
            }],
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
        $this->list_filter=array(
            new Page_Admin_TextForeignFilter(['name'=>'操作人','paramName'=>'name|operator_id','foreignTable'=>'Model_Admin','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'资源','paramName'=>'resource','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'资源ID','paramName'=>'res_id','fusion'=>false]),
            new Page_Admin_TimeRangeFilter(['name'=>'操作时间','paramName'=>'create_time']),
        );

        $this->hide_action_new = true;

        $this->single_actions_default = ['delete' => false, 'edit' => false];
        
        //$this->search_fields=array('admin_id','buyer_id','user_id','log');
    }

    public function _delete() {
    }

    public function _update() {
    }

    public function _create() {
    }
}




