<?php
class ProjectController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    private $_objectCache = [];

    private function _initForm() {
        $this->form=new Form_Project();
    }

    private function _initListDisplay() {
        $companyCache = new Model_Company;
        $this->list_display = [];
        foreach(Form_Project::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }
    }

    private function _initSingleActions() {
        $this->single_actions=[
            ['label'=>'复制','action'=>function($model){
                return '/admin/project?action=clone&id='.$model->mId;
            }],
            ['label'=>'审阅','action'=>function($model){
                return '/admin/systemLog/diff?resource=project&res_id='.$model->mId;
            }],
        ];

        //$this->single_actions_default = ['delete'=>false];
    }

    private function _initMultiActions() {
        $this->multi_actions=array(
            ['label'=>'回收站', 'required'=>false, 'action'=>'/admin/project/recovery'],
            ['label'=>'导出csv','required'=>false,'action'=>'/admin/project/exportToCsv?method=full&__filter='.urlencode($this->_GET("__filter"))],
        );
    }

    private function _initListFilter() {
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'交易ID','paramName'=>'id','fusion'=>false,'hidden'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'项目名称','paramName'=>'short|company_id','foreignTable'=>'Model_Company','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>'公司名称','paramName'=>'name|company_id','foreignTable'=>'Model_Company','fusion'=>true]),
            new Page_Admin_ChoiceFilter(['name'=>'整理状态','paramName'=>'item_status','choices'=>Model_Project::getItemStatusChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>'轮次大类','paramName'=>'turn','choices'=>Model_Project::getTurnChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>'新老类型','paramName'=>'new_follow','choices'=>Model_Project::getNewFollowChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>'投退类型','paramName'=>'enter_exit_type','choices'=>Model_Project::getEnterExitTypeChoices()]),
        );
    }

    public function __construct(){
        parent::__construct();

        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        $this->model=new Model_Project();
        $this->model->orderBy('id', 'DESC');

        WinRequest::mergeModel(array(
            'controllerText' => '交易记录',
        ));
    }

    private function _initFullAction() {
        $this->_initForm();
        $this->_initListDisplay();
        $this->_initSingleActions();
        $this->_initMultiActions();
        $this->_initListFilter();

        $this->model->addWhere('status', 'valid');
        WinRequest::mergeModel(array(
            'tableWrap' => '20480px',
        ));

        $this->multi_actions[] = ['label'=>'常用字段','required'=>false,'action'=>trim('/admin/project?'.$_SERVER['QUERY_STRING'],'?')];
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
            '交易ID',
            '项目编号',
            '整理状态',
            '目标企业',
            '项目简称',
            '所属行业',
            '轮次大类',
            '轮次详情',
            '新老类型',
            '投退类型',
            '新股老股',
            '决策日期',
            '交易状态',
            'Closing Date',
        ];
        $list_display = $this->list_display;
        $this->list_display = [];
        for($i = 0; $i < count($list_display); $i++) {
            if (in_array($list_display[$i]['label'], $briefFields)) {
                array_push($this->list_display, $list_display[$i]);
            }
        }

        $this->multi_actions[] = array('label'=>'全部字段','required'=>false,'action'=>trim('/admin/project/full?'.$_SERVER['QUERY_STRING'],'?'));
    }

    public function indexAction() {
        $this->_initIndexAction();
        return parent::indexAction();
    }

    public function recoveryAction() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $this->model->addWhere('id', $_REQUEST['id'])->update(['status'=>['"valid"', DBTable::NO_ESCAPE]]);
            return ['redirect: ' . dirname($_SERVER['SCRIPT_NAME'])];
        }
        $this->_initListDisplay();
        $this->model->addWhere('status', 'invalid');
        $this->hide_action_new = true;
        $this->single_actions_default = ['edit'=>false,'delete'=>false];
        $this->single_actions=[
            ['label'=>'恢复','action'=>function($model){
                return '/admin/project/recovery?id='.$model->mId;
            }],
        ];
        WinRequest::mergeModel(array(
            'tableWrap' => '10240px',
        ));
        $reqModel = WinRequest::getModel();
        $reqModel['controllerText'] = '交易记录 回收站';
        WinRequest::setModel($reqModel);
        return parent::indexAction();
    }

    /* 
     * 自动保存
     */
    public function autoSaveAction() {
        if ($_REQUEST['action'] == 'create'
            || $_REQUEST['action'] == 'update') {
            parent::indexAction();
        }
        return ['json:', ['json'=>['id'=>$this->model->mId, 'stamp'=>date('H:i:s')]]];
    }

    /*
     * 重载_delete()方法
     */
    public function _delete() {
        $this->model->addWhere('id', $_REQUEST['id'])->update(['status'=>['"invalid"', DBTable::NO_ESCAPE]]);
    }

    /*
     * 重载ExportActions.initData方法
     */
    public function initData() {
        $this->_initFullAction();
    }
}


