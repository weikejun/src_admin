<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-12-5
 * Time: 上午10:45
 */
class TradeRate extends Base_Trade_Rate{
    public function rate($orderInfo, $stockDescArray, $buyerDescArray, $score, $comment){
        if(empty($orderInfo)){
            return false;
        }else{
            if($orderInfo['status'] != "success"){
                return false;
            }else{
                $userId = $orderInfo['user_id'];
                $buyerId = $orderInfo['buyer_id'];
                $stockId = $orderInfo['stock_id'];
                $orderId = $orderInfo['id'];
                $this->setData([
                    'order_id' => $orderId,
                    'user_id' => $userId,
                    'stock_id' => $stockId,
                    'buyer_id' => $buyerId,
                    'score' => $score,
                    'stock_desc' => implode(",",$stockDescArray),
                    'buyer_desc' => implode(",",$buyerDescArray),
                    'comment' => $comment,
                    'status' => 1,
                    'create_time' => time(),
                    'update_time' => time(),
                ])->save();
                //向买手中回写描述tag
                $buyer = new Buyer();
                $buyerInfo = $buyer->getBuyerInfo($buyerId);
                foreach($buyerDescArray as $buyerDesc){
                    $buyerInfo['desc'][$buyerDesc] = empty($buyerInfo['desc'][$buyerDesc]) ? 1 : ($buyerInfo['desc'][$buyerDesc]+1);
                }
                $buyer->addWhere('id',$buyerId)->update([
                    'desc' => json_encode($buyerInfo['desc']),
                    'update_time'=>time()]);
                //向商品中回写描述tag
                $stock = new Stock();
                $stockInfo = $stock->getBaseInfoByStockId($stockId);
                $stockInfo['rate_tags']  = json_decode($stockInfo['rate_tags'],true);
                foreach($stockDescArray as $stockDesc){
                    $stockInfo['rate_tags'][$stockDesc] = empty($stockInfo['rate_tags'][$stockDesc]) ? 1 : ($stockInfo['rate_tags'][$stockDesc]+1);
                }

                //计算score
                // 平均评分= (平均评分*总计数+本次评分*10)/(总计数+1)
                $after_source = ($stockInfo['score'] * $stockInfo['rate_count'] + $score * 10)/($stockInfo['rate_count']+1);

                $stock->addWhere('id', $stockId)->update([
                    'rate_tags' => json_encode($stockInfo['rate_tags']),
                    'update_time' => time(),
                    'score' => floor($after_source),
                    'rate_count' => $stockInfo['rate_count']+1
                ]);
                return true;
            }
        }
    }

    /**
     * 能否评论
     * @param $orderInfoList
     * @return array
     */
    public function canComment($orderInfoList){
        $orderIdList = array_map(function($orderInfo){
            return $orderInfo['id'];
        },$orderInfoList);

        $rateList = $this->getListByOrderIdList($orderIdList);
        $ret = array();
        foreach($orderInfoList as $orderInfo){
            $ret[$orderInfo['id']] = ($rateList[$orderInfo['id']]|| ($orderInfo['status'] == 'success' && ($orderInfo['update_time'] + 30*86400 <= time())))? false:true;
        }
        return $ret;
    }

    /**
     * 根据订单id列表获取评价列表
     * @param $orderIdList
     * @return array
     */
    public function getListByOrderIdList($orderIdList){
        if(empty($orderIdList)){
            return null;
        }else{
            $tradeRateList = $this->addWhere('order_id',$orderIdList,'in')->find();
            $orderList = array();
            foreach($tradeRateList as $tradeRate){
                $tempData = $tradeRate->getData();
                $orderList[$tempData['order_id']] []= $tempData;
            }
            return $orderList;
        }
    }

}