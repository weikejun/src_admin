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
            array('name'=>'name','label'=>'投资主体名称','type'=>"text",'default'=>null,'required'=>true,'help'=>'填入准确全称'),
            array('name'=>'tp','label'=>'源码主体类型','type'=>"text",'default'=>null,'required'=>true,'help'=>'示例“人民币一期早期主基金”，“美元专项基金”，“人民币UGP相关”，其他。'),
            array('name'=>'co_investment','label'=>'源码主体是否是co-investment','type'=>"choice",'choices'=>[['是','是'],['否','否']], 'default'=>'否','required'=>false,'help'=>'主基金都不是，非主基金的和财务同事确认。'),
            array('name'=>'currency','label'=>'源码主体资金货币','type'=>"choice",'choices'=>[['USD','USD'],['RMB','RMB'],['HKD','HKD'],['其他','其他']], 'default'=>'USD','required'=>false,),
            array('name'=>'update_time','label'=>'更新时间','type'=>"datetime","readonly"=>'true','default'=>time(),'null'=>false,'auto_update'=>true),
        ));
        $this->list_display=array(
            ['label'=>'投资主体ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'投资主体名称','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'源码主体类型','field'=>function($model){
                return $model->mTp;
            }],
            ['label'=>'源码主体是否co-investment','field'=>function($model){
                return $model->mCoInvestment;
            }],
            ['label'=>'源码主体资金货币','field'=>function($model){
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


