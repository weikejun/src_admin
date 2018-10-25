<?php
class DealDecisionController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_DealDecision();
        $this->model->orderBy('decision', 'ASC');
        WinRequest::mergeModel(array(
            'controllerText'=>"投决意见",
            'tableWrap' => '2048px',
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

        //$this->hide_action_new = true;

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>Form_DealDecision::getFieldViewName('project_id'),'paramName'=>'project_id','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextForeignFilter(['name'=>Form_DealDecision::getFieldViewName('_company_short'),'paramName'=>'company_id|project_id','foreignTable'=>'Model_Project','fusion'=>false,'preSearch'=>function($val) {$model=new Model_Company;$model->addWhere('short',"%$val%",'like');$model=$model->findMap('id');return array_keys($model);},'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_DealDecision::getFieldViewName('decision'),'paramName'=>'decision','choices'=>Model_DealDecision::getDecisionChoices(),'class'=>'keep-all']),
            new Page_Admin_TextForeignFilter(['name'=>Form_DealDecision::getFieldViewName('partner'),'paramName'=>'name|partner','foreignTable'=>'Model_Member','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TimeRangeFilter(['name'=>Form_DealDecision::getFieldViewName('expiration'),'paramName'=>'expiration','dateClass'=>'datetimepicker']),
            new Page_Admin_TimeRangeFilter(['name'=>Form_DealDecision::getFieldViewName('create_time'),'paramName'=>'create_time','dateClass'=>'datetimepicker']),

        );
        //$this->search_fields = ['name','description','tp'];
        $this->multi_actions=array(
            ['label'=>'导出excel','required'=>false,'action'=>'/admin/dealDecision/exportToCsv?method=full&__filter='.urlencode($this->_GET("__filter"))],
        );
    }
}


