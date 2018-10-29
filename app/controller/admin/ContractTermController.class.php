<?php
class ContractTermController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    public function getBackUrl() {
        return str_replace('Controller', '', get_class($this)).'/pending';
    }

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_ContractTerm();
        $this->model->addWhere('status', '已审核');
        $this->model->orderBy('update_time', 'DESC');
        $this->model->on('before_update', function($model) {
            if ($model->mStatus == '已审核'
                || empty($model->mStatus)) {
                $model->mStatus = '未审核';
            }
        });
        WinRequest::mergeModel(array(
            'controllerText'=>"合同条款",
            'tableWrap' => '4096px',
            '_preview' => true,
        ));

        $this->form=new Form_ContractTerm(false);
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
            array('label'=>'我的未审核条款','required'=>false,'action'=>'/admin/ContractTerm/pending'),
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

    public function pendingAction() {
        $this->model=new Model_ContractTerm();
        $this->model->addWhere('status', '已审核', '!=');
        $this->model->addWhere('operator', Model_Admin::getCurrentAdmin()->mName);
        $this->model->orderBy('update_time', 'DESC');
        $this->single_actions_default = [
            'edit' => false,
            'delete' => false,
        ];

        $this->hide_item_op = true;

        $this->single_actions = [
            ['label'=>'预览','action'=>function($model){
                return '/admin/contractTerm/check?id='.$model->mId;
            }],
        ];

        $this->multi_actions = [
            array('label'=>'全部已审核条款','required'=>false,'action'=>'/admin/ContractTerm/'),
        ];

        // 审核表中可以对草稿进行修改
        $this->list_display[0] = ['name'=>'_op','label'=>'操作','field'=>function($model) {
            if ($model->mStatus == '草稿') {
                return "<a href='/admin/ContractTerm?action=read&id=$model->mId'>编辑</a>&nbsp;<a href='/admin/ContractTerm?action=delete&id=$model->mId'>删除</a>";
            }
        }];

        return $this->indexAction();
    }
}


