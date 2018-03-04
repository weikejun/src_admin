<?php
/**
 * Created by PhpStorm.
 * Aimed: 买手频道
 * User: dingping
 * Date: 14-11-24
 * Time: 下午6:50
 */
class BuyerChannel{
    /**
     * 丰富买手的其他信息
     * @param $buyerList
     * @return array
     */
    protected function enrichBuyerList($buyerList){
        $buyerIdList = array_map(function($buyer){
            return $buyer['id'];
        },$buyerList);

        $favor = new Favor();
        $favorList = $favor->followedList($buyerIdList);

        $ret = array();
        foreach($buyerList as $buyerInfo){
            $stateRank = new StateRank();
            $buyerInfo['followed'] = empty($favorList[$buyerInfo['id']])?false:true;
            $stateList = $stateRank->getStateListByUserId($buyerInfo['id'],0,3);
            $ret []= array('buyerInfo' => $buyerInfo, 'stateList' => $stateList);
        }
        return $ret;
    }

    /**
     * 获取推荐列表
     * @param $pageId
     * @param int $count
     */
    public function getRecommendBuyerList($pageId, $count = 10){
        $buyerIdList = (new BuyerRank())->getRecommendBuyerIdList($pageId,$count);
        $buyerList = (new Buyer())->getListByIdList($buyerIdList);
        $buyerList = $this->enrichBuyerList($buyerList);
        return $buyerList;
    }

    /**
     * 获取最新的买手列表
     * @param $pageId
     * @param int $count
     */
    public function getLatestBuyerList($pageId,$count=10){
        $buyerIdList = (new BuyerRank())->getLatestBuyerIdList($pageId,$count);
        $buyerList = (new Buyer())->getListByIdList($buyerIdList);
        $buyerList = $this->enrichBuyerList($buyerList);
        return $buyerList;
    }

    /**
     * 获取用户的关注列表
     * @param $userId
     * @param int $pageId
     * @param int $count
     * @return array
     */
    public function getFollowList($userId, $pageId=0,$count=10){
        $favorList = (new Favor())->getBuyerFollowListOfUser($userId,$pageId,$count);
        $buyerIdList = array_map(
         function($favor){
             return $favor->mFavorId;
         },$favorList);

        $buyerInfoList = (new Buyer())->getListByIdList($buyerIdList);

        $ret = array();
        foreach($buyerInfoList as $buyerInfo){
            $stateRank = new StateRank();
            $buyerInfo['followed'] = true;
            $stateList = $stateRank->getStateListByUserId($buyerInfo['id'],0,3);
            $ret []= array('buyerInfo' => $buyerInfo, 'stateList' => $stateList);
        }
        return $ret;
    }

    /**
     * 获取首页banner的资源列表
     * @param $pageId
     * @param int $count
     * @return array
     */
    public function getBannerList($pageId, $count= 10){
        return (new IndexNew())->getBuyerChannelBanner($pageId,$count);
    }

    /**
     *
     */
    public function getGlobalList(){
        return array(
            '全球',
            '美国',
            '日韩',
            '欧洲',
            '港台',
            '新澳'
        );
    }

    /**
     * @return array
     */
    public function getCategory(){
        return array(
            '衣服',
            '鞋子',
            '包包',
            '皮带',
            '长裙',
            '风衣',
            '貂衣',
            '化妆品'
        );
    }
}