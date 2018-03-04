<?php
/**
 * Created by PhpStorm.
 * Aimed: 用于记录用户的状态商品排序表
 * User: dingping
 * Date: 14-11-24
 * Time: 上午11:40
 */
class StateRank extends Base_State_Rank{

    const PAGE_NUM = 10;

    /**
     * @param $userId
     * @param $pageId
     * @param $limit
     * @return null
     */
    public function getStateListByUserId($userId,$pageId = 0,$limit = 3){
        if(empty($userId) || $limit <= 0){
            return null;
        }else{
            $offset = $pageId * $limit;
            $stateRank = new StateRank();
            $stateRankList = $stateRank->addWhere('buyer_id', $userId)->addWhere('status', 1)->limit($offset, $limit)->orderBy('id','desc')->find();
            $stateIdList = array();
            $stockIdList = array();
            foreach($stateRankList as $stateRank){
                if($stateRank->mType == 1){
                    $stockIdList []= $stateRank->mStateId;
                }else if($stateRank->mType == 2){
                    $stateIdList []= $stateRank->mStateId;
                }else{

                }
            }
            if(count($stateIdList) >= 1){
                //取到买手的状态列表
                $BuyerPic = new BuyerPic();
                $stateList = $BuyerPic->addWhere('id',$stateIdList, "in")->addWhere('status', 1)->find();
                $newStateList = array();
                foreach($stateList as $state){
                    $ImgsList  = json_decode($state->mImgs);
                    $newStateList[$state->mId] = array('id' => $state->mId,'type' => 2, 'img' => $ImgsList[0], 'create_time' => $state->mCreateTime, 'liked' => $state->mLiked, 'commented' => $state->mCommented,'note'=> $state->mNote );
                }
            }

            if(count($stockIdList) >=1){
                //取到买手的商品列表
                $Stock = new Stock();
                $stockList = $Stock->addWhere('id', $stockIdList,"in")->find();
                $newStockList = array();
                foreach($stockList as $stock){
                    $ImgsList  = json_decode($stock->mImgs);
                    $newStockList[$stock->mId] = array('id' => $stock->mId,'price' => $stock->mPriceout,'type' => 1, 'img' => $ImgsList[0], 'create_time' => $stock->mCreateTime, 'liked' => $stock->mLiked, 'commented' => $stock->mCommented,'note'=> $stock->mNote );
                }
            }

            $mergeList = array();
            foreach($stateRankList as $stateRank){
                if($stateRank->mType == 1){
                    $mergeList []= $newStockList[$stateRank->mStateId];
                }else if($stateRank->mType == 2){
                    $mergeList []= $newStateList[$stateRank->mStateId];
                }
            }
            return $mergeList;
        }
    }

    /**
     * 获取userId获取推荐的总数
     * @param $buyerId
     * @return int
     */
    public function getStateCountByBuyerId($buyerId){
        if(empty($buyerId)){
            return 0;
        }else{
            $total = $this->addWhere('buyer_id',$buyerId)->addWhere('status', 1)->count();
            return $total;
        }
    }

    /**
     * 商品上推荐
     */
    public function Stock2Rank($stockList){
        if(count($stockList) == 0){
            return true;
        }else{
            foreach($stockList as $stock){
                $this->setData([
                    'buyer_id' => $stock['buyer_id'],
                    'type' => 1,
                    'state_id' => $stock['id'],
                    'status' => $stock['onshelf'],
                    'create_time' => $stock['create_time'],
                    'update_time' => $stock['update_time'],
                ])->save();
            }
        }
    }

}