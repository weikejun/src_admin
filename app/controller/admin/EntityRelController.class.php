<?php
class EntityRelController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_EntityRel();
        WinRequest::mergeModel(array(
            'controllerText' => "投资主体 > 主体关系",
        ));

        $this->form=new Form_EntityRel();
        $this->list_display = [];
        foreach(Form_EntityRel::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }

        $this->list_filter = [
            new Page_Admin_TextFilter(['name'=>Form_EntityRel::getFieldViewName('parent_id'),'paramName'=>'parent_id','fusion'=>false,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_EntityRel::getFieldViewName('sub_id'),'paramName'=>'sub_id','fusion'=>false,'class'=>'keep-all']),
        ];

        $this->single_actions_default = ['delete'=>true,'edit'=>false];
        //$this->search_fields = ['name','description','tp'];
    }
}


