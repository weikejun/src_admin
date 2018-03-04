<?php
/**
 */
$offset = 0;
$limit = 50;

/**
 *  买手等级 任务
 *
 */
$buyerModel = (new Buyer());
while ($res = DB::query("select id,level ,fans from buyer where status = 'be' and valid ='valid' limit $offset, $limit")) {
    $num = count($res);
    foreach ($res as $ret) {
        $buyerId = $ret['id'];
        $level = $ret['level'];
        $fans=$ret['fans'];

        //去商品总数
        $sql_count_stock=" select count(1) as cot from stock where buyer_id= $buyerId";
        $ret_count_stock = DB::query($sql_count_stock);
        $count_stock=$ret_count_stock[0]['cot'];

        //总成交金额
        $gmv_status="'wait_prepay','prepayed','wait_pay','payed','packed','wait_refund','refund','returned','fail','to_demostic','demostic','to_user','post_sale','success','canceled','timeout','full_refund'";
        $sql = "select sum(sum_price) as sum,count(sum_price) as cot  from `order` where buyer_id= $buyerId and status in ($gmv_status)";
        $sum_price = DB::query($sql);

        // 0.4*1000*成交单数+0.3*总成交额+0.2*100*商品总数+0.1*粉丝数*10 》 等级* 10000
        $gmv_count = 0.4 * 1000 * $sum_price['cot'];
        $gmv_price = 0.3 * $sum_price['sum'];
        $count_stock_score = 0.2 * 100 * $count_stock;
        $fans_score = 0.1 * 10 * $fans;
        echo "买手:" . $buyerId . "\n";
        //echo "订单数得分:".$gmv_count."\n";
        //echo "订单成交额得分:".$gmv_price."\n";
        //echo "商品量得分:".$count_stock_score."\n";
        //echo "粉丝得分:".$fans_score."\n";
        $levelup_score = $gmv_count + $gmv_price + $count_stock_score + $fans_score;
        echo "总得分:" . $levelup_score . "\n";

        if ($levelup_score > $level * 1000) {
            //level up
            $buyerModel->levelup($buyerId);
        }
    }
    $offset += $num;
}


