<?php
class DealMemoController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_DealMemo();
        $this->model->orderBy('id', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"交易备注",
        ));

        $this->form=new Form_DealMemo();
        $this->list_display = [];
        foreach(Form_DealMemo::getFieldsMap() as $field) {
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

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>Form_DealMemo::getFieldViewName('project_id'),'paramName'=>'project_id','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>Form_DealMemo::getFieldViewName('update_time'),'paramName'=>'update_time']),

        );
        //$this->search_fields = ['name','description','tp'];
    }
}


