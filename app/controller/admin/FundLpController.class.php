<?php
class FundLpController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    private function _initForm() {
        $this->form=new Form_FundLp();
    }

    private function _initListDisplay() {
        $this->list_display = [];
        foreach(Form_FundLp::getFieldsMap() as $field) {
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
        $this->single_actions=[
            ['label'=>'预览','action'=>function($model){
                return '/admin/fundLp/check?id='.$model->mId;
            }],
            ['label'=>'复制','action'=>function($model){
                return '/admin/fundLp?action=clone&ex=gp_mailed,gp_mailed_detail,lp_mailed,lp_mailed_detail,gp_received,mail_receive_date,mailing_memo,create_time&id='.$model->mId;
            }],
        ];

        $this->single_actions_default = [
            'delete' => false,
            'edit' => true,
        ];
    }

    private function _initMultiActions() {
        $this->multi_actions=array(
            array('label'=>'导出csv','required'=>false,'action'=>'/admin/fundLp/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        );
    }

    private function _initListFilter() {
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>Form_FundLp::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'in'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_FundLp::getFieldViewName('entity_id'),'paramName'=>'entity_id','fusion'=>false,'class'=>'keep-all']),
            new Page_Admin_TextForeignFilter(['name'=>'募资主体名称','paramName'=>'name|entity_id','fusion'=>false,'foreignTable'=>'Model_Entity','class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('is_exit'),'paramName'=>'is_exit','choices'=>Model_FundLp::getIsExitChoices(),'class'=>'keep-all']),
        );
    }

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        WinRequest::mergeModel(array(
            'controllerText'=>"基金LP表",
            '_preview' => true,
            //'tableWrap' => '8192px',
        ));

        $this->model=new Model_FundLp();
    }

    private function _initFullAction() {
        $this->_initForm();
        $this->_initListDisplay();
        $this->_initSingleActions();
        $this->_initMultiActions();
        $this->_initListFilter();

        WinRequest::mergeModel(array(
            'tableWrap' => '7000px',
        ));

        $this->multi_actions[] = ['label'=>'常用字段','required'=>false,'action'=>trim('/admin/fundLp?'.$_SERVER['QUERY_STRING'],'?')];
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
            //'tableWrap' => '3064px',
        ));

        $briefFields = [
            Form_FundLp::getFieldViewName('id') => [],
            Form_FundLp::getFieldViewName('entity_id') => [],
            Form_FundLp::getFieldViewName('_entity_cate') => [],
            Form_FundLp::getFieldViewName('_entity_currency') => [],
            Form_FundLp::getFieldViewName('subscriber') => [],
            Form_FundLp::getFieldViewName('subscriber_code') => [],
        ];

        $list_display = $this->list_display;
        $this->list_display = [];
        for($i = 0; $i < count($list_display); $i++) {
            if (isset($briefFields[$list_display[$i]['label']])) {
                $briefFields[$list_display[$i]['label']] = $list_display[$i];
            }
        }
        $this->list_display = array_values($briefFields);

        $this->multi_actions[] = array('label'=>'全部字段','required'=>false,'action'=>trim('/admin/fundLp/full?'.$_SERVER['QUERY_STRING'],'?'));
    }

    public function indexAction() {
        $this->_initIndexAction();
        return parent::indexAction();
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

    protected function _initSelect() {
        $this->_initListDisplay();
        $this->list_filter = [];
        $this->search_fields = [];
        $reqModel = WinRequest::getModel();
        unset($reqModel['tableWrap']);
        WinRequest::setModel($reqModel);
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'认购人ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextForeignFilter(['name'=>Form_FundLp::getFieldViewName('entity_id'),'paramName'=>'name|entity_id','foreignTable'=>'Model_Entity','fusion'=>true]),
        );
        foreach($this->list_display as $i => $field) {
            if (in_array($field['name'], ['id', 'subscriber', 'subscriber_code', 'entity_id', '_entity_cate','_entity_currency'])) {
                $list_display[] = $field;
            }
        }
        $this->list_display = $list_display;
    }

    public function select() {
        $this->_initSelect();
        return parent::select();
    }

    public function select_search(){
        $this->_initSelect();
        return parent::select_search();
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/check.html', $this->_assigned];
    }

    public function clone() {
        $ret = parent::clone();
        // 支持部分字段不复制
        if ($_GET['ex']) {
            $ex = explode(',', trim($_GET['ex']));
            $fields = $this->form->getConfig();
            for($i = 0; $i < count($fields); $i++) {
                if (in_array($fields[$i]->name(), $ex)) {
                    $fields[$i]->clone_clear();
                }
            }
        }
        return $ret;
    }
}


