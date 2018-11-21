<?php
class ComplianceMatterController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_ComplianceMatter();
        WinRequest::mergeModel(array(
            'controllerText'=>"合规审查事项",
            'tableWrap' => '3072px',
            '_preview' => true,
        ));

        $this->form=new Form_ComplianceMatter();
        $this->list_display = [];
        foreach(Form_ComplianceMatter::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }

        $this->single_actions_default = [
            'edit' => true,
            'delete' => false,
        ];

        $this->single_actions = [
            ['label'=>'预览','action'=>function($model){
                return '/admin/contractTerm/check?id='.$model->mId;
            }],
        ];

        $this->list_filter = [
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'in'=>true,'class'=>'keep-all']),
            new Page_Admin_TextForeignFilter(['name'=>Form_ComplianceMatter::getFieldViewName('entity_id'),'paramName'=>'name|entity_id','foreignTable'=>'Model_Entity','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('limit_source'),'paramName'=>'limit_source','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('category'),'paramName'=>'category','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('sub_cate'),'paramName'=>'sub_cate','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('scene'),'paramName'=>'scene','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('requirement'),'paramName'=>'requirement','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('expiry'),'paramName'=>'expiry','fusion'=>true]),
            new Page_Admin_ChoiceFilter(['name'=>Form_ComplianceMatter::getFieldViewName('action_req'),'paramName'=>'action_req','choices'=>Model_ComplianceMatter::getActionReqChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_ComplianceMatter::getFieldViewName('action_target'),'paramName'=>'action_target','choices'=>Model_ComplianceMatter::getActionTargetChoices()]),
        ];
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/check.html', $this->_assigned];
    }
}


