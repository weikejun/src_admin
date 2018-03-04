<?php
class TradeCartController extends AppBaseController {

    public function __construct() {
       $this->addInterceptor(new LoginInterceptor());
    }

    //购物车列表页
    public function listAction() {
        $user_id = User::getCurrentUser()->mId;
        $list = TradeCart::getListByUid($user_id);
        $list = empty($list) ? null : $list;
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($list)];
    }

    //添加商品到购物车
    public function addAction() {
        $stock_amount_id = $this->_POST("stock_amount_id",'','20014');
        $num = intval($this->_POST('num', 1));

        $stock_amount = new StockAmount();
        $stock_amount = $stock_amount->addWhere('id', $stock_amount_id)->select();
        if (!$stock_amount) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'stock amount error'],'20014')];
        }
        $stock = new Stock();
        $stock = $stock->addWhere('id', $stock_amount->mStockId)->select();
        if (!$stock) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'stock error'],'20015')];
        }

        $cart = new TradeCart();
        $ret = $cart->addWhere("user_id", $param['user_id'])->addWhere("stock_amount_id", $param['stock_amount_id'])->select();

        $param['user_id'] = User::getCurrentUser()->mId;
        $param['stock_id'] = $stock_amount->mStockId;
        $param['stock_amount_id'] = $stock_amount_id;
        $param['num'] = $num;
        $param['buyer_id'] = $stock->mBuyerId;
        $param['live_id'] = $stock->mLiveId;
        $param['source'] = 0;
        TradeCart::addCart($param);
        return ['json:',AppUtils::returnValue(['msg'=>'成功添加到购物车'])];
    }

    //删除购物车的东西
    public function delAction() {
        $tradeCartId = $this->_POST("tradeCartId",'','20014');
        $param['id'] = $tradeCartId;
        TradeCart::delCart($param);
        return ['json:',AppUtils::returnValue(['msg'=>'删除成功'])];
    }
}
