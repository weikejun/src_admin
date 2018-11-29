<?php
class ControllerActualController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_ControllerActual();
        $this->model->orderBy('id', 'ASC');
        WinRequest::mergeModel(array(
            'controllerText' => "LP实际控制人",
        ));

        $this->form=new Form_ControllerActual();
        $this->list_display = [];
        foreach(Form_ControllerActual::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }

        $this->search_fields = ['name','description','contact'];

        $this->single_actions_default = ['delete'=>false,'edit'=>true];
    }
}


