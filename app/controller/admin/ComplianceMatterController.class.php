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
                return '/admin/ComplianceMatter/check?id='.$model->mId;
            }],
        ];

        $this->list_filter = [
            new Page_Admin_TextForeignFilter(['name'=>Form_ComplianceMatter::getFieldViewName('entity_id'),'paramName'=>'name|entity_id','foreignTable'=>'Model_Entity','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_ComplianceMatter::getFieldViewName('category'),'paramName'=>'category','choices'=>Model_ComplianceMatter::getCategoryChoices(),'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('sub_cate'),'paramName'=>'sub_cate','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('action_freq'),'paramName'=>'action_freq','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_ComplianceMatter::getFieldViewName('potence'),'paramName'=>'potence','choices'=>Model_ComplianceMatter::getPotenceChoices(),'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'in'=>true]),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('action_req'),'paramName'=>'action_req']),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('action_target'),'paramName'=>'action_target']),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('limit_source_type'),'paramName'=>'limit_source_type']),
            new Page_Admin_TimeRangeFilter(['name'=>Form_ComplianceMatter::getFieldViewName('expiry'),'paramName'=>'expiry']),
            new Page_Admin_TextForeignFilter(['name'=>Form_ComplianceMatter::getFieldViewName('_legal_person'),'paramName'=>'legal_person|entity_id','foreignTable'=>'Model_Entity','fusion'=>false,'preSearch'=>function($val) {return Model_Member::getIdsByName($val);}]),
            new Page_Admin_TextForeignFilter(['name'=>Form_ComplianceMatter::getFieldViewName('_finance_person'),'paramName'=>'finance_person|entity_id','foreignTable'=>'Model_Entity','fusion'=>false,'preSearch'=>function($val) {return Model_Member::getIdsByName($val);}]),
        ];
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/check.html', $this->_assigned];
    }
}


