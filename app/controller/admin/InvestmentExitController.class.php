<?php
class InvestmentExitController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        WinRequest::mergeModel(array(
            'controllerText'=>"退出记录",
        ));
        $this->model=new Model_InvestmentExit();
        $this->model->orderBy('exit_time', 'DESC');
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'project_id','label'=>'投资项目','type'=>"choosemodel",'model'=>'Model_Project','default'=>null,'required'=>true,),
            array('name'=>'amount','label'=>'金额','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'currency','label'=>'计价货币','type'=>"choice",'choices'=>[['USD','USD'],['RMB','RMB'],['HKD','HKD']], 'default'=>'USD','required'=>true,),
            array('name'=>'exit_way','label'=>'退出方式','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'stock_num','label'=>'退出股数','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'exit_rate','label'=>'退出比例','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'rest_rate','label'=>'退后剩余比例','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'return_rate','label'=>'退出回报率','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'exit_time','label'=>'退出日期','type'=>"date",'default'=>null,'null'=>true,),
            array('name'=>'memo','label'=>'退出备注','type'=>"text", 'default'=>null,'required'=>false,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"hidden","readonly"=>'true','default'=>time(),'null'=>false,),
        ));
        $this->list_display=array(
            ['label'=>'id','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'项目名称','field'=>function($model){
                $project = $this->_getResource($model->mProjectId, 'Project', new Model_Project, 'id');
                return $project->mName;
            }],
            ['label'=>'金额','field'=>function($model){
                return $model->mAmount . ' ' . $model->mCurrency;
            }],
            ['label'=>'退出轮次','field'=>function($model){
                $project = $this->_getResource($model->mProjectId, 'Project', new Model_Project, 'id');
                return $project->mTurn;
            }],
            ['label'=>'退出方式','field'=>function($model){
                return $model->mExitWay;
            }],
            ['label'=>'退出股数','field'=>function($model){
                return $model->mStockNum;
            }],
            ['label'=>'退出比例','field'=>function($model){
                return $model->mExitRate;
            }],
            ['label'=>'退后剩余比例','field'=>function($model){
                return $model->mRestRate;
            }],
            ['label'=>'退出回报率','field'=>function($model){
                return $model->mReturnRate;
            }],
            ['label'=>'退出日期','field'=>function($model){
                return date('Y-m-d H:i:s',$model->mExitTime);
            }],
            ['label'=>'退出备注','field'=>function($model){
                return $model->mMemo;
            }],
        );

        $this->single_actions=[
            ['label'=>'投资记录','action'=>function($model){
                return '/admin/project?__filter='.urlencode('project_id='.$model->mProjectId);
            }],
        ];

        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'项目ID','paramName'=>'project_id','fusion'=>false]),
        );
    }
}


