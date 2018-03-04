<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * amied: like
 * Date: 14-11-25
 * Time: 下午3:21
 */
class Favor extends Base_Favor{

    /**
     * 商品
     */
    const STOCK = 1;

    /**
     * 状态
     */
    const STATE = 2;

    /**
     * 买手
     */
    const BUYER = 3;
    /**
     * 根据输入的买手列表来判断用户是否有follow
     * @param $buyerIdList
     * @return array
     */
    public function followedList($buyerIdList){
        return $this->favorList($buyerIdList, self::BUYER);
    }

    /**
     * 用户和一堆商品之间是否有喜欢关系
     * @param $stockIdList
     * @return array
     */
    public function likeStockList($stockIdList){
        return $this->favorList($stockIdList, self::STOCK);
    }

    /**
     * 用户和一堆状态之间是否有喜欢关系
     * @param $stateIdList
     * @return array
     */
    public function likeStateList($stateIdList){
        return $this->favorList($stateIdList, self::STATE);
    }

    /**
     * 抽象一个事务与用户之间的喜欢关系
     * @param $idList
     * @param $type
     * @return array
     */
    protected function favorList($idList,$type){
        $retArray = array();
        $curUser = User::getCurrentUser();
        if(!empty($curUser)){
            $curUserId = $curUser->mId;
        }else{
            $curUserId = null;
        }

        if(!empty($curUserId)){
            if(count($idList) >= 1){
                $favor = new self();
                $favorList = $favor->addWhere('favor_id',$idList,'in')->addWhere('valid',1)->addWhere('user_id', $curUserId)->addWhere('favor_type',$type)->find();
                foreach($favorList as $tempFavor){
                    $retArray[$tempFavor->mFavorId] = true;
                }
            }
        }else{
            foreach($idList as $id){
                $retArray[$id] = false;
            }
        }
        return $retArray;
    }

    /**
     * 获取用户的跟随买手列表
     * @param $userId
     * @param $pageId
     * @param $count
     * @return array
     */
    public function getBuyerFollowListOfUser($userId,$pageId=0,$count=10){
        if(empty($userId)){
            return array();
        }else{
            $offset = $pageId * $count;
            $favorList = $this->addWhere('favor_type',self::BUYER)->addWhere('valid',1)->addWhere('user_id', $userId)->orderBy('create_time','DESC')->limit($offset,$count)->find();
            return $favorList;
        }
    }

    /**
     * 追随
     * @param $userId
     * @param $buyerId
     */
    public function follow($userId, $buyerId){
        if(empty($userId) || empty($buyerId)){
            return true;
        }
        $res = $this->like($userId,$buyerId,self::BUYER,$buyerId);
        if(!empty($res)){
            $buyer = new DBTable('buyer');
            $buyer->addWhere('id',$buyerId)->update(['fans'=> ["`fans`+1",DBTable::NO_ESCAPE]]) ;
            return true;
        }
    }

    /**
     * 不跟随
     * @param $userId
     * @param $buyerId
     * @return bool
     */
    public function unFollow($userId, $buyerId){
        if(empty($userId) || empty($buyerId)){
            return true;
        }else{
            $res = $this->unLike($userId,$buyerId,self::BUYER);
            if(!empty($res)){
                $buyer = new DBTable('buyer');
                $buyer->addWhere('id',$buyerId)->addWhereRaw('and fans >= 1')->update(['fans'=> ["`fans`-1",DBTable::NO_ESCAPE]]) ;
            }
            return true;
        }
    }

    /**
     * 是否已经跟随
     * @param $userId
     * @param $buyerId
     * @return boolean
     */
    public function isFollowed($userId, $buyerId){
        return $this->isLiked($userId,$buyerId,self::BUYER);
    }

    /**
     * 抽象的喜欢方法
     * @param $userId
     * @param $stateId
     * @param $stateType
     * @param $notifyId
     * @return array
     */
    protected function like($userId, $stateId, $stateType, $notifyId){
        $favor = new self();
        $ret = $favor->addWhere('favor_id',$stateId)->addWhere('favor_type', $stateType)->addWhere('user_id', $userId)->select();
        $res = false;
        if(empty($ret)){
            $favor->setData([
                    'favor_type'=> $stateType,
                    'favor_id' => $stateId,
                    'notify_id' => $notifyId,
                    'user_id' => $userId,
                    'valid' => 1,
                    'read' => 0,
                    'create_time' => time(),
                    'update_time' => time(),
                ]
            );
            $res = $favor->save();
        }else if($favor->mValid == 0){
            $favorId = $favor->mId;
            $favor->clear();
            $favor->setData([
                    'id' => $favorId,
                    'valid' => 1,
                    'update_time'=> time(),
                ]
            );
            $favor->addWhere('id',$favorId);
            $res = $favor->save();
        }
        return $res;
    }

    /**
     * 抽象所有的取消喜欢状态
     * @param $userId
     * @param $stateId
     * @param $stateType
     * @return bool
     */
    protected function unLike($userId, $stateId, $stateType){
        if(empty($userId) || empty($stateId) || empty($stateId)){
            return false;
        }else{
            $favor = new self();
            $favorRet = $favor->addWhere('favor_type',$stateType)->addWhere('favor_id',$stateId)->addWhere('user_id', $userId)->find();
            if(empty($favorRet)){
                return false;
            }else{
                $favor->addWhere('favor_type',$stateType)->addWhere('favor_id',$stateId)->addWhere('user_id',$userId);
                $favor->update(['valid' => 0,'update_time' => time()]);
                return true;
            }
        }
    }

    /**
     * 是否具有喜欢关系
     * @param $userId
     * @param $stateId
     * @param $stateType
     * @return bool
     */
    public function isLiked($userId, $stateId, $stateType){
        $favor = new self();
        $favorRet = $favor->addWhere('favor_type',$stateType)->addWhere('favor_id',$stateId)->addWhere('user_id', $userId)->addWhere('valid',1)->find();
        if(empty($favorRet)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 获取喜欢列表
     * @param $stateId
     * @param $stateType
     * @param int $pageId
     * @param int $count
     * @return array
     */
    protected function likeList($stateId,$stateType,$pageId = 0, $count = 10){
        if(empty($stateId) || empty($stateType) || $pageId < 0 || !isset($pageId)){
            return array();
        }
        if($count >= 20 || $count <= 0){
            $count = 10;
        }
        $offset = $pageId * $count;
        $favor = new self();
        $favorList = $favor->addWhere('favor_type',$stateType)->addWhere('favor_id',$stateId)->addWhere('valid',1)->orderBy('update_time','desc')->limit($offset, $count)->find();
        $userIdList = array_map(function($everyFavor){
            return $everyFavor->mUserId;
        }, $favorList);
        if(count($userIdList) >= 1){
            $user = new User();
            $userList = $user->addWhere('id', $userIdList, 'in')->find();
            $userList = array_map(
                function($user){
                    return $user->getData();
                },$userList);
            return $userList;
        }else{
            return array();
        }
    }

    /**
     * 获取状态的点赞列表
     * @param $stateId
     * @param int $pageId
     * @param int $count
     * @return array
     */
    public function getStateLikeList($stateId, $pageId = 0, $count = 10){
        return $this->likeList($stateId, self::STATE,$pageId,$count);
    }

    /**
     * 状态点赞
     * @param $userId
     * @param $stateId
     * @param $notifyId
     * @return bool
     */
    public function likeState($userId, $stateId, $notifyId){
        $res = $this->like($userId,$stateId,self::STATE,$notifyId);
        if(!empty($res)){
            $buyer = new DBTable('buyer_pic');
            $buyer->addWhere('id',$stateId)->update(['liked'=> ["`liked`+1",DBTable::NO_ESCAPE]]) ;
            return true;
        }else{
            return false;
        }
    }

    /**
     * 取消对状态的喜欢状态
     * @param $userId
     * @param $stateId
     * @return bool
     */
    public function unlikeState($userId, $stateId){
        $res = $this->unLike($userId,$stateId,self::STATE);
        if(!empty($res)){
            $buyer = new DBTable('buyer_pic');
            $buyer->addWhere('id',$stateId)->addWhereRaw('and liked >= 1')->update(['liked'=> ["`liked`-1",DBTable::NO_ESCAPE]]) ;
        }
        return true;
    }

    /**
     * 获取商品的点赞列表
     * @param $stateId
     * @param int $pageId
     * @param int $count
     * @return array
     */
    public function getStockLikeList($stateId, $pageId = 0, $count = 10){
        return $this->likeList($stateId, self::STOCK,$pageId,$count);
    }

    /**
     * 商品点赞
     * @param $userId
     * @param $stateId
     * @param $notifyId
     * @return bool
     */
    public function likeStock($userId, $stateId, $notifyId){
        $res = $this->like($userId,$stateId,self::STOCK,$notifyId);
        if(!empty($res)){
            $buyer = new DBTable('stock');
            $buyer->addWhere('id',$stateId)->update(['liked'=> ["`liked`+1",DBTable::NO_ESCAPE]]) ;
            return true;
        }else{
            return false;
        }
    }

    /**
     * 取消对商品的喜欢状态
     * @param $userId
     * @param $stateId
     * @return bool
     */
    public function unlikeStock($userId, $stateId){
        $res = $this->unLike($userId,$stateId,self::STOCK);
        if(!empty($res)){
            $buyer = new DBTable('stock');
            $buyer->addWhere('id',$stateId)->addWhereRaw('and liked >= 1')->update(['liked'=> ["`liked`-1",DBTable::NO_ESCAPE]]) ;
        }
        return true;
    }

}