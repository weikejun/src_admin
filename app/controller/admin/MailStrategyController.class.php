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
            'controllerText' => "交易提醒策略",
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
        //$this->search_fields = ['name','description','tp'];
    }
}


