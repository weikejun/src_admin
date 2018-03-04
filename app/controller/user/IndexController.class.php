<?php

class IndexController extends AppBaseController{
    public function __construct(){
        // 不需要权限控制
        #$this->addInterceptor(new LoginInterceptor());        
    }
    public function newAction(){
        $new=new IndexNew();
        $news=$new->addWhere("valid","valid")->orderBy("order","desc")->orderBy("update_time","desc")->addWhere('channel',1)->limit(0,10)->find();
        $news=array_map(function($new){
            $data=$new->getData();
            $data['imgs']=json_decode($data['imgs'],true);
            return $data;
        },$news);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["news"=>$news])];
    }

}
