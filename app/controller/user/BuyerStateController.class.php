<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-11-27
 * Time: 下午4:51
 */
class BuyerStateController extends AppBaseController{
    public function __construct(){
        $this->addInterceptor(new LoginInterceptor());
    }
    /**
     * 状态详情列表页
     */
    public function detailAction(){
        $stateId = $this->_GET('state_id');
        $pageId = $this->_GET('pageId',1);
        $count = $this->_GET('count',10);
        if(empty($stateId)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "msg" => "没有详情可以显示"
            ],10001)];
        }else{
            $curUserId = (new User())->getCurrentUser()->mId;

            $buyerPic = new BuyerPic();
            $stateDetail = $buyerPic->getDetailByStateId($stateId);
            if(empty($stateDetail)){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    "msg" => "没有详情可以显示"
                ],10001)];
            }

            $buyerInfo = (new Buyer())->getBuyerInfo($stateDetail['buyer_id']);
            $likeList  = (new Favor())->getStateLikeList($stateId,0,8);

            $liked = false;
            if( !empty($curUserId) ){
                $liked = (new Favor())->isLiked($curUserId,$stateId,Favor::STATE);
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

            $comment = new Comment();
            $commentList = $comment->getCommentListOfState($stateId,$pageId-1, $count);
            $allCount = count($commentList);
            $pageInfo=$this->_PAGEV2($pageId,$allCount,$count);

            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "stateDetail" => array(
                    "imgs" => $stateDetail['imgs'],
                    "note" => $stateDetail['note'],
                    "id"=> $stateDetail['id'],
                    "followed" => $liked, //这个followed与外边的liked是一个东西，因为android使用了外边的liked，而iOS的数据结构放在内部，因此我搞了这个坑
                ),
                "buyerInfo" => $buyerInfo,
                "like" => array(
                    "liked" => $stateDetail['liked'],
                    "likeList" => $likeList,
                ),
                "liked" => $liked,
                "commentList" => array(
                    "commented"=> $stateDetail['commented'],
                    "commentList" => array_values($commentList),
                ),
                "pageInfo" => $pageInfo])];
        }
    }

    /**
     * 给状态提交评论
     */
    public function commitAction(){
        $commentType = $this->_POST('comment_type','1');
        $replyId = $this->_POST('reply_id');
        $comment = $this->_POST('comment');
        $stateId = $this->_POST('state_id');

        $ciUserId = (new User())->getCurrentUser()->mId;
        $ciUserType = 1;

        $ret = (new Comment())->commit2State($ciUserId,$ciUserType,$commentType,$replyId,$comment,$stateId);
        if(!empty($ret)){
            Comment::notify($ret);
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
            'msg' => '提交评论成功'])];
    }

    /**
     * 点赞成功
     * @return array
     */
    public function likeAction(){
        $stateId = $this->_GET('state_id');
        $favor = new Favor();

        $curUserId = (new User())->getCurrentUser()->mId;
        $state = (new BuyerPic())->getDetailByStateId($stateId);
        $buyerId = $state['buyer_id'];

        if($favor->isLiked($curUserId, $stateId,2)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                'msg' => '已经点过赞咯~'
            ],1002)];
        }else{
            if($favor->likeState($curUserId, $stateId, $buyerId)){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    'msg' => '点赞成功'
                ])];
            }else{
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    'msg' => '关注失败,请重试'
                ],1002)];
            }
        }
    }

    /**
     * 取消点赞接口
     * @return array
     */
    public function unlikeAction(){
        $stateId = $this->_GET('state_id');
        $favor = new Favor();

        $curUserId = (new User())->getCurrentUser()->mId;

        if($favor->isLiked($curUserId, $stateId,2)){
            if($favor->unlikeState($curUserId, $stateId)){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    'msg' => '亲，你不喜欢我哦~'
                ])];
            }else{
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    'msg' => '网络不给力，取消点赞失败哦~'
                ],1002)];
            }
        }else{
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                'msg' => '亲，你还没点赞呢~'
            ],1002)];
        }
    }

    /**
     * 商品的评价列表
     * @return array
     */
    public function commentListAction(){
        $stateId = $this->_GET('state_id');
        $pageId = $this->_GET('pageId',1);
        $count = $this->_GET('count',10);
        if(empty($stateId)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'无商品'],10001)];
        }else{
            $commentList = (new Comment())->getCommentListOfState($stateId,$pageId-1,$count);

            $pageInfo = $this->_PAGEV2($pageId,count($commentList),$count);
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "commentList" => array_values($commentList),
                "pageInfo" => $pageInfo,
            ])];
        }
    }
}