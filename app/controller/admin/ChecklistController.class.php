<?php
class ChecklistController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_Checklist();
        $this->model->orderBy('id', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"Checklist清单",
        ));

        $this->form=new Form_Checklist();
        $this->list_display = [];
        foreach(Form_Checklist::getFieldsMap() as $field) {
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

        $this->list_filter=array(
            new Page_Admin_ChoiceFilter(['name'=>Form_Checklist::getFieldViewName('field'),'paramName'=>'field','choices'=>Model_Checklist::getFieldChoices(),'class'=>'keep-all']),
        );

        $this->single_actions=[
            ['label'=>'预览','action'=>function($model){
                return '/admin/Checklist/check?id='.$model->mId;
            }],
            ['label'=>'复制','action'=>function($model){
                return '/admin/fundChecklist?action=clone&ex=&id='.$model->mId;
            }],
        ];
        //$this->search_fields = ['name','description','tp'];
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/check.html', $this->_assigned];
    }
}


