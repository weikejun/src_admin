<?php
class LiveFlowController extends Page_Admin_Base {
    use Page_Admin_InlineBase;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new LiveFlow();
        $this->model->orderBy("flow_time","desc");

        $this->form=new Form(array(
            array('name'=>'live_id','label'=>'直播ID','type'=>"text",'readonly'=>'true','default'=>$this->_GET('live_id', 0),'required'=>true,),
            array('name'=>'imgs','label'=>'图片','type'=>"simpleJsonFiles",'default'=>null,'required'=>false,),
            array('name'=>'content','label'=>'流内容','type'=>"textarea",'default'=>null,'required'=>true,),
            array('name'=>'flow_time','label'=>'流时间','type'=>"datetime",'default'=>null,'required'=>true,),
            array('name'=>'create_time','label'=>'创建时间','readonly'=>true,'type'=>"datetime",'default'=>null,'required'=>true,),
            array('name'=>'update_time','label'=>'更新时间','readonly'=>true,'type'=>"datetime",'default'=>null,'required'=>true,'auto_update'=>true),
            array('name'=>'status','label'=>'状态','type'=>"choice",'choices'=>[[0,'隐藏'],[1,'显示']],'default'=>1,'required'=>true,),
        ));
        $this->list_display=array(
            ['label'=>'流时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mFlowTime);
            }],
            ['label'=>'类型','field'=>function($model){
                return strtolower(get_class($model)) == 'stock' 
                    ? '商品<br />ID:'.$model->mId : '图文<br />ID:'.$model->mId;
            }],
            ['label'=>'状态','field'=>function($model){
                return strtolower(get_class($model)) == 'stock' 
                    ? '显示' : ($model->mStatus == 0 ? '隐藏' : '显示');
            }],
            ['label'=>'内容','field'=>function($model){
                $imgs = json_decode($model->mImgs, true);
                $retStr = '';
                for($i = 0; $i < count($imgs)&&$i < 2; $i++) {
                    $retStr .= '<img width=100 src="'.$imgs[$i].'" />';
                }
                return $retStr.'<br />'.(strtolower(get_class($model)) == 'stock' 
                    ? '商品名：'.$model->mName.'<br />价格：'.$model->mPriceout
                    : $model->mContent);
            }],
        );
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'直播ID','paramName'=>'live_id','fusion'=>false]),
        );
    }

    public function flowAction() {
        $stocks= new Stock;
        $stocks = $stocks->addWhere('live_id', $this->_GET('live_id', 0))->find();
        $model=$this->model;
        $model->setAutoClear(false);
        $model->addWhere('live_id', $this->_GET('live_id', 0));
        if($this->list_filter){
            foreach($this->list_filter as $filter){
                $filter->setFilter($model);
            }
        }
        $flows=$model->find();
        $model->setAutoClear(true);
        $modelDataList = [];
        foreach($stocks as $stock) {
            $modelDataList[$stock->mFlowTime.sprintf("0%08d", $stock->mId)] = $stock;
        }
        foreach($flows as $flow) {
            $modelDataList[$flow->mFlowTime.sprintf("1%08d", $flow->mId)] = $flow;
        }
        krsort($modelDataList);
        $modelDataList = array_values($modelDataList);

        return array('admin/live_flow/flow.html', array(
            '__success_url' => $_SERVER['REQUEST_URI'],
            '__is_new' => true,
            'form' => $this->form,
            'pageAdmin' => $this,
            'modelDataList' => $modelDataList,
            '_page' => $page,
            '_pageSize' => self::$PAGE_SIZE,
            '_startIndex' => $page*self::$PAGE_SIZE,
            '_allCount' => $model->count()
        ));
    } 
    public function create(){
        $__success_url=$this->_REQUEST('__success_url');
        if(empty($__success_url))
            $__success_url = Utils::get_default_back_url();
        $this->assign("__success_url",$__success_url);
        $result=$this->_create();
        if($result){
            $this->back("插入成功",$__success_url);
        }else{
            $this->display("admin/live_flow/flow.html");
        }
    }
}

