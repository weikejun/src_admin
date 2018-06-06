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

        $this->form=new Form(array(
            ['name'=>'name','label'=>'名称','type'=>"text",'default'=>null,'required'=>true,'help'=>'填入准确全称'],
            ['name'=>'register_country','label'=>'注册国','type'=>"text",'default'=>null,'required'=>false],
            ['name'=>'description','label'=>'描述','type'=>"text",'default'=>null,'required'=>false,'help'=>'示例“人民币一期早期主基金”，“美元专项基金”，“人民币UGP相关”，其他。'],
            ['name'=>'tp','label'=>'类型','type'=>'selectInput','choices'=>Model_Entity::getTpChoices(),'required'=>false,'help'=>'可问财务同事'],
            ['name'=>'co_investment','label'=>'co-investment','type'=>"choice",'choices'=>[['是','是'],['否','否']], 'default'=>'否','required'=>false,'help'=>'主基金都不是，非主基金的和财务同事确认。'],
            ['name'=>'currency','label'=>'资金货币','type'=>"choice",'choices'=>Model_Project::getCurrencyChoices(), 'default'=>'USD','required'=>false,],
            ['name'=>'update_time','label'=>'更新时间','type'=>"datetime","readonly"=>'true','default'=>time(),'null'=>false,'auto_update'=>true],
        ));
        $this->list_display=array(
            ['label'=>'ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'名称','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'类型','field'=>function($model){
                return $model->mTp;
            }],
            ['label'=>'co-investment','field'=>function($model){
                return $model->mCoInvestment;
            }],
            ['label'=>'资金货币','field'=>function($model){
                return $model->mCurrency;
            }],
            ['label'=>'更新时间','field'=>function($model){
                return date('Y-m-d H:i:s',$model->mUpdateTime);
            }],
        );

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
        $this->display("admin/base/select.html");
    }
}


