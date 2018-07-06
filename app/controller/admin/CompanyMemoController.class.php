<?php
class CompanyMemoController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_CompanyMemo();
        $this->model->orderBy('id', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"企业备注",
        ));

        $this->form=new Form_CompanyMemo();
        $this->list_display = [];
        foreach(Form_CompanyMemo::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }

        $this->single_actions_default = [
            'edit' => false,
            'delete' => true,
        ];

        $this->list_filter=array(
            new Page_Admin_TextForeignFilter(['name'=>Form_CompanyMemo::getFieldViewName('company_id'),'paramName'=>'short|company_id','foreignTable'=>'Model_Company','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>Form_CompanyMemo::getFieldViewName('update_time'),'paramName'=>'update_time']),

        );
        //$this->search_fields = ['name','description','tp'];
    }
}


