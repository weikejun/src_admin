<?php
class MailListController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_MailList();
        $this->model->orderBy('id', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText' => "交易提醒列表",
            'tableWrap' => '3096px',
        ));

        $this->form=new Form_MailList();
        $this->list_display = [];
        foreach(Form_MailList::getFieldsMap() as $field) {
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


