<?php
class CompanyController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        WinRequest::mergeModel(array(
            'controllerText'=>"目标企业",
            'tableWrap' => "6000px",
        ));
        $this->model=new Model_Company();
        $this->model->orderBy('update_time', 'DESC');

        $this->form=new Form_Company();
        $this->list_display = [];
        foreach(Form_Company::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }

        $this->single_actions=[
            ['label'=>'审阅','action'=>function($model){
                return '/admin/systemLog/diff?resource=company&res_id='.$model->mId;
            }],
        ];

        $this->single_actions_default = [
            'delete' => false,
            'edit' => true,
        ];

        $this->multi_actions=array(
            array('label'=>'导出csv','required'=>false,'action'=>'/admin/company/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        );

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'公司ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'公司名称','paramName'=>'name','fusion'=>true]),
        );
    }
}


