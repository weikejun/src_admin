<?php
//多盟的数据统计和转化
//by boshen

ini_set('memory_limit', '328M');
set_time_limit(600);

$start_date = '2014-11-28';
$start_time = strtotime($start_date);

$content = "日期\t点击数\t激活数\t注册数\t激活转化率\t注册转化率\n";
while( $start_time < time() ) {
    $pr = new PromoteChannel();
    $end_time = $start_time + 86400;
    $list = $pr->addWhere('click_time', $start_time, '>=')->addWhere('click_time', $end_time, '<')->find();
    $click_num = $active_num = $register_num = 0;
    foreach( $list as $v ) {
        $click_num++;
        $v = $v->getData();
        if( $v['ping_time'] > 0 ) $active_num++;
        if( $v['active_time'] > 0 ) $register_num++;
    }
    unset($list);

    $content .= date('Y-m-d', $start_time)."\t".$click_num."\t".$active_num."\t".$register_num."\t".round($active_num*100/$click_num, 2)."\t".round($register_num*100/$active_num, 2)."\n";
    $start_time = $end_time;
}
echo $content;
