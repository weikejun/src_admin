<?php

class IndexController extends BaseController{
    public function indexAction(){
        return ["index.tpl",['aa'=>'b']];
    }

    //获取开机启动图片地址
    //by boshen@20141216
    public function startUpImageAction() {
        //$pic_url = '/public_upload/4/4/f/44f6882d515ed5bb3978ddbcadd15577.jpg';
        $pic_url = '/public_upload/3/4/1/341c35331da7a396d9420bc37457d0a1.jpg';
        $data = array('picUrl'=>$pic_url);
        return ['json:', AppUtils::returnValue($data)];
    }
}
