<?php
class EntityController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_Entity();
        $this->model->orderBy('update_time', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"投资主体",
        ));
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form_Entity();
        $this->list_display = [];
        foreach(Form_Entity::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }

        $this->single_actions=[
            ['label'=>'交易记录','action'=>function($model){
                return '/admin/project?__filter='.urlencode('entity_id='.$model->mId);
            }],
            ['label'=>'关系','action'=>function($model){
                return '/admin/entityRel?__filter='.urlencode('subject_id='.$model->mId);
            }],
        ];

        $this->single_actions_default = [
            'edit' => true,
            'delete' => false,
        ];

        /*$this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'主体名称','paramName'=>'name','fusion'=>true]),
        );*/
        $this->search_fields = ['name'];
    }

    public function select() {
        $this->model->addWhere('tp', '主基金相关');
        return parent::select();
    }

    public function select_search(){
        $model=$this->model;
        $search=trim($this->_GET('search'));
        $this->assign("search",$search);
        foreach($this->search_fields as $field){
            $model->addWhereRaw("($field = '$search' or (`tp` = '主基金相关' and $field like '%$search%'))");
            //$model->addWhere($field,"%$search%",'like','or');
        }
        $this->_index();
        $this->display("admin/base/select.html");
    }
}


