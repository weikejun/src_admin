<?php
/**
 * Created by PhpStorm.
 * Aimed: 记录买手的生活图片表
 * User: dingping
 * Date: 14-11-24
 * Time: 上午11:33
 */
class BuyerPic extends Base_Buyer_Pic{
    //可以继承一些基本方法
    /**
     * 返回买手状态的具体详情
     * @param $stateId
     * @return $this|array|bool|null
     */
    public function getDetailByStateId($stateId){
        if(empty($stateId)){
            return null;
        }else{
            $buyerPic = new self();
            $state = $buyerPic->addWhere('id', $stateId)->addWhere('status', 1)->select();
            if(empty($state)){
                return null;
            }else{
                $state = $state->getData();
                $state['imgs'] = empty($state['imgs'])?null:json_decode($state['imgs']);
                return $state;
            }
        }
    }

    /**
     * 根据状态id列表生成详细信息
     * @param $stateIdList
     * @return array
     */
    public function genStateDetailOfStateList($stateIdList){
        if(count($stateIdList)<=0){
            return array();
        }
        $ret = array();
        $stateList = (new self())->addWhere('id',$stateIdList, 'in')->addWhere('status','1')->find();
        foreach($stateList as $state){
            $data = $state->getData();
            $data['imgs'] = $data['imgs'] ? json_decode($data['imgs']) : '[]';
            $data['type'] = 2;
            $data['lastTime'] = time()-$state->mUpdateTime;
            $ret[$state->mId] = $data;
        }
        return $ret;
    }
}