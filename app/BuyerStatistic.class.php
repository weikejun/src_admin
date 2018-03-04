<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-12-3
 * Time: 下午3:11
 */
class BuyerStatistic extends Base_Buyer_Statistic{
    /**
     * @param $buyerId
     * @param $stockStatistic
     * @return bool
     */
    public function setBuyerStatistic($buyerId,$stockStatistic){
        if(empty($buyerId) || empty($stockStatistic)){
            return false;
        }else{
            $buyer = $this->addWhere('buyer_id',$buyerId)->select();
            if(empty($buyer)){
                $res =$this->setData([
                    'buyer_id' =>$buyerId,
                    'stock_statistic'=>$stockStatistic,
                    'create_time'=>time(),
                    'update_time'=>time(),
                ])->save();
            }else{
                $res =$this->addWhere('buyer_id',$buyerId)->update([
                    'buyer_id'=>$buyerId,
                    'update_time'=>time(),
                    'stock_statistic'=>$stockStatistic,
                ]);
            }
            if(!empty($res)){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * 根据
     * @param $buyerId
     * @return array
     */
    public function getByBuyerId($buyerId){
        if(empty($buyerId)){
            return array();
        }else{
            $ret = $this->addWhere('buyer_id',$buyerId)->select();
            if(!empty($ret)){
                $ret = $ret->getData();
                $ret['stock_statistic'] = empty($ret['stock_statistic']) ? null:json_decode($ret['stock_statistic']);
            }
            return $ret;
        }
    }
}