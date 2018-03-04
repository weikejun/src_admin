<?php
/**
 * Created by PhpStorm.
 * aimde: 解决买手推荐排序，商品统计的问题
 * User: dingping
 * Date: 14-12-2
 * Time: 上午11:58
 */
$offset = 0;
$limit = 50;

/**
 * 解决买手的热买商品统计数量问题
 */
$buyerStaticModel = (new BuyerStatistic());
while($res = DB::query("select id from buyer where status = 'be' and valid ='valid' limit $offset, $limit")){
    $num = count($res);
    foreach($res as $ret){
        $buyerId = $ret['id'];
        $sql = "select stock_id,count(stock_id) count from `order` where buyer_id = $buyerId group by stock_id order by count(stock_id) desc limit 5";
        $stockInfo = DB::query($sql);
        $stockArray = array();
        foreach($stockInfo as $item){
            $stockArray[$item['stock_id']] = $item['count'];
        }
        $stockStatistic = json_encode($stockArray);
        $buyerStaticModel->setBuyerStatistic($buyerId,$stockStatistic);
    }
    $offset += $num;
}


$offset = 0;
$limit = 50;
/**
 * 解决买手推荐排序问题
 */
$buyerRankModel = new BuyerRank();
while($res = DB::query("select max(update_time) update_time,buyer_id from stock group by buyer_id limit $offset,$limit ")){
    $num = count($res);
    foreach($res as $ret){
        $buyerRankModel->setLatestBuyer($ret['buyer_id'],$ret['update_time']);
        $buyerRankModel->clear();
    }
    $offset += $num;
}
