<?php

class BuyerController extends AppBaseController{
    public function __construct(){
        $this->addInterceptor(new LoginInterceptor());        
    }
    public function infoAction(){
        $buyer_id=$this->_GET('id',"",10011);
        $buyer=new Buyer();
        $buyer=$buyer->addWhere('id',$buyer_id)->select();
        if(!$buyer){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'not valid buyer id'], 10025)];
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                'easemob_username'=>$buyer->mEasemobUsername,
                'head'=>$buyer->mHead,
                'name'=>$buyer->mName,
            ])];
    }

    /**
     * 关注买手
     */
    public function followAction(){
        $buyerId = $this->_GET('buyer_id');
        $curUserId = User::getCurrentUser()->mId;

        $favor = new Favor();

        if($favor->isFollowed($curUserId, $buyerId)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                'msg' => '已关注，无需再次关注'
            ],1002)];
        }else{
            if($favor->follow($curUserId, $buyerId)){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    'msg' => '关注成功'
                ])];
            }else{
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    'msg' => '关注失败,请重试'
                ],1002)];
            }
        }
    }

    /**
     * 取消关注接口
     * @return array
     */
    public function unfollowAction(){
        $buyerId = $this->_GET('buyer_id');
        $curUserId = User::getCurrentUser()->mId;

        $favor = new Favor();

        if($favor->isFollowed($curUserId, $buyerId)){
            if($favor->unfollow($curUserId, $buyerId)){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    'msg' => '已取消关注'
                ])];
            }else{
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                    'msg' => '已取消关注'
                ])];
            }
        }else{
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
                'msg' => '已取消关注'
            ],1002)];
        }
    }

    /**
     * 我关注的买手列表
     */
    public function followListAction(){
        $pageId = $this->_GET('pageId',1);
        $count = $this->_GET('count',10);

        $curUserId = (new User())->getCurrentUser()->mId;
        $followList = (new BuyerChannel())->getFollowList($curUserId,$pageId-1,$count);
        $pageInfo = $this->_PAGEV2($pageId,count($followList),$count);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
            'buyerList' => $followList,
            'pageInfo' => $pageInfo,
        ])];
    }

}
