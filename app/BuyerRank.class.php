<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-12-2
 * Time: 上午12:49
 */
class BuyerRank extends Base_Buyer_Rank{

    /**
     * 运营推荐
     */
    const RECOMMEND = 1;

    /**
     * 最新
     */
    const LATEST = 2;
    /**
     * 获取买手的最新列表
     * @param $pageId
     * @param $count
     * @return array
     */
    public function getLatestBuyerIdList($pageId,$count){
        $offset = $pageId * $count;
        $buyerIdList = $this->setCols('buyer_id')->addWhere('type',self::LATEST)->orderBy('update_time','desc')->limit($offset,$count)->find();
        $buyerIdList = array_map(function($buyerId){
            return $buyerId->mBuyerId;
        },$buyerIdList);
        return $buyerIdList;
    }

    /**
     * 获取买手推荐的推荐列表
     * @param $pageId
     * @param $count
     * @return array
     */
    public function getRecommendBuyerIdList($pageId,$count){
        $offset = $pageId * $count;
        $buyerIdList = $this->setCols('buyer_id')->addWhere('type',self::RECOMMEND)->orderBy('update_time','desc')->limit($offset,$count)->find();
        $buyerIdList = array_map(function($buyerId){
            return $buyerId->mBuyerId;
        },$buyerIdList);
        return $buyerIdList;
    }

    /**
     * 推荐买手
     * @param $buyer_id
     * @param $selector_id
     * @param null $comment
     * @return bool | array
     */
    public function recommendBuyer($buyer_id,$selector_id,$comment=null){
        $buyer = $this->addWhere('buyer_id',$buyer_id)->addWhere('type',self::RECOMMEND)->select();
        if(empty($buyer)){
            $res =$this->setData([
                'buyer_id' =>$buyer_id,
                'selector_id'=>$selector_id,
                'type'=>self::RECOMMEND,
                'create_time'=>time(),
                'update_time'=>time(),
                'comment'=>$comment,
            ])->save();
        }else{
            $res =$this->addWhere('buyer_id',$buyer_id)->addWhere('type',self::RECOMMEND)->update([
                'selector_id'=>$selector_id,
                'update_time'=>time(),
                'comment'=>$comment,
            ]);
        }
        if(!empty($res)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 设置买手的最新动作
     * @param $buyer_id
     * @param $lastUpdated
     * @return array | bool
     */
    public function setLatestBuyer($buyer_id,$lastUpdated){
        if(empty($buyer_id) || empty($lastUpdated)){
            return false;
        }else{
            $buyer = $this->addWhere('buyer_id',$buyer_id)->addWhere('type',self::LATEST)->select();
            if(empty($buyer)){
                $res =$this->setData([
                    'buyer_id' =>$buyer_id,
                    'selector_id'=>null,
                    'type'=>self::LATEST,
                    'create_time'=>time(),
                    'update_time'=>$lastUpdated,
                    'comment'=>null,
                ])->save();
            }else{
                $res =$this->addWhere('buyer_id',$buyer_id)->addWhere('type',self::LATEST)->update([
                    'update_time'=>$lastUpdated,
                ]);
            }
            if(empty($res)){
                return false;
            }else{
                return true;
            }
        }
    }
}