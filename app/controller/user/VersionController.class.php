<?php
class VersionController extends AppBaseController{
    public function __construct(){
        parent::__construct();
    }

    public static $ios_user_newest = '';
    public static $ios_buyer_newest = '';
    public static $android_user_newest = '';
    public static $android_buyer_newest = '';

    public function updateAppAction() {
        $os = $this->_GET('os');
        $version_id = $this->_GET('version_id');
        $client_type = $this->_GET('client_type');
        $tbl = [
            'ios'=>[
                'user'=>[
                    '2.3.0'=>[
                        'title'=>'发现新版本更新',
                        'content'=>'赶紧来更新新版增加了代金券功能哈哈',
                        'updateStatus'=>0,
                        'appUrl'=>'https://itunes.apple.com/us/app/tao-shi-jie/id905480305?l=zh&ls=1&mt=8',
                    ]
                ],
                'buyer'=>[
                ],
            ],
        ];
        if (empty($tbl[$os][$client_type][$version_id])) {
            $tbl[$os][$client_type][$version_id] = [
                'title'=>'',
                'content'=>'',
                'updateStatus'=>0,
                'appUrl'=>'',
            ];
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($tbl[$os][$client_type][$version_id],0)];
    }

    public function showadAction() {
        $os = $this->_GET('os');
        $version_id = $this->_GET('version_id');
        $client_type = $this->_GET('client_type');
        $tbl = [
            'ios'=>[
                '2.4.0'=>[
                    'url'=>'http://ai.m.taobao.com/?pid=mm_44633001_4196508_25590162&app_key=538edbef56240ba4b601e85c&device_id=78aa8301f17956edf3b5cba068cff685155653e4&aid=&mac=02:00:00:00:00:00&channel=App%20Store',
                ],
            ],
        ];
        if (empty($tbl[$os][$version_id])) {
            $tbl[$os][$version_id] = [
                'url'=>'',
            ];
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($tbl[$os][$version_id],0)];
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
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($tbl[$os],0)];
    }
}
