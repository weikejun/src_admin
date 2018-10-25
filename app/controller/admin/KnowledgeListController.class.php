<?php
class KnowledgeListController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_KnowledgeList();
        $this->model->orderBy('id', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"知识列表",
            'tableWrap' => '2048px',
        ));

        $this->form=new Form_KnowledgeList();
        $this->list_display = [];
        foreach(Form_KnowledgeList::getFieldsMap() as $field) {
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

        $this->single_actions = [
            ['label'=>'预览','action'=>function($model){
                return '/admin/knowledgeList/check?id='.$model->mId;
            }],
        ];

        $this->multi_actions = [
            array('label'=>'导出csv','required'=>false,'action'=>'/admin/knowledgeList/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        ];

        $this->list_filter = [
            new Page_Admin_TextForeignFilter(['name'=>Form_KnowledgeList::getFieldViewName('cate_id'),'paramName'=>'name|cate_id','foreignTable'=>'Model_KnowledgeCate','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_KnowledgeList::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'in'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_KnowledgeList::getFieldViewName('name'),'paramName'=>'name','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_KnowledgeList::getFieldViewName('content'),'paramName'=>'content','fusion'=>true,'class'=>'keep-all']),
        ];
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/check.html', $this->_assigned];
    }
}


