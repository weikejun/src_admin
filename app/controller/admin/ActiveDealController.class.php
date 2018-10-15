<?php
class ActiveDealController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    private $_objectCache = [];

    private function _initForm() {
        $this->form=new Form_Project();
    }

    private function _initListDisplay() {
        $this->list_display = [];
        foreach(Form_Project::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator'
                && $field['type'] != 'seperator2') {
                $this->list_display[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }
    }

    private function _initSingleActions() {
        $this->single_actions=[];

        $this->single_actions_default = ['delete'=>false,'edit'=>true];
    }

    private function _initMultiActions() {
        $this->multi_actions = [];
    }

    private function _initListFilter() {
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'class'=>'keep-all']),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('_company_short'),'paramName'=>'short|company_id','foreignTable'=>'Model_Company','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('deal_type'),'paramName'=>'deal_type','choices'=>Model_Project::getDealTypeChoices(),'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('manager'),'paramName'=>'manager','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('legal_person'),'paramName'=>'legal_person','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('finance_person'),'paramName'=>'finance_person','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TimeRangeFilter(['name'=>Form_Project::getFieldViewName('decision_date'),'paramName'=>'decision_date']),
            new Page_Admin_TimeRangeFilter(['name'=>Form_Project::getFieldViewName('expect_sign_date'),'paramName'=>'expect_sign_date']),
        );
    }

    public function __construct(){
        parent::__construct();

        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        $this->model=new Model_Project();
        $this->model->orderBy('deal_type', 'ASC');
        $this->model->addWhere('active_deal', '是');
        if (!Model_AdminGroup::isCurrentAdminRoot()) {
            $persIds = Model_ItemPermission::getAdminItem();
            $this->model->addWhereRaw('(company_id IN ('.implode(',', $persIds['company']).') OR id IN ('.implode(',', $persIds['project']).'))');
        }

        WinRequest::mergeModel(array(
            'controllerText' => 'Active进度表',
            'no_truncate' => true,
        ));

        $this->hide_action_new = true;
    }

    private function _initFullAction() {
        $this->_initForm();
        $this->_initListDisplay();
        $this->_initSingleActions();
        $this->_initMultiActions();
        $this->_initListFilter();

        $this->model->addWhere('status', 'valid');
        WinRequest::mergeModel(array(
            'tableWrap' => '3072px',
        ));

        $briefFields = [
            Form_Project::getFieldViewName('id') => [],
            Form_Project::getFieldViewName('_company_short') => [],
            Form_Project::getFieldViewName('turn_sub') => [],
            Form_Project::getFieldViewName('deal_type') => [],
            Form_Project::getFieldViewName('decision_date') => [],
            Form_Project::getFieldViewName('deal_progress') => [],
            Form_Project::getFieldViewName('trade_schedule_memo') => [],
            Form_Project::getFieldViewName('proj_status') => [],
            Form_Project::getFieldViewName('pre_money') => [],
            Form_Project::getFieldViewName('post_money') => [],
            Form_Project::getFieldViewName('our_amount') => [],
            Form_Project::getFieldViewName('financing_amount') => [],
            Form_Project::getFieldViewName('ts_ratio') => [],
            Form_Project::getFieldViewName('_stock_ratio') => [],
            Form_Project::getFieldViewName('loan_amount') => [],
            Form_Project::getFieldViewName('loan_schedule') => [],
            Form_Project::getFieldViewName('trade_file_schedule') => [],
            Form_Project::getFieldViewName('expect_sign_date') => [],
            Form_Project::getFieldViewName('expect_pay_schedule') => [],
            Form_Project::getFieldViewName('trade_schedule_todo') => [],
            Form_Project::getFieldViewName('manager') => [],
            Form_Project::getFieldViewName('legal_person') => [],
            Form_Project::getFieldViewName('invest_currency') => [],
            Form_Project::getFieldViewName('finance_person') => [],
            Form_Project::getFieldViewName('law_firm') => [],
            Form_Project::getFieldViewName('lawyer_fee') => [],
        ];

        $list_display = $this->list_display;
        $this->list_display = [];
        for($i = 0; $i < count($list_display); $i++) {
            if (isset($briefFields[$list_display[$i]['label']])) {
                $briefFields[$list_display[$i]['label']] = $list_display[$i];
            }
        }
        $this->list_display = array_values($briefFields);

        $this->multi_actions[] = ['label'=>'导出csv','required'=>false,'action'=>'/admin/activeDeal/exportToCsv?method=full&__filter='.urlencode($this->_GET("__filter"))];
        $this->multi_actions[] = ['label'=>'常用字段','required'=>false,'action'=>trim('/admin/activeDeal?'.$_SERVER['QUERY_STRING'],'?')];
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

        $this->model->addWhere('status', 'valid');
        WinRequest::mergeModel(array(
            'tableWrap' => '2048px',
        ));

        $briefFields = [
            Form_Project::getFieldViewName('id') => [],
            Form_Project::getFieldViewName('_company_short') => [],
            Form_Project::getFieldViewName('turn_sub') => [],
            Form_Project::getFieldViewName('deal_type') => [],
            Form_Project::getFieldViewName('decision_date') => [],
            Form_Project::getFieldViewName('deal_progress') => [],
            Form_Project::getFieldViewName('trade_schedule_memo') => [],
            Form_Project::getFieldViewName('post_money') => [],
            Form_Project::getFieldViewName('our_amount') => [],
            Form_Project::getFieldViewName('financing_amount') => [],
            Form_Project::getFieldViewName('_stock_ratio') => [],
            Form_Project::getFieldViewName('loan_amount') => [],
            Form_Project::getFieldViewName('loan_schedule') => [],
            Form_Project::getFieldViewName('expect_pay_schedule') => [],
            Form_Project::getFieldViewName('manager') => [],
        ];

        $list_display = $this->list_display;
        $this->list_display = [];
        for($i = 0; $i < count($list_display); $i++) {
            if (isset($briefFields[$list_display[$i]['label']])) {
                $briefFields[$list_display[$i]['label']] = $list_display[$i];
            }
        }
        $this->list_display = array_values($briefFields);

        $this->multi_actions[] = ['label'=>'导出csv','required'=>false,'action'=>'/admin/activeDeal/exportToCsv?method=index&__filter='.urlencode($this->_GET("__filter"))];
        $this->multi_actions[] = array('label'=>'全部字段','required'=>false,'action'=>trim('/admin/activeDeal/full?'.$_SERVER['QUERY_STRING'],'?'));
    }

    public function indexAction() {
        $this->_initIndexAction();
        return parent::indexAction();
    }

    public function read() {
        return $this->display('redirect:/admin/project?action=read&id='.$_GET['id'].'#field-index-progress');
    }

    public function create() {
        $this->index();
    }

    public function select() {
        $this->index();
    }

    public function delete() {
        $this->index();
    }

    /*
     * 重载ExportActions.initData方法
     */
    public function initData() {
        if (strtolower($_GET['method']) == 'full') {
            return $this->_initFullAction();
        }
        return $this->_initIndexAction();
    }
}


