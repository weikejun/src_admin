<?php
/**
 * Created by PhpStorm.
 * User: boshen
 * Date: 14-12-20
 * Time: 下午6:16
 */

class CommentController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new BuyerLoginInterceptor());
    }

    //买手后台回复评论
    public function addCommentAction(){
        $commentType = $this->_POST('comment_type','1');
        $replyId = $this->_POST('reply_id');
        $comment = $this->_POST('comment');
        $stateId = $this->_POST('state_id');

        //后端做校验
        if( empty($replyId) && 1 != $commentType ) {
            $commentType = 1;
        } elseif( !empty($replyId) && 1 == $commentType ) {
            $commentType = 2;
        }

        //买手评论
        $ciUserId = Buyer::getCurrentBuyer()->mId;
        $ciUserType = UserBase::BUYER;

        $ret = (new Comment())->commit2State($ciUserId,$ciUserType,$commentType,$replyId,$comment,$stateId);
        if(!empty($ret)){
            Comment::notify($ret);
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
            'msg' => '提交评论成功'])];
    }

    /**
     * 消息评论列表，支持分页
     */
    public function commentListAction(){
        $pageId = $this->_GET("pageId",1);
        $count = $this->_GET("count",10);

        $curUserId = Buyer::getCurrentBuyer()->mId;
        $commentList = (new Comment())->getListByBuyerId($curUserId,Comment::BUYER,$pageId-1,$count);
        $stockIdList = array_map(function($comment){
            if($comment['state_type'] == Comment::STOCK){
                return $comment['state_id'];
            }
        },$commentList);
        $stateIdList = array_map(function($comment){
            if($comment['state_type'] == Comment::STATE){
                return $comment['state_id'];
            }
        },$commentList);
        $stockList = (new Stock())->genStockDetailOfStockList(array_values(array_filter($stockIdList)));
        $newStockList = array();
        foreach($stockList as $stock){
            $newStockList[$stock['id']] = $stock;
        }
        $StateList = (new BuyerPic())->genStateDetailOfStateList(array_values(array_filter($stateIdList)));
        $newStateList = array();
        foreach($StateList as $state){
            $newStateList[$state['id']] = $state;
        }
        $commentList = array_map(function($comment)use($newStateList,$newStockList){
            if($comment['state_type'] == Comment::STATE){
                $comment['pic'] = $newStateList[$comment['state_id']]['imgs'][0];
            }else if($comment['state_type'] == Comment::STOCK){
                $comment['pic'] = $newStockList[$comment['state_id']]['imgs'][0];
            }
            return $comment;
        },$commentList);
        $pageInfo = $this->_PAGEV2($pageId,count($commentList),$count);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
            "commentList"=>$commentList,
            "pageInfo"=>$pageInfo,
        ])];
    }

}