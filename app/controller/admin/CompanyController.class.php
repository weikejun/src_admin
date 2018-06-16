<?php
class CompanyController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        WinRequest::mergeModel(array(
            'controllerText'=>"目标企业",
            'tableWrap' => "7000px",
        ));
        $this->model=new Model_Company();
        $this->model->orderBy('update_time', 'DESC');

        $this->form=new Form_Company();
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

        $this->single_actions=[
            ['label'=>'审阅','action'=>function($model){
                return '/admin/systemLog/diff?resource=company&res_id='.$model->mId;
            }],
        ];

        $this->single_actions_default = [
            'delete' => false,
            'edit' => true,
        ];

        $this->multi_actions=array(
            array('label'=>'导出csv','required'=>false,'action'=>'/admin/company/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        );

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'公司ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'公司名称','paramName'=>'name','fusion'=>true]),
        );
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

    protected function _initSelect() {
        $this->list_filter = [];
        $this->search_fields = ['name'];
        $reqModel = WinRequest::getModel();
        unset($reqModel['tableWrap']);
        WinRequest::setModel($reqModel);
        $list_display = [];
        foreach($this->list_display as $i => $field) {
            if (in_array($field['name'], ['id', 'name', 'short', 'bussiness', 'project_type', 'region', 'register_region'])) {
                $list_display[] = $field;
            }
        }
        $this->list_display = $list_display;
    }

    public function select() {
        $this->_initSelect();
        $this->display("admin/base/select.html");
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
                $model->addWhere($field, $search);
            }
        }
        $this->_index();
        $this->display("admin/base/select.html");
    }
}


