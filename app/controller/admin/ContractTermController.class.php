<?php
class ContractTermController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_ContractTerm();
        $this->model->orderBy('id', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"知识列表",
            'tableWrap' => '4096px',
            '_preview' => true,
        ));

        $this->form=new Form_ContractTerm();
        $this->list_display = [];
        foreach(Form_ContractTerm::getFieldsMap() as $field) {
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

        $this->multi_actions = [
            array('label'=>'导出csv','required'=>false,'action'=>'/admin/ContractTerm/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        ];

        $this->list_filter = [
            new Page_Admin_TextFilter(['name'=>Form_ContractTerm::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'in'=>true]),
            new Page_Admin_TextFilter(['name'=>Form_ContractTerm::getFieldViewName('trade_doc'),'paramName'=>'trade_doc','class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_ContractTerm::getFieldViewName('term'),'paramName'=>'term','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>Form_ContractTerm::getFieldViewName('term_detail'),'paramName'=>'term_detail','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_ContractTerm::getFieldViewName('operator'),'paramName'=>'operator','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>Form_ContractTerm::getFieldViewName('create_time'),'paramName'=>'create_time']),
        ];
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/check.html', $this->_assigned];
    }
}


