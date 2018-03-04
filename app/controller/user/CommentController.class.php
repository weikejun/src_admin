<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-11-27
 * Time: 下午4:51
 */
class CommentController extends AppBaseController{
    public function __construct(){
        $this->addInterceptor(new LoginInterceptor());
    }

    /**
     * 消息评论列表
     */
    public function listAction(){
        $pageId = $this->_GET("pageId",1);
        $count = $this->_GET("count",10);

        $curUserId = User::getCurrentUser()->mId;
        $commentList = (new Comment())->getListByUserId($curUserId,Comment::USER,$pageId-1,$count);
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