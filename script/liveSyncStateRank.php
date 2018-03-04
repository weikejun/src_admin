<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-12-31
 * Time: 下午2:58
 */
$time = time();
//结束的直播的商品都要默认下架
$lives=(new Live())->addWhere('valid','valid')->addWhere("end_time",$time,'<')->find();
foreach($lives as $live){
    $stockList = (new LiveStock())->getStockListOfLiveId($live->mId);
    $stockIdList = array_filter(array_map(function($stockData){
        return $stockData['stock_id'];
    },$stockList));
    (new Stock())->offShelf($stockIdList);
}

//正在直播与已经通过在预告的直播的所有商品默认更新到买手的商品推荐内.
$lives=(new Live())->addWhere('valid','valid')->addWhere("end_time",$time,'>=')->find();
foreach($lives as $live){
    $stockList = (new LiveStock())->getStockListOfLiveId($live->mId);
    $stockIdList = array_filter(array_map(function($stockData){
        if($stockData['stock_type'] == 1){
            return $stockData['stock_id'];
        }
    },$stockList));
    $stockList = (new Stock())->genStockDetailOfStockList(array_values($stockIdList));
    (new StateRank())->Stock2Rank(array_values($stockList));
}
