<?php
class ControllerActualController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

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

        $this->list_filter = [
            new Page_Admin_TextFilter(['name'=>Form_ControllerActual::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'class'=>'keep-all','in'=>true]),
            new Page_Admin_TextFilter(['name'=>Form_ControllerActual::getFieldViewName('name'),'paramName'=>'name','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_ControllerActual::getFieldViewName('description'),'paramName'=>'description','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_ControllerActual::getFieldViewName('contact'),'paramName'=>'contact','fusion'=>true,'class'=>'keep-all']),
        ];

        $this->single_actions_default = ['delete'=>false,'edit'=>true];

        $this->multi_actions = [
            ['label'=>'导出csv','required'=>false,'action'=>'/admin/ControllerActual/exportToCsv?method=full&__filter='.urlencode($this->_GET("__filter"))],
        ];
    }
}


