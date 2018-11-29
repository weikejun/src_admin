<?php
class ComplianceMatterController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    private function _initForm() {
        $this->form=new Form_ComplianceMatter();
    }

    private function _initListDisplay() {
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
    }

    private function _initSingleActions() {
        $this->single_actions_default = [
            'edit' => true,
            'delete' => false,
        ];

        $this->single_actions = [
            ['label'=>'预览','action'=>function($model){
                return '/admin/ComplianceMatter/check?id='.$model->mId;
            }],
        ];
    }

    private function _initMultiActions() {
        $this->multi_actions = [
            ['label'=>'导出csv','required'=>false,'action'=>'/admin/ComplianceMatter/exportToCsv?method=full&__filter='.urlencode($this->_GET("__filter"))],
        ];
    }

    private function _initListFilter() {
        $this->list_filter = [
            new Page_Admin_TextForeignFilter(['name'=>Form_ComplianceMatter::getFieldViewName('entity_id'),'paramName'=>'name|entity_id','foreignTable'=>'Model_Entity','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_ComplianceMatter::getFieldViewName('category'),'paramName'=>'category','choices'=>Model_ComplianceMatter::getCategoryChoices(),'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('sub_cate'),'paramName'=>'sub_cate','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('action_freq'),'paramName'=>'action_freq','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_ComplianceMatter::getFieldViewName('constrained_entitys'),'paramName'=>'constrained_entitys','fusion'=>true,'class'=>'keep-all']),
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


    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_ComplianceMatter();
        WinRequest::mergeModel(array(
            'controllerText'=>"合规审查事项",
            '_preview' => true,
        ));


    }

    private function _initFullAction() {
        $this->_initForm();
        $this->_initListDisplay();
        $this->_initSingleActions();
        $this->_initMultiActions();
        $this->_initListFilter();

        WinRequest::mergeModel(array(
            'tableWrap' => '3072px',
        ));

        $this->multi_actions[] = ['label'=>'常用字段','required'=>false,'action'=>trim('/admin/ComplianceMatter?'.$_SERVER['QUERY_STRING'],'?')];
    }

    public function fullAction() {
        $this->_initFullAction();
        return parent::indexAction();
    }

    private function _initIndexAction() {
        $this->_initForm();
        $this->_initListDisplay();
        $this->_initSingleActions();
        $this->_initMultiActions();
        $this->_initListFilter();

        WinRequest::mergeModel(array(
            'tableWrap' => '2048px',
        ));

        $briefFields = [
            Form_ComplianceMatter::getFieldViewName('id') => [],
            Form_ComplianceMatter::getFieldViewName('limit_source_type') => [],
            Form_ComplianceMatter::getFieldViewName('entity_id') => [],
            Form_ComplianceMatter::getFieldViewName('constrained_entitys') => [],
            Form_ComplianceMatter::getFieldViewName('category') => [],
            Form_ComplianceMatter::getFieldViewName('sub_cate') => [],
            Form_ComplianceMatter::getFieldViewName('potence') => [],
            Form_ComplianceMatter::getFieldViewName('requirement') => [],
            Form_ComplianceMatter::getFieldViewName('action_req') => [],
            Form_ComplianceMatter::getFieldViewName('action_target') => [],
            Form_ComplianceMatter::getFieldViewName('action_freq') => [],
            Form_ComplianceMatter::getFieldViewName('expiry') => [],
            Form_ComplianceMatter::getFieldViewName('create_time') => [],
        ];

        $list_display = $this->list_display;
        $this->list_display = [];
        for($i = 0; $i < count($list_display); $i++) {
            if (isset($briefFields[$list_display[$i]['label']])) {
                $briefFields[$list_display[$i]['label']] = $list_display[$i];
            }
        }
        $this->list_display = array_values($briefFields);

        $this->multi_actions[] = array('label'=>'全部字段','required'=>false,'action'=>trim('/admin/ComplianceMatter/full?'.$_SERVER['QUERY_STRING'],'?'));
    }

    public function indexAction() {
        $this->_initIndexAction();
        return parent::indexAction();
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/check.html', $this->_assigned];
    }

    /* 
     * 自动保存
     */
    public function autoSaveAction() {
        if ($_REQUEST['action'] == 'create'
            || $_REQUEST['action'] == 'update') {
            $this->indexAction();
        }
        return ['json:', ['json'=>['id'=>$this->model->mId, 'stamp'=>date('H:i:s')]]];
    }

    /*
     * 重载ExportActions.initData方法
     */
    public function initData() {
        $this->_initFullAction();
    }
}


