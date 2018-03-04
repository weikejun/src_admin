<?php
class NationalFlag{
    public static  function getUrl($country){
        $country = trim($country);
        $transMap = array(
            'niederlands' => '荷兰',
            'italia' => '意大利',
            'italy' => '意大利',
            'us' => '美国',
            'usa' => '美国',
            'united states' => '美国',
            'canada' => '加拿大',
            'new zealand' => '新西兰',
            'france' => '法国',
            '中国台湾' => '中国',
            'uk' => '英国',
        );
        if(isset($transMap[strtolower($country)])) {
            $country = $transMap[trim(strtolower($country))];
        }
        if(in_array($country,[
            '澳大利亚',
            '法国',
            '加拿大',
            '日本',
            '新西兰',
            '英国',
            '德国',
            '韩国',
            '荷兰',
            '美国',
            '香港',
            '泰国',
            '新加坡',
            '西班牙',
            '葡萄牙',
            '土耳其',
            '马来西亚',
            '中国',
            '尼泊尔',
            '意大利'])){
            return "/flag/".urlencode($country).".png";
        }
        return "/flag/blank.gif";
    }
}
