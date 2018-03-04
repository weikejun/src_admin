<?php

function orderStatistics($time_start,$time_end){
    $statistics=[];
    $order=new Order();
    $order->setAutoClear(true);
    $statistics['总订单数']=$order->addWhere("create_time",$time_start,">")->addWhere("create_time",$time_end,"<")->count();
    $statistics['有效订单数']=$order->addWhere("status","canceled","!=")->addWhere("create_time",$time_start,">")->addWhere("create_time",$time_end,"<")->count();

    $valid_orders=$order->addWhere("status","canceled","!=")->addWhere("create_time",$time_start,">")->addWhere("create_time",$time_end,"<")->find();
    $valid_orders_price=0;
    foreach ($valid_orders as $valid_order){
        $valid_orders_price+=$valid_order->mSumPrice;
    }
    $statistics['有效订单金额']=$valid_orders_price;

    $live=new Live();
    $statistics['时间段内开始的直播数']=$live->addWhere("status","verified")->addWhere("start_time",$time_start,">")->addWhere('start_time',$time_end,"<")->count();
    $lives=$live->addWhereRaw("status='verified' and ((start_time>$time_start and start_time<$time_end) or (end_time>$time_start and end_time<$time_end))")->find();

    $statistics['直播数'] =count($lives);

    $statistics['每场直播销售金额（均值）']=$valid_orders_price/count($lives);

    $lives_sale=[];
    foreach($lives as $live){
        $lives_sale[$live->mId]=0;
        foreach($order->addWhere("live_id",$live->mId)->addWhere("status","canceled","!=")->addWhere("create_time",$time_start,">")->addWhere("create_time",$time_end,"<")->find() as $_order){
            $lives_sale[$live->mId]+=$_order->mSumPrice;
        }
    }
    $statistics['单场直播最高销售额']=max(array_values($lives_sale));
    $statistics['单场直播最低销售额']=min(array_values($lives_sale));


    $statistics['购买去重用户']=count($order->addWhere("status","canceled","!=")->addWhere("create_time",$time_start,">")->addWhere("create_time",$time_end,"<")->groupBy("user_id")->find());
    $statistics['人均购买金额']=$valid_orders_price/$statistics['购买去重用户'];
    $statistics['订单均值']=$valid_orders_price/$statistics['有效订单数'];

    $smarty=DefaultViewSetting::getTemplateWithSettings();
    $smarty->assign("statistics",$statistics);
    $smarty->assign("lives_sale",$lives_sale);
    $smarty->assign("lives",$lives);
    $email_content=$smarty->fetch("email/order.tpl");

    EMail::send([
        'title'=>"订单统计 ".date("Y-m-d",$time_start)." - ".date("Y-m-d",$time_end),
        'content'=> $email_content,
        //'cc'=>"wangpeng@aimeizhuyi.com",
        //'to'=>"wangpeng@aimeizhuyi.com",
        'to'=>["lengqiying@aimeizhuyi.com","chendandan@aimeizhuyi.com","weikejun@aimeizhuyi.com","huangying@aimeizhuyi.com","gaominghui@aimeizhuyi.com","huangjianming@aimeizhuyi.com","liujingjing@aimeizhuyi.com"],
        //'cc'=>['lengqiying@aimeizhuyi.com','linlin@aimeizhuyi.com'],
    ]);
}
$time_start=mktime(0,0,0,date("n"),date('j')-1,date('Y'));
$time_end=mktime(0,0,0,date("n"),date('j'),date('Y'));
orderStatistics($time_start,$time_end);
if(date('j')==1){
    $time_start=mktime(0,0,0,date("n")-1,date('j'),date('Y'));
    orderStatistics($time_start,$time_end);
}
if(date('w')==1||date('w')==5){
    $time_start=mktime(0,0,0,date("n"),date('j')-7,date('Y'));
    orderStatistics($time_start,$time_end);
}
