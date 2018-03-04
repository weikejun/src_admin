<?php
class ExtController extends AppBaseController{
    public function __construct(){
        parent::__construct();
    }

    public function setChannelAction(){
        $channel = $this->_POST('channel', 'sitein');
        setcookie('__channel', $channel, time()+86400*365*10, '/');
        $_COOKIE['__channel'] = $channel;
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],0)];
    }

    public function promoteAction() {
        $source = $this->_GET('source');
        if(strtolower($source) == 'domob') {
            $pr = new PromoteChannel;
            $pr->setData([
                'ifa' => $this->_GET('ifa'),
                'mac' => $this->_GET('mac'),
                'oid' => $this->_GET('oid'),
                'udid' => $this->_GET('udid'),
                'appid' => $this->_GET('appId'),
                'click_time' => time(),
                'click_ip' => Utils::getClientIP(),
                'source' => $source,
            ]);
            $pr->save();
        }
        return ['redirect: https://itunes.apple.com/us/app/tao-shi-jie/id905480305?l=zh&ls=1&mt=8'];
    }

    public function pingAction() {
        $idfa = $this->_POST('idfa');
        $appid = '905480305';
        // for domob
        $_SESSION['promote_idfa'] = $idfa;
        $pr = new PromoteChannel;
        $pr = $pr->addWhere('ifa', $idfa)->addWhere('appid', $appid)->addWhere('ping_time', null, 'is')->update(['ping_time'=>time()]);
        //$this->_domobResponse($idfa);
        
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'ok'],0)];
    }

}
