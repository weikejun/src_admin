<?php

class FeedbackController extends AppBaseController{
    public function __construct(){
        #$this->addInterceptor(new LoginInterceptor());        
    }
    public function addAction(){
        $feedback = new Feedback();
        $feedback->mInfo = $this->_POST("info",""); 
        $feedback->mUserId=User::getCurrentUser()->mId;
        $feedback->mCreateTime = time();
        if($feedback->save()){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['id'=>$feedback->mId])];
        }else{
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'can\'t save user addr'],99999)];
        }
    }
    public function stockAction(){
        $feedback= new StockFeedback();
        $feedback->mLiveId = $this->_POST("live_id","","99999"); 
        $feedback->mStockId = $this->_POST("stock_id","","99999"); 
        $feedback->mBuyerId= $this->_POST("buyer_id","","99999"); 
        $feedback->mInfo = $this->_POST("info",""); 
        $feedback->mType = $this->_POST("type",""); 
        if(User::getCurrentUser()){
            $feedback->mUserId=User::getCurrentUser()->mId;
        }
        $feedback->mCreateTime = time();
        if($feedback->save()){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['id'=>$feedback->mId])];
        }else{
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'can\'t save feedback'],99999)];
        }
    }
}
