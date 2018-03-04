<?php
/**
 * Created by PhpStorm.
 * User: boshen
 * Date: 14-12-9
 * Time: 下午2:47
 */

class LogisticTracking extends Base_Logistic_Tracking {

    //获取物流所有的跟踪信息 by boshen@20141209
    public static function getAllTrackings($logistic_no, $logistic_provider='shunfeng') {
        if(empty($logistic_no)) return array();

        $logistic_tracking = new LogisticTracking();
        $list = $logistic_tracking->addWhere('logistic_no', $logistic_no)->addWhere('logistic_provider', $logistic_provider)->orderBy('ftime', 'DESC')->limit(50)->find();
        $data = array_map( function($tracking) {
            $tracking = $tracking->getData();
            $tracking['show_time'] = date('Y-m-d H:i:s', $tracking['ftime']);
            return $tracking;
        }, $list);

        return $data;
    }

    //获取物流的最新一条记录 by boshen@20141209
    public static function getNewTracking($logistic_no, $logistic_provider='shunfeng') {
        if(empty($logistic_no)) return null;

        $logistic_tracking = new LogisticTracking();
        $tracking = $logistic_tracking->addWhere('logistic_no', $logistic_no)->addWhere('logistic_provider', $logistic_provider)->orderBy('ftime', 'DESC')->limit(1)->select();

        if( !empty($tracking) ) {
            $tracking = $tracking->getData();
            $tracking['show_time'] = date('Y-m-d H:i:s', $tracking['ftime']);
        }

        return $tracking;
    }

}
