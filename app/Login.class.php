<?php

class Login {


    public static function getWeixinAccesstokenByCode($code){
        require_once(ROOT_PATH."/lib/_wxpay/tenpay_config.php");
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token";
        $params = array();
        $params['appid'] = $APP_ID;
        $params['secret'] = $APP_SECRET;
        $params['code'] = $code;
        $params['grant_type'] = 'authorization_code';
        $url .= '?'.http_build_query($params);
        $html = self::get_url($url);
        $arr = json_decode($html, true);
        return $arr;
    }

    public static function getWeixinUserInfo($access_token, $openid){
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $params = array();
        $params['access_token'] = $access_token;
        $params['openid'] = $openid;
        $url .= '?'.http_build_query($params);
        $html = self::get_url($url);
        $arr = json_decode($html, true);
        return $arr;
    }

    public static function get_url($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $html = curl_exec($ch);
        curl_close($ch);
        
        return $html;
    }

    public static function getWeiboUserInfo($token, $uid) {
        $c = new SaeTClientV2(WB_AKEY, WB_SKEY, $token);
        return $c->show_user_by_id($uid);
    }

}
