<?php
class Logistic extends Base_Logistic{

    public static $KUAIDI100_KEY = 'rqfAVPxA9966';  //kuaidi100的KEY，保密

    //注册快递单号到快递100
    public function registerLogic($logistic_no, $logistic_provider, $to='') {
        $logistic_no = str_replace(' ', '', $logistic_no);
        if(empty($logistic_no)) return false;

        $config = array(
            'shunfeng' => 'shunfeng',
            'yuantong' => 'yuantong',
            'ems' => 'ems',
            'emsinten' => 'emsinten',
            'usps' => 'usps',
            'letseml' => 'letseml',
            'xlobo' => 'xlobo',
            'chronopostfra' => 'chronopostfra',
            'dhl' => 'dhl',
            'meiguokuaidi' => 'meiguokuaidi',
            'shentong' => 'shentong',
            //'' => '',
        );
        //不在推送范围内的快递单号，不会推送到kuaidi100
        if(!isset($config[$logistic_provider])) return false;

        $callback_url = 'http://api.taoshij.com/logistic/logisticUpdateCallback';
        $param = '{"company":"'.$logistic_provider.'", "number":"'.$logistic_no.'","from":"", "to":"'.$to.'", "key":"'.self::$KUAIDI100_KEY.'", "parameters":{"callbackurl":"'.$callback_url.'"}}';
        $params = array( 'schema'=>'json', 'param'=>$param );
        $url = "http://www.kuaidi100.com/poll";
        $result = self::sendHttpRequest($url, $params, 1);

        return isset($result['returnCode'])&&'200'==$result['returnCode'] ? true : false;
    }

    //发送http request请求
    //支持GET和POST类型
    //return array('code', 'msg')
    public static function sendHttpRequest($url, $params=array(), $post = 0) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        if($post) {
            $str = "";
            foreach ( $params as $k => $v ) {
                $str .= "$k=" . urlencode ( $v ) . "&";
            }
            $post_data = substr( $str, 0, - 1 );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        $result = curl_exec($ch);
        curl_close($ch);

        //var_dump($result); exit;
        $response = empty($result) ? array() : json_decode($result, true);

        return $response;
    }

    //获取修复之后的快递公司映射名
    //输入：快递公司名称
    //by boshen@20141210
     public static function getFixedProvider($logistic_provider = '') {
        $logistic_provider = str_replace(' ', '', $logistic_provider);
         if( false !== strpos($logistic_provider, '顺丰') ) return 'shunfeng';
         if( false !== strpos($logistic_provider, '圆通') ) return 'yuantong';

         return '';
     }

    //获取国际快递的公司映射名
    public static function getGlobalFixedProvider($logistic_provider = '') {
        $logistic_provider = str_replace(' ', '', $logistic_provider);
        if( false !== stripos($logistic_provider, '国际EMS') || false !== stripos($logistic_provider, 'ems国际') ) return 'emsinten';
        if( false !== stripos($logistic_provider, 'ems') ) return 'ems';
        if( false !== stripos($logistic_provider, 'usps') ) return 'usps';
        if( false !== strpos($logistic_provider, '美联速递') ) return 'letseml';
        if( false !== strpos($logistic_provider, '贝海物流') ) return 'xlobo';
        if( false !== stripos($logistic_provider, 'Laposte') ) return 'chronopostfra';
        if( false !== stripos($logistic_provider, 'dhl') ) return 'dhl';
        if( false !== strpos($logistic_provider, '美国快递') ) return 'meiguokuaidi';
        if( false !== strpos($logistic_provider, '圆通') ) return 'yuantong';
        if( false !== strpos($logistic_provider, '順丰') || false !== strpos($logistic_provider, '顺丰') ) return 'shunfeng';
        if( false !== strpos($logistic_provider, '申通') ) return 'shentong';

        return '';
    }

    /**
     * 根据orderId获取物流单号
     * @param $orderId
     * @return array
     */
    public function getLogisticOfOrderId($orderId){
        if(empty($orderId)){
            return null;
        } else{
            $ret = $this->addWhere('order_id',$orderId)->select();
            if(!empty($ret)){
                $ret = $ret->getData();
            }
            return $ret;
        }
    }

}
