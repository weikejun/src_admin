<?php
class MemberController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_Member();
        WinRequest::mergeModel(array(
            'controllerText' => "项目成员",
        ));

        $this->form=new Form_Member();
        $this->list_display = [];
        foreach(Form_Member::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }

        $this->search_fields = ['name','mail'];
    }
}


