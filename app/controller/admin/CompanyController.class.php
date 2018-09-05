<?php
class CompanyController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    private function _initForm() {
        $this->form=new Form_Company();
    }

    private function _initListDisplay() {
        $this->list_display = [];
        foreach(Form_Company::getFieldsMap() as $field) {
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
            ['label'=>'审阅','action'=>function($model){
                return '/admin/systemLog/diff?resource=company&res_id='.$model->mId;
            }],
        ];

        $this->single_actions_default = [
            'delete' => false,
            'edit' => true,
        ];
    }

    private function _initMultiActions() {
        $this->multi_actions=array(
            array('label'=>'导出csv','required'=>false,'action'=>'/admin/company/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        );
    }

    private function _initListFilter() {
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>Form_Company::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'in'=>true,'hidden'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Company::getFieldViewName('name'),'paramName'=>'name','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Company::getFieldViewName('short'),'paramName'=>'short','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Company::getFieldViewName('project_type'),'paramName'=>'project_type','choices'=>Model_Company::getProjectTypeChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Company::getFieldViewName('hold_status'),'paramName'=>'hold_status','choices'=>Model_Company::getHoldStatusChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Company::getFieldViewName('management'),'paramName'=>'management','choices'=>Model_Company::getManagementChoices()]),
            new Page_Admin_TextFilter(['name'=>Form_Company::getFieldViewName('manager'),'paramName'=>'manager','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>Form_Company::getFieldViewName('legal_person'),'paramName'=>'legal_person','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Company::getFieldViewName('finance_person'),'paramName'=>'finance_person','fusion'=>true]),
        );
    }

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        WinRequest::mergeModel(array(
            'controllerText'=>"目标企业",
        ));

        $this->model=new Model_Company();
        $this->model->orderBy('update_time', 'DESC');
        $this->model->on('after_insert', function($model) {
            if (!$model->getData('id')) {
                return false;
            }
            $adminId = Model_Admin::getCurrentAdmin()->mId;
            $finder = new Model_ItemPermission;
            $finder->addWhere('admin_id', $adminId);
            $finder->addWhere('company_id', $model->getData('id'));
            $finder->addWhere('project_id', '');
            if ($finder->count()) {
                return;
            }
            $itemPer = new Model_ItemPermission;
            $itemPer->setData([
                'admin_id' => $adminId,
                'company_id' => $model->getData('id'),
                'project_id' => '',
                'operator_id' => '',
                'create_time' => time(),
            ]);
            $itemPer->save();
        });
        if (!Model_AdminGroup::isCurrentAdminRoot()) {
            $persIds = Model_ItemPermission::getAdminItem();
            $this->model->addWhere('id', $persIds['company'], 'IN', DBTable::ESCAPE);
        }
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

        $this->multi_actions[] = ['label'=>'常用字段','required'=>false,'action'=>trim('/admin/company?'.$_SERVER['QUERY_STRING'],'?')];
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
            'tableWrap' => '3064px',
        ));

        $briefFields = [
            Form_Company::getFieldViewName('id') => [],
            Form_Company::getFieldViewName('short') => [],
            Form_Company::getFieldViewName('project_type') => [],
            Form_Company::getFieldViewName('_captable') => [],
            Form_Company::getFieldViewName('_deal_num') => [],
            Form_Company::getFieldViewName('_latest_invest_turn') => [],
            Form_Company::getFieldViewName('_financing_amount_all') => [],
            Form_Company::getFieldViewName('_latest_post_moeny') => [],
            Form_Company::getFieldViewName('_latest_shareholding_ratio_sum') => [],
            Form_Company::getFieldViewName('_invest_amount') => [],
            Form_Company::getFieldViewName('_have_exit') => [],
            Form_Company::getFieldViewName('_exit_amount') => [],
            Form_Company::getFieldViewName('_exit_amount_cost') => [],
            Form_Company::getFieldViewName('_director_status') => [],
            Form_Company::getFieldViewName('_director_name') => [],
            Form_Company::getFieldViewName('_board_veto') => [],
            Form_Company::getFieldViewName('_holder_veto') => [],
            Form_Company::getFieldViewName('_first_manager') => [],
            Form_Company::getFieldViewName('manager') => [],
            Form_Company::getFieldViewName('legal_person') => [],
            Form_Company::getFieldViewName('finance_person') => [],
            Form_Company::getFieldViewName('_pending_detail') => [],
            Form_Company::getFieldViewName('_memo') => [],
        ];

        $list_display = $this->list_display;
        $this->list_display = [];
        for($i = 0; $i < count($list_display); $i++) {
            if (isset($briefFields[$list_display[$i]['label']])) {
                $briefFields[$list_display[$i]['label']] = $list_display[$i];
            }
        }
        $this->list_display = array_values($briefFields);

        $this->multi_actions[] = array('label'=>'全部字段','required'=>false,'action'=>trim('/admin/company/full?'.$_SERVER['QUERY_STRING'],'?'));
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
        $this->search_fields = ['name','short'];
        $reqModel = WinRequest::getModel();
        unset($reqModel['tableWrap']);
        WinRequest::setModel($reqModel);
        $list_display = [];
        foreach($this->list_display as $i => $field) {
            if (in_array($field['name'], ['id', 'name', 'short', 'bussiness', 'project_type'])) {
                $list_display[] = $field;
            }
        }
        $this->list_display = $list_display;
    }

    public function select() {
        $this->_initSelect();
        return parent::select();
        /*
        if(Model_AdminGroup::isCurrentAdminRoot()) { // root用户展示全部信息
            return parent::select();
        }
        $this->display("admin/base/select.html");
         */
    }

    public function select_search(){
        $this->_initSelect();
        $model=$this->model;
        $search=trim($this->_GET('search'));
        $this->assign("search",$search);
        if (!$search) {
            $model->addWhere('id', 0);
        } else {
            foreach($this->search_fields as $field){
                $model->addWhere($field, $search, '=', 'OR');
            }
        }
        $this->_index();
        $this->display("admin/base/select.html");
    }
}


