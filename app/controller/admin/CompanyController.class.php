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
            ['label'=>'预览','action'=>function($model){
                return '/admin/company/check?id='.$model->mId;
            }],
            ['label'=>'审阅','action'=>function($model){
                return '/admin/systemLog/diff?resource=company&res_id='.$model->mId;
            }],
            ['label'=>'memo','target'=>'_blank','action'=>function($model){
                return '/admin/company/memo?id='.$model->mId;
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
            new Page_Admin_TextFilter(['name'=>Form_Company::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'in'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Company::getFieldViewName('short'),'paramName'=>'short','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Company::getFieldViewName('main_founders'),'paramName'=>'main_founders','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Company::getFieldViewName('name'),'paramName'=>'name','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Company::getFieldViewName('project_type'),'paramName'=>'project_type','choices'=>Model_Company::getProjectTypeChoices(),'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Company::getFieldViewName('hold_status'),'paramName'=>'hold_status','choices'=>Model_Company::getHoldStatusChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Company::getFieldViewName('management'),'paramName'=>'management','choices'=>Model_Company::getManagementChoices()]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Company::getFieldViewName('partner'),'paramName'=>'name|partner','foreignTable'=>'Model_Member','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Company::getFieldViewName('manager'),'paramName'=>'name|manager','foreignTable'=>'Model_Member','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Company::getFieldViewName('legal_person'),'paramName'=>'name|legal_person','foreignTable'=>'Model_Member','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Company::getFieldViewName('finance_person'),'paramName'=>'name|finance_person','foreignTable'=>'Model_Member','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Company::getFieldViewName('_company_character'),'paramName'=>'id|id','foreignTable'=>'Model_Project','fusion'=>false,'preSearch'=>function($val){$model = new Model_Project;$model->setCols(['company_id'])->addComputedCol('MAX(id)', 'id')->addWhere('status','valid')->addWhereRaw(' and (`close_date` > 0 or `count_captable` = "计入")')->groupBy('company_id');$ids=array_keys($model->findMap('id'));$model=new Model_Project;$model->addWhere('id',$ids,'IN')->addWhere('company_character',"%$val%",'like');$model=$model->findMap('id');return array_keys($model);},'forSelField'=>'company_id']),
            new Page_Admin_TextForeignFilter(['name'=>'投资主体ID','paramName'=>'entity_id|id','foreignTable'=>'Model_Project','fusion'=>true,'forSelField'=>'company_id']),
        );
    }

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        WinRequest::mergeModel(array(
            'controllerText'=>"目标企业",
            '_preview' => true,
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
            if (!isset($persIds['all'])) {
                $this->model->addWhere('id', $persIds['company'], 'IN', DBTable::ESCAPE);
            }
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
            'tableWrap' => '3200px',
        ));

        $briefFields = [
            Form_Company::getFieldViewName('id') => [],
            Form_Company::getFieldViewName('code') => [],
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
            Form_Company::getFieldViewName('partner') => [],
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
        $this->search_fields = ['name','short','partner','manager','legal_person','finance_person','project_type'];
        $reqModel = WinRequest::getModel();
        unset($reqModel['tableWrap']);
        WinRequest::setModel($reqModel);
        $this->list_filter=array(
            new Page_Admin_TextForeignFilter(['name'=>'投资主体ID','paramName'=>'entity_id|id','foreignTable'=>'Model_Project','fusion'=>true,'forSelField'=>'company_id']),
        );
        foreach($this->list_display as $i => $field) {
            if (in_array($field['name'], ['id', 'name', 'short', 'bussiness', 'project_type','partner','manager','legal_person','finance_person'])) {
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
        return parent::select_search();
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/check.html', $this->_assigned];
    }

    public function memoAction() {
        $reqModel = WinRequest::getModel();
        $reqModel['controllerText'] = '目标企业 > Memo';
        WinRequest::setModel($reqModel);
        if(isset($_GET['id'])) {
            $company = new Model_Company;
            $company->addWhere('id', $_GET['id']);
            $company->select();
            $deals = new Model_Project;
            $deals->addWhere('status', 'valid');
            $deals->addWhere('company_id', $_GET['id']);
            $deals->orderBy('id','ASC');
            $deals = $deals->find();
            $vars['deals'] = [];
            foreach($deals as $i => $deal) {
                // 字段统一赋值
                foreach($deal->getData() as $fKey => $fVal) {
                    $vars['deals'][$fKey][$i] = $fVal;
                }
                // 合计持股数
                foreach(Form_Project::getFieldsMap() as $fieldArr) {
                    if (is_callable($fieldArr['field'])) {
                        $vars['deals'][$fieldArr['name']][$i] = call_user_func($fieldArr['field'], $deal);
                    }
                }
                // 工作备忘
                $vars['deals']['_memo'][$i] = '';
                $memos = new Model_DealMemo;
                $memos->addWhere('project_id', $deal->getData('id'));
                $memos = $memos->find();
                foreach($memos as $memo) {
                    $vars['deals']['_memo'][$i] .= date('Ymd H:i:s', $memo->getData('update_time'))." ".$memo->getData('operator')." ".$memo->getData('title')." ".$memo->getData('content').'<br />';
                }
            }
            // 合并列
            $combine = [];
            $i = 0;
            $j = 1;
            while($i < count($vars['deals']['turn_sub'])) {
                $dealArr = $vars['deals'];
                if (isset($dealArr['turn_sub'][$i + $j]) && $dealArr['turn_sub'][$i] == $dealArr['turn_sub'][$i + $j]) { // 同轮次
                    foreach($dealArr as $fKey => $fArr) {
                        if ($dealArr[$fKey][$i] == $dealArr[$fKey][$i + $j]) {
                            if (isset($combine[$fKey][$i])) {
                                $combine[$fKey][$i]++;
                            }
                            else {
                                $combine[$fKey][$i] = 2;
                            }
                            $vars['deals']["__$fKey"][$i + $j] = true;
                        }
                    }
                    $j++;
                    continue;
                }  

                $i = $i + $j; // 比较下一轮次
                $j = 1;
            }
            $vars['company'] = $company->getData();
            $vars['deal_cols'] = 2 + count($deals);
            $vars['deal_count'] = count($deals);
            $vars['combine'] = $combine;
        }
        if (isset($_GET['format']) && $_GET['format'] == 'excel') {
            $template = DefaultViewSetting::getTemplate();
            DefaultViewSetting::setTemplateSetting($template);
            $template->assign(['vars' => $vars]);
            $tableStr = $template->fetch('admin/company/_memo_table.html');
            $tableStr = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta http-equiv=Content-Type content="text/html; charset=utf-8" /><meta name=ProgId content=Excel.Sheet /><meta name=Generator content="Microsoft Excel 11" /></head><body>'.$tableStr.'</body></html>';
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=".$vars['company']['short']."_memo.xls");
            print $tableStr;
            die();
        }
        return ['admin/company/memo.html', ['vars' => $vars]];
    }
}


