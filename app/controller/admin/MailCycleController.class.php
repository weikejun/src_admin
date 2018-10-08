<?php
class MailCycleController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_MailCycle();
        $this->model->orderBy('id', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText' => "交易提醒策略 > 发送周期",
        ));

        $this->form=new Form_MailCycle();
        $this->list_display = [];
        foreach(Form_MailCycle::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }

        $this->list_filter = [
            new Page_Admin_TextFilter(['name'=>Form_MailCycle::getFieldViewName('strategy_id'),'paramName'=>'strategy_id','fusion'=>false,'in'=>true,'hidden'=>true,'class'=>'keep-all']),
            new Page_Admin_TextForeignFilter(['name'=>Form_MailCycle::getFieldViewName('strategy_id'),'paramName'=>'name|strategy_id','foreignTable'=>'Model_MailStrategy','fusion'=>true,'class'=>'keep-all']),
        ];
        //$this->search_fields = ['name','description','tp'];
    }
}


