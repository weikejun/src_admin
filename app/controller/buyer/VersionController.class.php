<?php
class VersionController extends AppBaseController{
    public function __construct(){
        parent::__construct();
    }
    public function latestAction(){
        static $tbl=[
            'ios'=>[
                'ver'=>'1.0.0',
                'url'=>'http://www.wangp.org',
                'readme'=>'欢迎使用最新的1.0.0版',
            ],
            'android'=>[
                'ver'=>'0.0.1',
                'url'=>'http://www.wangp.org',
                'readme'=>'欢迎使用最新的0.0.1版',
            ],
        ];
        $os=$this->_GET('os','ios');
        if(!isset($tbl[$os])){
            $os='ios';
        }
        return ['json:',AppUtils::returnValue($tbl[$os],0)];
    }
}
