<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-11-27
 * Time: 上午1:24
 */
class Comment extends Base_Comment{

    /**
     * 商品类型
     */
    const STOCK = 1;
    /**
     * 状态类型
     */
    const STATE = 2;

    /**
     * 评论
     */
    const COMMENT = 1;

    /**
     * 回复
     */
    const REPLY = 2;

    /**
     * 引用
     */
    const QUOTE = 3;

    /**
     * 买家
     */
    const USER = 1;

    /**
     * 买手
     */
    const BUYER = 2;

    /**
     * 获取状态的评论列表
     * @param $stateId
     * @param $stateType
     * @param $pageId
     * @param $count
     * @return array
     */
    public function getCommentListOfBase($stateId, $stateType, $pageId = 0, $count = 10){
        if(empty($stateId) || empty($stateType) || $pageId < 0){
            return array();
        }else{
            if($count >= 20 || $count < 0){
                $count = 20;
            }
            $offset = $pageId * $count;

            $comment = new self();
            $commentRet = $comment->addWhere('state_type', $stateType)->addWhere('state_id', $stateId)->addWhere('status',1)->orderBy('id', 'desc')->limit($offset, $count)->find();

            //被回复用户的买家用户集合
            $userIdList = array();
            //被回复用户的买手用户集合
            $buyerIdList = array();
            foreach($commentRet as $oneComment){
                if($oneComment->mCiUserType == 1){
                    $userIdList []= $oneComment->mCiUserId;
                }else if($oneComment->mCiUserType == 2){
                    $buyerIdList []= $oneComment->mCiUserId;
                }

                if(!empty($oneComment->mReplyUserId)){
                    if($oneComment->mReplyUserType == 1){
                        $userIdList []= $oneComment->mReplyUserId;
                    }else if($oneComment->mReplyUserType == 2){
                        $buyerIdList []= $oneComment->mReplyUserId;
                    }
                }
            }

            $userMap = array();
            if(count($userIdList) >= 1){
                $user = new User();
                $userMap = $user->addWhere('id', $userIdList,'in')->findMap('id');
            }

            $buyerMap = array();
            if(count($buyerIdList) >= 1){
                $buyer = new Buyer();
                $buyerMap = $buyer->addWhere('id', $buyerIdList,'in')->findMap('id');
            }

            $commentList = array();
            foreach($commentRet as $oneComment){
                $commentId = $oneComment->mId;
                $content = $oneComment->mComment;
                switch($oneComment->mReplyUserType){
                    case 1:
                        $replyName = $userMap[$oneComment->mReplyUserId]->mName;
                        break;
                    case 2:
                        $replyName = $buyerMap[$oneComment->mReplyUserId]->mName;
                        break;
                    default:
                        $replyName = null;
                }
                switch($oneComment->mCiUserType){
                    case 1:
                        $ciName = $userMap[$oneComment->mCiUserId]->mName;
                        break;
                    case 2:
                        $ciName = $buyerMap[$oneComment->mCiUserId]->mName;
                        break;
                    default:
                        $ciName = null;
                }
                switch($oneComment->mCommentType){
                    case self::COMMENT:
                        $replyAction = "";
                        break;
                    case self::REPLY:
                        $replyAction = "回复$replyName:";
                        break;
                    case self::QUOTE:
                        $replyAction = "引用$replyName:";
                        break;
                    default:
                        $replyAction = "";
                        break;
                }
                $commentList[$commentId] = array(
                    "comment" => $content,
                    "owner_id" => $oneComment->mOwnerId,
                    "owner_type" => $oneComment->mOwnerType,
                    "ci_user_name" => $ciName,
                    "ci_user_id" => $oneComment->mCiUserId,
                    "ci_user_type" => $oneComment->mCiUserType,
                    "ci_user_level" => $oneComment->mCiUserType == 2 ? Buyer::level($buyerMap[$oneComment->mCiUserId]->mLevel): null,
                    "reply_name" => $replyName,
                    "reply_id" => $oneComment->mReplyId,
                    "reply_user_id" => $oneComment->mReplyUserId,
                    "reply_user_type" => $oneComment->mReplyUserType,
                    "update_time" => $oneComment->mUpdateTime,
                    "id" => $oneComment->mId,
                    "ci_user_avatar_url" => $oneComment->mCiUserType== 1 ? $userMap[$oneComment->mCiUserId]->mAvatarUrl :$buyerMap[$oneComment->mCiUserId]->mHead,
                );

            }
            return $commentList;
        }
    }

    /**
     * 获取图片状态的评论列表
     * @param $stateId
     * @param $pageId
     * @param $count
     * @return array
     */
    public function getCommentListOfState($stateId, $pageId = 0, $count = 10){
        return $this->getCommentListOfBase($stateId, self::STATE, $pageId, $count);
    }

    /**
     * 获取商品的评论列表
     * @param $stateId
     * @param $pageId
     * @param $count
     * @return array
     */
    public function getCommentListOfStock($stateId, $pageId = 0, $count = 10){
        return $this->getCommentListOfBase($stateId, self::STOCK, $pageId, $count);
    }

    /**
     * 提交评论的基础方法
     * @param $ciUserId
     * @param $ciUserType
     * @param int $commentType
     * @param $replyUserType
     * @param $replyUserId
     * @param $replyId
     * @param $content
     * @param $stateId
     * @param $stateType
     * @param $ownerType
     * @param $ownerId
     * @return bool|mixed
     */
    protected function commitCommentBase($ciUserId,$ciUserType,$commentType = self::COMMENT,$replyUserType,$replyUserId,$replyId,$content,$stateId,$stateType,$ownerType,$ownerId){
        if(empty($ciUserId)||empty($ciUserType)||empty($commentType)||empty($content)||empty($stateId)||empty($stateType)||empty($ownerType)||empty($ownerId)){
            return false;
        }else{
            $comment = new self();
            $data = [
                'ci_user_type' => $ciUserType,
                'ci_user_id' => $ciUserId,
                'comment' => $content,
                'state_type' => $stateType,
                'state_id' => $stateId,
                'owner_type' => $ownerType,
                'owner_id' => $ownerId,
                'comment_type' => $commentType,
                'reply_id' => $replyId,
                'reply_user_type' => $replyUserType,
                'reply_user_id' => $replyUserId,
                'status' => 1,
                'create_time' => time(),
                'update_time' => time(),
            ];
            $res = $comment->setData($data)->save();
            if(!empty($res)){
                return $data;
            }else{
                return false;
            }
        }
    }

    /**
     * 提交评论给状态栏
     * @param $ciUserId
     * @param $ciUserType
     * @param $commentType
     * @param $replyId
     * @param $comment
     * @param $stateId
     * @return bool|mixed
     */
    public function commit2State($ciUserId,$ciUserType,$commentType,$replyId,$comment,$stateId){
        $state = (new BuyerPic())->getDetailByStateId($stateId);
        $ownerType = self::BUYER;
        $ownerId = $state['buyer_id'];
        $replyUserType = null;
        $replyUserId = null;
        if(!empty($replyId)){
            $replyComment = $this->getCommentByCommentId($replyId);
            $replyUserType = $replyComment['ci_user_type'];
            $replyUserId = $replyComment['ci_user_id'];
        }
        $ret = $this->commitCommentBase($ciUserId,$ciUserType,$commentType,$replyUserType,$replyUserId,$replyId,$comment,$stateId,self::STATE ,$ownerType,$ownerId);
        if(!empty($ret)){
            $buyer = new DBTable('buyer_pic');
            $buyer->addWhere('id',$stateId)->update(['commented'=> ["`commented`+1",DBTable::NO_ESCAPE]]) ;
        }
        return $ret;
    }

    /**
     * 提交评论给商品
     * @param $ciUserId
     * @param $ciUserType
     * @param $commentType
     * @param $replyId
     * @param $comment
     * @param $stockId
     * @return bool|mixed
     */
    public function commit2Stock($ciUserId,$ciUserType,$commentType,$replyId,$comment,$stockId){
        $stockInfo = (new Stock())->getBaseInfoByStockId($stockId);
        $ownerType = self::BUYER;
        $ownerId = $stockInfo['buyer_id'];
        $replyUserType = null;
        $replyUserId = null;
        if(!empty($replyId)){
            $replyComment = $this->getCommentByCommentId($replyId);
            $replyUserType = $replyComment['ci_user_type'];
            $replyUserId = $replyComment['ci_user_id'];
        }
        $ret = $this->commitCommentBase($ciUserId,$ciUserType,$commentType,$replyUserType,$replyUserId,$replyId,$comment,$stockId,self::STOCK ,$ownerType,$ownerId);
        if(!empty($ret)){
            $buyer = new DBTable('stock');
            $buyer->addWhere('id',$stockId)->update(['commented'=> ["`commented`+1",DBTable::NO_ESCAPE]]) ;
        }
        return $ret;
    }

    /**
     * 根据commentId获取comment的内容
     * @param $commentId
     * @return array|null
     */
    public function getCommentByCommentId($commentId){
        if(empty($commentId)){
            return null;
        }else{
            return $this->addWhere('id',$commentId)->select()->getData();
        }
    }

    /**
     * 根据用户id，type查询回复评论列表
     * @param $replyUserId
     * @param $replyUserType
     * @param $pageId
     * @param $count
     * @return array|null
     */
    public function getListByUserId($replyUserId,$replyUserType,$pageId,$count){
        if(empty($replyUserId) || empty($replyUserType) ){
            return null;
        }else{
            $offset = $pageId * $count;
            $replyList = $this->addWhere('reply_user_id',$replyUserId)->addWhere('reply_user_type',$replyUserType)->addWhere('status',1)->orderBy('id','desc')->limit($offset,$count)->find();
            $replyList = array_map(function($reply){
                return $reply->getData();
            },$replyList);

            $buyerIdList = array_values(array_filter(array_map(function($reply){
                if($reply['ci_user_type'] == self::BUYER){
                    return $reply['ci_user_id'];
                }
            },$replyList)));
            $buyerList = (new Buyer())->getListByIdList($buyerIdList);
            $newBuyerList = array();
            foreach($buyerList as $buyer){
                $newBuyerList[$buyer['id']] = $buyer;
            }
            $userIdList = array_values(array_filter(array_map(function($reply){
                if($reply['ci_user_type'] == self::USER){
                    return $reply['ci_user_id'];
                }
            },$replyList)));
            $userList = (new User())->getUserListByIdList($userIdList);
            $newUserList = array();
            foreach($userList as $user){
                $newUserList[$user['id']] = $user;
            }
            $replyList = array_map(function($reply)use($newUserList,$newBuyerList){
                if($reply['ci_user_type'] == Comment::USER){
                    $reply['ci_user_name'] = $newUserList[$reply['ci_user_id']]['name'];
                    $reply['ci_user_avatar_url'] = $newUserList[$reply['ci_user_id']]['avatar_url'];
                }else{
                    $reply['ci_user_name'] = $newBuyerList[$reply['ci_user_id']]['name'];
                    $reply['ci_user_avatar_url'] = $newBuyerList[$reply['ci_user_id']]['head'];
                }
                if($reply['state_type'] == Comment::STOCK){
                    $reply['picUrl'] = "AMCustomerURL://stockdetail?id=".$reply['state_id'];
                }else if($reply['state_type'] == Comment::STATE){
                    $reply['picUrl'] = "AMCustomerURL://statedetail?id=".$reply['state_id'];
                }
                return $reply;
            },$replyList);
            return $replyList;
        }
    }

    /**
     * 根据buyerId，type查询回复评论列表
     * @param $buyerId
     * @param $userType
     * @param $pageId
     * @param $count
     * @return array|null
     */
    public function getListByBuyerId($buyerId,$userType,$pageId,$count){
        if(empty($buyerId) || empty($userType) ){
            return null;
        }else{
            $offset = $pageId * $count;
            $replyList = $this->addWhere("owner_id",$buyerId)->addWhereRaw("and (comment_type = 1 or  (comment_type = 2 and reply_user_id = $buyerId and reply_user_type = 2))")->addWhere('status',1)->orderBy('id','desc')->limit($offset,$count)->find();
            $replyList = array_map(function($reply){
                return $reply->getData();
            },$replyList);

            $buyerIdList = array_values(array_filter(array_map(function($reply){
                if($reply['ci_user_type'] == self::BUYER){
                    return $reply['ci_user_id'];
                }
            },$replyList)));
            $buyerList = (new Buyer())->getListByIdList($buyerIdList);
            $newBuyerList = array();
            foreach($buyerList as $buyer){
                $newBuyerList[$buyer['id']] = $buyer;
            }
            $userIdList = array_values(array_filter(array_map(function($reply){
                if($reply['ci_user_type'] == self::USER){
                    return $reply['ci_user_id'];
                }
            },$replyList)));
            $userList = (new User())->getUserListByIdList($userIdList);
            $newUserList = array();
            foreach($userList as $user){
                $newUserList[$user['id']] = $user;
            }
            $replyList = array_map(function($reply)use($newUserList,$newBuyerList){
                if($reply['ci_user_type'] == Comment::USER){
                    $reply['ci_user_name'] = $newUserList[$reply['ci_user_id']]['name'];
                    $reply['ci_user_avatar_url'] = $newUserList[$reply['ci_user_id']]['avatar_url'];
                }else{
                    $reply['ci_user_name'] = $newBuyerList[$reply['ci_user_id']]['name'];
                    $reply['ci_user_avatar_url'] = $newBuyerList[$reply['ci_user_id']]['head'];
                }
                if($reply['state_type'] == Comment::STOCK){
                    $reply['picUrl'] = "AMCustomerURL://stockdetail?id=".$reply['state_id'];
                }else if($reply['state_type'] == Comment::STATE){
                    $reply['picUrl'] = "AMCustomerURL://statedetail?id=".$reply['state_id'];
                }
                return $reply;
            },$replyList);
            return $replyList;
        }
    }

    /**
     * 评论通知
     * @param $comment
     * @return null
     */
    public static function notify($comment){
        if(empty($comment)){
            return null;
        }else{
            $str = "";
            //回复者的身份
            $userInfo = UserBase::getInstance()->getBaseUserInfo($comment['ci_user_id'],$comment['ci_user_type']);
            if(!empty($comment['reply_id'])){
                $replyUserInfo = UserBase::getInstance()->getBaseUserInfo($comment['reply_user_id'],$comment['reply_user_type']);
            }
            if($comment['ci_user_type'] == UserBase::BUYER){
                if(!empty($comment['reply_id'])){
                    $str = "买手@".$userInfo['name']."对你说".$comment['comment'];
                    Notification::sendNotify($comment['reply_user_id'],$comment['reply_user_type'],['title'=>$str,'type'=>'comment','from'=>'comment'],0);
                    if($comment['ci_user_id'] != $comment['owner_id']){
                        $str = "买手@".$userInfo['name']."回复:".$replyUserInfo['name']."说".$comment['comment'];
                        Notification::sendNotify($comment['owner_id'],$comment['owner_type'],['title'=>$str,'type'=>'comment','from'=>'comment'],0);
                    }
                }else{
                    if($comment['ci_user_id'] != $comment['owner_id']){
                        $str = "买手@".$userInfo['name']."对你说".$comment['comment'];
                        Notification::sendNotify($comment['owner_id'],$comment['owner_type'],['title'=>$str,'type'=>'comment','from'=>'comment'],0);
                    }
                }
            }else{
                if(!empty($comment['reply_id'])){
                    if($comment['ci_user_type'] == $comment['reply_user_type'] && $comment['ci_user_id'] == $comment['reply_user_id']){
                        //买家自己回复自己就什么都不做
                        ;
                    }else{
                        $str = "@".$userInfo['name']."对你说".$comment['comment'];
                        Notification::sendNotify($comment['reply_user_id'],$comment['reply_user_type'],['title'=>$str,'type'=>'comment','from'=>'comment'],0);
                    }
                }else{
                    $str = "@".$userInfo['name']."对你说".$comment['comment'];
                    Notification::sendNotify($comment['owner_id'],$comment['owner_type'],['title'=>$str,'type'=>'comment','from'=>'comment'],0);
                }
            }
        }
        return true;
    }
}
