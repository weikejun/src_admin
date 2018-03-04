<?php
class StockAmount extends Base_Stock_Amount{
    public static function releaseLockedAmount($order){
        $id = $order->mStockAmountId;
        $num = $order->mNum;
        $stock_amount_tbl=new DBTable('stock_amount');
        return $stock_amount_tbl->addWhere('id',$id)->update(['locked_amount'=>["`locked_amount`-$num",DBTable::NO_ESCAPE]]);
    }
    public static function releaseSoldAmount($order){
        $id = $order->mStockAmountId;
        $num = $order->mNum;
        $stock_amount_tbl=new DBTable('stock_amount');
        return $stock_amount_tbl->addWhere('id',$id)->addWhere('sold_amount',0,'>')->update(['sold_amount'=>["`sold_amount`-$num",DBTable::NO_ESCAPE]]);
    }

    public static function getStockAmountsInfo($stockAmountIds){
        $stockAmountIds=array_unique($stockAmountIds);
        $stockAmount=new StockAmount();
        $stockAmounts=$stockAmount->addWhere("id",$stockAmountIds,'in')->find();
        $stockIds=[];
        foreach($stockAmounts as $stockAmount){
            $stockIds[]=$stockAmount->mStockId;
            //$stockAmountsInfoMap[$stockAmount->mId]=['sku_value'=>$stockAmount->mSkuValue];
            //$stockAmountMap
        }
        $stockIds=array_unique($stockIds);
        $stock=new Stock();
        $stocks=$stock->addWhere("id",$stockIds,"in")->find();


        $stockAmountsInfoMap=[];
        foreach($stockAmounts as $stockAmount){
            $findStock=null;
            foreach($stocks as $stock){
                if($stock->mId==$stockAmount->mStockId){
                    $findStock=$stock;
                    break;
                }
            }
            if(!$findStock){
                Logger::error("no stock: {$stockAmount->mStockId}, sku: {$stockAmount->mSkuValue}");
            }
            $stockAmountsInfoMap[$stockAmount->mId]=[
                'sku_value'=>$stockAmount->mSkuValue,
                'name'=>$findStock->mName,
            ];
        }
        return $stockAmountsInfoMap;
        
    }

    /**
     * 根据stockId获取sku信息
     * @param $stockInfo
     * @return array
     */
    public function getSkuOfStockId($stockId){
        if(empty($stockId)){
            return array();
        }else{
            $stockInfo = (new Stock())->getBaseInfoByStockId($stockId);
            $skuMetaMapTmp = json_decode($stockInfo['sku_meta'], true);
            $skuMetaMap = [];
            $skuMetaOrder = 0;
            foreach($skuMetaMapTmp as $skuKey => $skuValues) {
                $skuMetaMap[$skuMetaOrder++] = array_flip($skuValues);
            }

            $amount = new self();
            $amount->setAutoClear(false);
            $amount = $amount->addWhere('stock_id', $stockId)->addWhere('valid', 'valid')->select();
            if($amount){
                $amountList = $amount->limit(0,$amount->count())->find();
                $skuArr = array();
                foreach($amountList as $key=>$value){
                    // 过滤无用的sku value
                    $skuAttrs = explode("\t", $value->mSkuValue);
                    // 过滤属性顺序与Meta顺序不同
                    $skuValid = true;
                    foreach($skuAttrs as $skuOrder => $skuAttr) {
                        if(!isset($skuMetaMap[$skuOrder][$skuAttr])) {
                            $skuValid = false;
                            break;
                        }
                    }
                    if(!$skuValid) {
                        continue;
                    }
                    $num = ($value->mAmount - $value->mLockedAmount - $value->mSoldAmount);
                    array_push($skuArr, array('id' => $value->mId, 'value' => $value->mSkuValue/*preg_replace('/ +/',"\t",$value->mSkuValue)*/, 'amount' => $num>0?$num:0));
                }
                # 库存状态信息
                return $skuArr;
            }else{
                return array();
            }
        }
    }

    /**
     * @param $stockAmountIdList
     * @return array
     */
    public function getStockAmountList($stockAmountIdList){
        if(empty($stockAmountIdList)){
            return [];
        }else{
            $stockAmountList = $this->addWhere('id',$stockAmountIdList,'in')->find();
            $stockAmountList = array_map(function($stockAmount){
                $data = $stockAmount->getData();
                $data['sku_value'] = explode("\t",$data['sku_value']);
                return $data;
            },$stockAmountList);
            return $stockAmountList;
        }
    }

    /**
     * 根据stockAmountId获取sku库存信息
     * @param $stockAmountId
     * @return array
     */
    public function getBaseInfoById($stockAmountId){
        if(empty($stockAmountId)){
            return null;
        }else{
            $ret = $this->addWhere('id',$stockAmountId)->select();
            if(!empty($ret)){
                $ret = $ret->getData();
                $ret['sku_value'] = explode("\t",$ret['sku_value']);
            }
            return $ret;
        }
    }
}
