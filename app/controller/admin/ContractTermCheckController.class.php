<?php
class ContractTermCheckController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_ContractTerm();
        $this->model->addWhere('status', '未审核');
        $this->model->orderBy('update_time', 'ASC');
        WinRequest::mergeModel(array(
            'controllerText'=>"合同条款审核",
            'tableWrap' => '4096px',
            '_preview' => true,
        ));

        $this->form=new Form_ContractTerm('check');
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
            'delete' => true,
        ];

        $this->hide_action_new = true;

        $this->single_actions = [
            ['label'=>'预览','action'=>function($model){
                return '/admin/contractTermCheck/check?id='.$model->mId;
            }],
            ['label'=>'审阅','action'=>function($model){
                return '/admin/systemLog/diff?resource=ContractTerm&res_id='.$model->mId;
            }],
        ];

        $this->multi_actions = [];

        $this->list_filter = [];
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/check.html', $this->_assigned];
    }
}


