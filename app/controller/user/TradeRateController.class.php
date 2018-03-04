<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-12-5
 * Time: 下午2:12
 */
class TradeRateController extends AppBaseController{
    public function __construct(){
        # 没必要登陆限制
        $this->addInterceptor(new LoginInterceptor());
    }

    /**
     * 提交交易评价
     */
    public function commitAction(){
        $orderId = $this->_GET("order_id");
        $comment = $this->_GET("comment");
        $score = $this->_GET("score");
        $stockDesc = $this->_GET("stock_desc");
        $buyerDesc = $this->_GET("buyer_desc");

        $stockDesc = explode(',' , $stockDesc);
        $buyerDesc = explode(',' , $buyerDesc);

        $orderInfo = (new Order())->getOrderInfoByOrderId($orderId);
        if(empty($orderInfo)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(
                ['msg' => "未找到这个订单信息"],10001
            )];
        }else{
            $curUserId = User::getCurrentUser()->mId;
            if( $curUserId != $orderInfo['user_id']){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(
                    ['msg' => "您不能评价别人的订单"],10001
                )];
            }else{
                $ret = (new TradeRate())->rate($orderInfo,$stockDesc,$buyerDesc,$score,$comment);
                if(empty($ret)){
                    return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(
                        ['msg' => "提交评价失败"],10001
                    )];
                }else{
                    return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(
                        ['msg' => "提交评价成功"])];
                }
            }
        }
    }

    /**
     * 交易评价详情页
     */
    public function detailAction(){
        $orderId = $this->_GET('order_id');
        if(empty($orderId)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(
                ['msg' => "请输入订单id"])];
        }else{
            $orderInfo = (new Order())->getOrderInfoByOrderId($orderId);
            $stockInfo = (new Stock())->getStockInfoByStockId($orderInfo['stock_id']);
            $stockTagList = Stock::getStockTagList();
            $buyerTagList = Buyer::getBuyerTagList();
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(
                [
                    "stockInfo" => $stockInfo,
                    "stockTagList" => $stockTagList,
                    "buyerTagList" => $buyerTagList,
                ])];
        }
    }
}
