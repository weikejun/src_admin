<?php
class MailStrategyController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_MailStrategy();
        $this->model->orderBy('id', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText' => "提醒邮件策略",
            'tableWrap' => '3096px',
        ));

        $this->form=new Form_MailStrategy();
        $this->list_display = [];
        foreach(Form_MailStrategy::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }

        $this->list_filter = [
        ];

        $this->single_actions=[
            ['label'=>'预览','action'=>function($model){
                return '/admin/MailStrategy/check?id='.$model->mId;
            }],
        ];
        //$this->search_fields = ['name','description','tp'];
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/check.html', $this->_assigned];
    }
}


