<?php
class StockCommentController extends AppBaseController{
    public function addAction(){
        $commentStr=$this->_POST('comment',"",20010);
        $stockId=$this->_POST('stock_id',"",20004);
        $create_time=time();
        $comment=new StockComment();
        $ret=$comment->setData([
            'stock_id'=>$stockId,
            'user_id'=>User::getCurrentUser()->mId,
            'comment'=>$commentStr,
            'create_time'=>$create_time,
            ])->save();
        if($ret){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                "id"=>$ret,
                "comment"=>$commentStr,
                //"user_info"=>User::getCurrentUser()->getData(),
                'create_time'=>$create_time,
                "mycomment"=>true
                ])];
        }else{
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],10002)];
        }
    }
    public function replyAction(){
        $commentStr=$this->_POST('comment',"",20010);
        $id=$this->_POST('id',"",20004);
        $comment=new StockComment();
        $ret=$comment->addWhere('id',$id)->select();
        if(!$ret){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],99999)];
        }
        $reply=new StockComment();
        $create_time=time();
        $ret=$reply->setData(['stock_id'=>$comment->mStockId,'reply_id'=>$id,'user_id'=>User::getCurrentUser()->mId,'reply_user_id'=>$comment->mUserId,'session_id'=>$comment->mSessionId?$comment->mSessionId:$comment->mId,'comment'=>$commentStr,'create_time'=>$create_time])->save();
        
        if($ret){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["id"=>$ret,"comment"=>$commentStr,"user_info"=>User::getCurrentUser()->getData(),'create_time'=>$create_time,"mycomment"=>true])];
        }else{
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],99999)];
        }
    }
    public function delAction(){
        $id=$this->_POST('id',"",20004);
        $comment=new StockComment();
        $ret=$comment->addWhere('id',$id)->select();
        if(!$ret){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],20004)];
        }
        if($comment->mUserId!=User::getCurrentUser()->mId){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([],20013)];
        }
        $comment->mValid='invalid';
        $comment->save();
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([])];
    }
    public function listAction(){
        $stock_id=$this->_GET('stock_id',"",20004);
        $pageId=$this->_GET('pageId',1);
        $count=$this->_GET('count',10);
        $comment=new StockComment();
        $comment->setAutoClear(false);
        $allCount=$comment->addWhere('stock_id',$stock_id)->count();
        $pageInfo=$this->_PAGE($allCount,$pageId,$count);
        $comments=array_map(
            [$this,'processCommentData'],
            $comment->limit(($pageId-1)*$count,$count)->find()
        );
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["comments"=>$comments,"pageInfo"=>$pageInfo])];
    }
    private function processCommentData($comment){
        $data=$comment->getData();
        $user=User::get($comment->mUserId);
        $data['user_name']=$user->mUsername;
        $reply_user=User::get($comment->mReplyUserId);
        $data['reply_user_name']=$reply_user->mUsername;
        return $comment->getData();
    }
    public function sessionAction(){
        $session_id=$this->_GET('session_id',"",99999);
        $stock_comment=new StockComment();
        $comments=$stock_comment->addWhere('session_id',$session_id)->addWhere("id",$session_id,'=','or')->orderBy('id','desc')->find();
        $comments=array_map(
            [$this,'processCommentData'],
            $comments
        );
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["comments"=>$comments])];
    }
    public function myAction(){
        $user_id=User::getCurrentUser()->mId;
        $stock_comment=new StockComment();
        $reply_comments=$stock_comment->addWhere('reply_user_id',$user_id)->orderBy('id','desc')->find();
        $reply_comments=array_map(
            [$this,'processCommentData'],
            $reply_comments
        );
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["comments"=>$reply_comments])];
    }
}
