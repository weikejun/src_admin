<?php
class TradeCart extends Base_Trade_Cart {

    private static $stockInfo;
    private static $buyerInfo;

    //列表页
    public static function getListByUid($uid) {
        $cart = new self();
        $output = $tmp = [];
        $list = $cart->addWhere("user_id", $uid)->find();
        foreach ($list as $obj) {
            $skuData = $obj->getData();
            if (!isset(self::$stockInfo[$obj->mStockId])) {
                $stock = new Stock();
                $stock = $stock->addWhere("id", $obj->mStockId)->select();
                if (!$stock) {
                    self::$stockInfo[$obj->mStockId] = null;
                } else {
                    $data = $stock->getData();
                    if (isset($data['imgs'])) $data['imgs'] = json_decode($data['imgs']);
                    if ($data['imgs']) {
                        $data['imgs_meta']=array_map(function($file){
                            return ImageMagick::size($file);
                        },$data['imgs']);
                    }
                    $stock_info['name'] = $data['name'];
                    $stock_info['imgs'] = $data['imgs'];
                    $stock_info['priceout'] = $data['priceout'];
                    $stock_info['onshelf'] = $data['onshelf'];
                    self::$stockInfo[$obj->mStockId] = $stock_info;
                } 
            }
            if (!isset(self::$buyerInfo[$obj->mBuyerId])) {
                $buyer = new Buyer();
                $buyer = $buyer->getBuyerInfo($obj->mBuyerId);
                $buyerInfo['id'] = $buyer['id'];
                $buyerInfo['name'] = $buyer['name'];
                $buyerInfo['head'] = $buyer['head'];
                $buyerInfo['level'] = $buyer['level'];
                $buyerInfo['easemob_username'] = $buyer['easemob_username'];
                self::$buyerInfo[$obj->mBuyerId] = $buyerInfo;
            }
            $skuData['stock_info'] = self::$stockInfo[$obj->mStockId];

            $stock_amount = new StockAmount();
            $stock_amount = $stock_amount->addWhere('id', $obj->mStockAmountId)->select();
            $skuData['sku_value'] = $stock_amount->mSkuValue;
            $skuData['is_closed'] = 0;
            $skuData['close_reason'] = '';
            if (empty(self::$stockInfo[$obj->mStockId])) {
                $skuData['close_reason'] = '已删除';
                $skuData['is_closed'] = 1;
            } elseif (self::$stockInfo[$obj->mStockId]['onshelf']==0) {
                $skuData['close_reason'] = '已下架';
                $skuData['is_closed'] = 1;
            } elseif (($stock_amount->mLockedAmount+$stock_amount->mSoldAmount)>=$stock_amount->mAmount) {
                $skuData['close_reason'] = '已售罄';
                $skuData['is_closed'] = 1;
            }
            $tmp[$obj->mBuyerId]['buyer_info'] = self::$buyerInfo[$obj->mBuyerId];
            $tmp[$obj->mBuyerId]['sku_info'][] = $skuData;
        }
        foreach($tmp as $v){
            $output[] = $v;
        }
        return $output;
    }

    //添加sku到购物车
    public static function addCart($param) {
        if (empty($param['user_id']) || empty($param['stock_id']) || empty($param['stock_amount_id']) || empty($param['num'])) {
            return false;
        }
        $time = time();
        $cart = new self();
        $ret = $cart->addWhere("user_id", $param['user_id'])->addWhere("stock_amount_id", $param['stock_amount_id'])->select();
        if (!$ret) {
            $cart->mUserId = $param['user_id'];
            $cart->mStockId = $param['stock_id'];
            $cart->mStockAmountId = $param['stock_amount_id'];
            $cart->mNumber = !empty($param['num']) ? $param['num'] : 1;
            $cart->mBuyerId = !empty($param['buyer_id'])?$param['buyer_id']:1;
            $cart->mLiveId = !empty($param['live_id'])?$param['live_id']:0;
            $cart->mCreateTime = $time;
            $cart->mUpdateTime = $time;
            $cart->mSource = !empty($param['source'])?$param['source']:'';
            $cart->save();
        } else {
            $cart->mNumber += $param['num'];
            $cart->mUpdateTime = $time;
            $cart->save();
        }
        return true;
    }

    public static function delCart($param) {
        $cart = new self();
        $cart = $cart->addWhere("id", $param['id'])->addWhere("user_id",User::getCurrentUser()->mId)->select();
        if ($cart) $cart->delete();
        return true;
    }
}
