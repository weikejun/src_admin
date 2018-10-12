<?php
class DealDecisionController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_DealDecision();
        $this->model->orderBy('decision', 'ASC');
        WinRequest::mergeModel(array(
            'controllerText'=>"投决意见",
        ));

        $this->form=new Form_DealDecision();
        $this->list_display = [];
        foreach(Form_DealDecision::getFieldsMap() as $field) {
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

        //$this->hide_action_new = true;

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>Form_DealDecision::getFieldViewName('project_id'),'paramName'=>'project_id','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TimeRangeFilter(['name'=>Form_DealDecision::getFieldViewName('expiration'),'paramName'=>'expiration','dateClass'=>'datetimepicker','class'=>'keep-all']),
            new Page_Admin_TimeRangeFilter(['name'=>Form_DealDecision::getFieldViewName('create_time'),'paramName'=>'create_time','dateClass'=>'datetimepicker','class'=>'keep-all']),

        );
        //$this->search_fields = ['name','description','tp'];
    }
}


