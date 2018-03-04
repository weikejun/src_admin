<?php

class StockController extends AppBaseController{
    public function __construct(){
        # 没必要登陆限制
        $this->addInterceptor(new LoginInterceptor());
    }
    public function showAction(){
        $stock=new Stock();
        $stock->setAutoClear(false);
        $id=$this->_GET('id',"");
        $stock=$stock->addWhere('id',$id)->addWhere('valid','valid')->select();
        if(!$stock){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'no this stock'],99999)];
        }
        $live=new Live();
        $live=$live->addWhere('id',$stock->mLiveId)->select();
        if(!$live){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'live does not exist'],50023)];
        }
        $endtime = $live->mEndTime;
        $is_close = 1;
        if($endtime && time()<$endtime){
            $is_close=0;
        }

        $allCount=$stock->count();
        $skuMetaMapTmp = json_decode($stock->mSkuMeta, true);
        $skuMetaMap = [];
        $skuMetaOrder = 0;
        foreach($skuMetaMapTmp as $skuKey => $skuValues) {
            $skuMetaMap[$skuMetaOrder++] = array_flip($skuValues);
        }
        $data = $stock->getData();
        $data['left_time'] = $is_close ? 0 : ($endtime - time());
        // gen sku
        $amount = new StockAmount();
        $amount->setAutoClear(false);
        $amount = $amount->addWhere('stock_id', $id)->addWhere('valid', 'valid')->select();
        if($amount){
            $amountList = $amount->limit(0,$amount->count())->find();
            $skuArr = array();
            foreach($amountList as $key=>$value){
                // 过滤无用的sku value
                $skuAttrs = explode("\t", $value->mSkuValue);
                // 过滤属性数量与Meta数量不同
                if(count($skuAttrs) != count($skuMetaMap)) {
                    continue;
                }
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
                array_push($skuArr, array('id' => $value->mId, 'value' => $value->mSkuValue/*preg_replace('/ +/',"\t",$value->mSkuValue)*/, 'amount' => ($value->mAmount - $value->mLockedAmount - $value->mSoldAmount)));
            }
            # 库存状态信息
            $data['sku'] = $skuArr;
        }
        # string to json
        if(isset($data['imgs'])) $data['imgs'] = json_decode($data['imgs']);
        if($data['imgs']){
            $data['imgs_meta']=array_map(function($file){
                return ImageMagick::size($file);
            },$data['imgs']);
        }
        if(isset($data['sku_meta'])) {
            $meta = json_decode($data['sku_meta']);
            $meta_arr = [];
            $i = 0;
            foreach($meta as $key=>$val){
                $meta_arr[$i++] = ['meta'=>$key, 'value'=>$val];
            }
            $data['sku_meta'] = $meta_arr;
        }
        // 统一进行反序列化，避免和客户端做过多的约定
        #$data['priceout_unit_show'] = isset($data['priceout_unit']) ? TableUtils::getUnitShow($data['priceout_unit']) : '';
        $data['pay_unit']=$stock->mPriceoutUnit;
        $data['pay_show']=TableUtils::getUnitShow($stock->mPriceoutUnit);
        $data['prepay'] = GlobalMethod::countPrepay($stock->mPriceout,GlobalMethod::ALL_PAY_SWITCH);
        $buyer = new Buyer;
        $buyer = $buyer->addWhere('id', $stock->mBuyerId)->select();
        $data['buyer_name'] = $buyer ? $buyer->mName : '未知';
        $orderNum = Order::genOrderNum([$stock->mId]);
        $data['order_num'] = $orderNum[$stock->mId] ? $orderNum[$stock->mId] : 0;
        # 限购数量
        $data['limit_num'] = 1;
        // 分享运营标题
        $data['share_title'] = array(
            'wechat' => '首付'.$data['prepay'].'就能带回家',
            'wechat_moments' => '首付'.$data['prepay'].'就能带回家',
            'qzone' => '首付'.$data['prepay'].'就能带回家',
            'weibo' => '现在就去海外血拼，首付'.$data['prepay'].'就能带回家',
        );
        $data['is_close']=$is_close;
        $data['is_open']= (time() < $live->mStartTime ? 0 : 1);

        // 获取买手信息
        $buyerInfo=Buyer::getBuyerInfo($data['buyer_id']);
        $data['buyer_info'] = $buyerInfo;
        if ($this->_GET('os') == "android") {
            $data = ['stock'=>$data];
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($data)];
    }

    /**
     * 新版本的商品详情页
     */
    public function detailAction(){
        $stockId=$this->_GET('stock_id',"");
        $stockDetail = (new Stock())->getStockInfoByStockId($stockId);
        if(empty($stockDetail)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'商品未通过审核或者不存在'],21003)];
        }else{
            $curUserId = (new User())->getCurrentUser()->mId;

            $buyerInfo = (new Buyer())->getBuyerInfo($stockDetail['buyer_id']);
            $orderNum = Order::genOrderNum([$stockDetail['id']]);
            $data['order_num'] = $orderNum[$stockDetail['id']] ? $orderNum[$stockDetail['id']] : 0;
            $sku = (new StockAmount())->getSkuOfStockId($stockId);
            $stockDetail['sku'] = $sku;
            $stockDetail['buyer_name'] =  $buyerInfo ? $buyerInfo['name'] : '未知';

            $likeList  = (new Favor())->getStockLikeList($stockDetail['id'],0,7);
            $commentList = (new Comment())->getCommentListOfStock($stockDetail['id'],0,3);

            $liked = false;
            if( !empty($curUserId) ){
                $liked = (new Favor())->isLiked($curUserId,$stockId,Favor::STOCK);
                foreach($likeList as $key  => $favorUser){
                    //如果买家在喜欢列表内，则换到第一个位置
                    if( $favorUser['id'] == $curUserId){
                        $tmp = $likeList[0];
                        $likeList[0] = $favorUser;
                        $likeList[$key] = $tmp;
                        break;
                    }
                }
            }

            //标记用户是否喜欢 todo:这里我坑爹了，返回的rst外面一个liked，stockDetail里面还有一个followed，兼容闻竹的ios数据结构，忍忍。
            $stockDetail['followed'] = $liked;

            $stockStatic = (new BuyerStatistic())->getByBuyerId($buyerInfo['id']);
            $stockIdList = array();
            $num = 0;
            //对推荐商品进行排序
            foreach($stockStatic['stock_statistic'] as $Id=>$count){
                if($Id != $stockId ){
                    $stockIdList []= $Id;
                    if($num++ > 2){break;}
                }
            }

            $stockList = (new Stock())->genStockDetailOfStockList($stockIdList);
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "stockDetail" => $stockDetail,
                "like" => array(
                    "liked" => $stockDetail['liked'],
                    "likeList" => $likeList,
                ),
                "liked" => $liked,
                "commentList" => array(
                    "commented"=> $stockDetail['commented'],
                    "commentList" => array_values($commentList),
                ),
                "buyerInfo" => $buyerInfo,
                "recommendStockList" => array_values($stockList),
            ])];
        }
    }



    /**
     * 点赞成功
     * @return array
     */
    public function likeAction(){
        $stockId = $this->_GET('stock_id');
        $favor = new Favor();

        $curUserId = (new User())->getCurrentUser()->mId;
        $stockInfo = (new Stock())->getStockInfoByStockId($stockId);
        $buyerId = $stockInfo['buyer_id'];

        if($favor->isLiked($curUserId, $stockId, 1)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                'msg' => '已经点过赞咯~'
            ],20017)];
        }else{
            if($favor->likeStock($curUserId, $stockId, $buyerId)){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    'msg' => '亲，喜欢就放肆，但爱是克制，你点赞了就要记得把我买回家~'
                ])];
            }else{
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    'msg' => '关注失败,请重试'
                ],20018)];
            }
        }
    }

    /**
     * 取消点赞
     * @return array
     */
    public function unlikeAction(){
        $stockId = $this->_GET('stock_id');
        $favor = new Favor();

        $curUserId = (new User())->getCurrentUser()->mId;

        if($favor->isLiked($curUserId, $stockId,1)){
            if($favor->unlikeStock($curUserId, $stockId)){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    'msg' => '亲，你不喜欢我哦~'
                ])];
            }else{
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    'msg' => '网络不给力，取消点赞失败哦~'
                ],20019)];
            }
        }else{
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                'msg' => '亲，你还没点赞呢~'
            ],20020)];
        }
    }

    /**
     * 给状态提交评论
     */
    public function commitAction(){
        $commentType = $this->_POST('comment_type','1');
        $replyId = $this->_POST('reply_id');
        $comment = $this->_POST('comment');
        $stockId = $this->_POST('stock_id');

        $ciUserId = (new User())->getCurrentUser()->mId;
        $ciUserType = 1;

        $commentModel = new Comment();
        $ret = $commentModel->commit2Stock($ciUserId,$ciUserType,$commentType,$replyId,$comment,$stockId);
        if(!empty($ret)){
            Comment::notify($ret);
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
            'msg' => '提交评论成功'])];
    }

    /**
     * 商品的评价列表
     * @return array
     */
    public function commentListAction(){
        $stockId = $this->_GET('stock_id');
        $pageId = $this->_GET('pageId',1);
        $count = $this->_GET('count',10);
        if(empty($stockId)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'无商品'],10001)];
        }else{
            $commentList = (new Comment())->getCommentListOfStock($stockId,$pageId-1,$count);
            $pageInfo = $this->_PAGEV2($pageId,count($commentList),$count);
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "commentList" => array_values($commentList),
                "pageInfo" => $pageInfo,
            ])];
        }
    }
}
