<?php
class EntityController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_Entity();
        $this->model->orderBy('update_time', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText'=>"投资主体",
            '_preview' => true,
            'tableWrap' => "6400px",
        ));
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form_Entity();
        $this->list_display = [];
        foreach(Form_Entity::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }

        $this->single_actions=[
            ['label'=>'预览','action'=>function($model){
                return '/admin/entity/check?id='.$model->mId;
            }],
        ];

        $this->single_actions_default = [
            'edit' => true,
            'delete' => false,
        ];

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>Form_Entity::getFieldViewName('name'),'paramName'=>'name','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Entity::getFieldViewName('currency'),'paramName'=>'currency','choices'=>Model_Project::getCurrencyChoices(),'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Entity::getFieldViewName('cate'),'paramName'=>'cate','choices'=>Model_Entity::getCateChoices(),'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Entity::getFieldViewName('id'),'paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>Form_Entity::getFieldViewName('register_country'),'paramName'=>'register_country','fusion'=>true]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Entity::getFieldViewName('tp'),'paramName'=>'tp','choices'=>Model_Entity::getTpChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Entity::getFieldViewName('org_type'),'paramName'=>'org_type','choices'=>Model_Entity::getOrgTypeChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Entity::getFieldViewName('co_investment'),'paramName'=>'co_investment','choices'=>Model_Entity::getCoInvestmentChoices()]),
            new Page_Admin_TextForeignFilter(['name'=>'子主体','paramName'=>'sub_id|id','foreignTable'=>'Model_EntityRel','fusion'=>false,'forSelField'=>'parent_id']),
            new Page_Admin_TextForeignFilter(['name'=>'父主体','paramName'=>'parent_id|id','foreignTable'=>'Model_EntityRel','fusion'=>false,'forSelField'=>'sub_id']),
        );
        $this->multi_actions=array(
            ['label'=>'导出csv','required'=>false,'action'=>'/admin/entity/exportToCsv?__filter='.urlencode($this->_GET("__filter"))],
        );
    }

    protected function _initSelect() {
        $reqModel = WinRequest::getModel();
        unset($reqModel['tableWrap']);
        WinRequest::setModel($reqModel);
        $list_display = [];
        foreach($this->list_display as $i => $field) {
            if (in_array($field['name'], ['id', 'name', 'description', 'register_country', 'tp', 'currency', 'co_investment'])) {
                $list_display[] = $field;
            }
        }
        $this->list_display = $list_display;
        $this->list_filter = [];
        $this->search_fields = ['name'];
    }

    public function select() {
        $this->_initSelect();
        $this->model->addWhere('tp', '主基金相关');
        return parent::select();
    }

    public function select_search(){
        $this->_initSelect();
        $model=$this->model;
        $search=trim($this->_GET('search'));
        $this->assign("search",$search);
        foreach($this->search_fields as $field){
            //$model->addWhereRaw("($field = '$search' or (`tp` = '主基金相关' and $field like '%$search%'))");
            $model->addWhere($field, $search, '=');
            $model->addWhere('tp', '主基金相关', '=', 'or (');
            $model->addWhere($field, "%$search%", 'like', 'and');
            $model->addWhere($field, $search, '=', ') or');
        }
        $this->_index();
        $this->display("admin/base/select.html");
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/check.html', $this->_assigned];
    }
}


