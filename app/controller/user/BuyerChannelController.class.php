<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-11-25
 * Time: 上午10:13
 */
class BuyerChannelController extends AppBaseController{
    public function __construct(){
    }

    /**
     * 推荐
     */
    public function recommendAction(){
        $count=$this->_GET('count',9);
        $pageId = $this->_GET('pageId',1);
        $BuyerChannel = new BuyerChannel();
        //todo: 买手推荐排序算法
        $buyerList = $BuyerChannel->getRecommendBuyerList($pageId-1,$count);
        $bannerList = $BuyerChannel->getBannerList(0, 10);
        $allCount = count($buyerList);
        $pageInfo=$this->_PAGEV2($pageId,$allCount,$count);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
            'list' => $buyerList,
            'pageInfo'=>$pageInfo,
            'bannerList'=>$bannerList,
        ])];
    }

    /**
     * 最新
     */
    public function latestAction(){
        $count=$this->_GET('count',9);
        $pageId = $this->_GET('pageId',1);
        $bannerChannel = new BuyerChannel();

        //todo: 买手最新的算法
        $buyerList = $bannerChannel->getLatestBuyerList($pageId-1,$count);
        $allCount = count($buyerList);
        $pageInfo=$this->_PAGEV2($pageId,$allCount,$count);
        $bannerList = $bannerChannel->getBannerList(0, 10);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
            'list' => $buyerList,
            'pageInfo'=>$pageInfo,
            'bannerList'=>$bannerList,
        ])];
    }

    /**
     * 买手详情
     */
    public function infoAction(){
        $buyerId = $this->_GET('buyer_id');
        if(empty($buyerId)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "msg" => "没有更多的信息可以展示TOT"
            ], 1002)];
        }else{
            $buyer = new Buyer();
            //1. 获取买手的个人信息
            $buyerInfo = $buyer->getBuyerInfo($buyerId);
            if(empty($buyerInfo)){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    "msg" => "没有更多的信息可以展示TOT"
                ], 1002)];
            }else{
                //2. 获取正在直播的列表详情
                $live = new Live();
                $liveList = $live->getLiveByBuyerId($buyerId,1,0,1);
                $living = count($liveList) >= 1 ? $liveList[0] : null;
                //3. 获取买手个人推荐
                $stateRank = new StateRank();
                $stateList = $stateRank->getStateListByUserId($buyerId,0,12);
                $recommended_count = $stateRank->getStateCountByBuyerId($buyerId);
                $stateListPageInfo = $this->_PAGEV2(1,count($stateList),12);

                //4. 是否关注
                $favor = new Favor();
                $user = new User();
                $userId = $user::getCurrentUser()->mId;
                $followed = $favor->isFollowed($userId,$buyerId);
                $buyerInfo['followed'] = $followed;

                //5. 成功直播的列表详情
                $liveList = $live->getLiveByBuyerId($buyerId,Live::LIVED,0,4);
                $livedPageInfo = $this->_PAGEV2(1,count($liveList),4);
                $lived_count = $live->getLiveCountByBuyerId($buyerId,Live::LIVED);

                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    "buyerInfo" => $buyerInfo,
                    "living" => $living,
                    "lived"  => $liveList,
                    "livedPageInfo" => $livedPageInfo,
                    "lived_count" => $lived_count,
                    "recommended" => $stateList,
                    "recommendedPageInfo" =>$stateListPageInfo,
                    "recommended_count" => $recommended_count,
                ])];
            }
        }
    }

    /**
     * 买手推荐(买手的状态/商品详情列表)
     */
    public function buyerStateAction(){
        $buyerId = $this->_GET('buyer_id');
        $count=$this->_GET('count',12);
        $pageId=$this->_GET('pageId',1);

        if(empty($buyerId)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "msg" => "没有更多内容"
            ],1002)];
        }else{
            $stateRank = new StateRank();
            $stateList = $stateRank->getStateListByUserId($buyerId,$pageId-1,$count);
            $allCount = count($stateList);
            $pageInfo=$this->_PAGEV2($pageId,$allCount,$count);
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "recommended" => $stateList,
                "pageInfo" => $pageInfo,
            ])];
        }

    }

    /**
     * 买手成功的直播列表
     */
    public function livedListAction(){
        $buyerId = $this->_GET('buyer_id');
        $count=$this->_GET('count',4);
        $pageId=$this->_GET('pageId',1);

        if(empty($buyerId)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "msg" => "没有更多的成功直播了"
            ],1002)];
        }else{
            $live = new Live();
            $liveList = $live->getLiveByBuyerId($buyerId,2,$pageId-1,$count);
            $allCount = count($liveList);
            $pageInfo=$this->_PAGEV2($pageId,$allCount,$count);
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "lived" => $liveList,
                "pageInfo" => $pageInfo,
            ])];
        }
    }
}
