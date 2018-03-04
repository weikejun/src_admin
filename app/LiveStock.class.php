<?php
/**
 * Created by PhpStorm.
 * User: dingping
 * Date: 14-11-22
 * Time: 上午10:59
 */
class LiveStock extends Base_Live_Stock{

    /**
     * 从stock表同步数据到liveStock表中
     * @param $stockData
     * @param $action
     * @return bool|mixed
     */
    public function stockSynLiveStock($stockData, $action){
        //liveId为null的时候不处理
        if(empty($stockData['live_id'])){
            return false;
        }

        $ret = false;
        switch($action){
            case "update":
                $liveStock = new LiveStock();
                $live_stock_data = array();
                foreach($liveStock->getFieldList() as $field){
                    if(isset($stockData[$field['name']])){
                        $live_stock_data[$field['name']]=  $stockData[$field['name']];
                    }
                }
                /**先做更新操作,如果没有发现数据，则进行插入操作，不能直接使用save方法**/
                unset($live_stock_data['id']);
                $rowCount = $liveStock->addWhere("stock_id",$stockData['id'])->addWhere("live_id", $live_stock_data['live_id'])->update($live_stock_data);
                if(empty($rowCount)){
                    $live_stock_data['stock_id'] = $stockData['id'];
                    $ret = $liveStock->insert($live_stock_data);
                    if(!$ret){
                        Logger::error("stock syn to live_stock failed: ". var_export($live_stock_data, true).
                            "syned stock: ". var_export($stockData, true)
                        );
                    }
                }
                break;
            case "insert":
                $liveStock = new LiveStock();
                $live_stock_data = array();
                foreach($liveStock->getFieldList() as $field){
                    if(isset($stockData[$field['name']])){
                        $live_stock_data[$field['name']]=  $stockData[$field['name']];
                    }
                }
                /**先做更新操作,如果没有发现数据，则进行插入操作**/
                unset($live_stock_data['id']);
                $live_stock_data['stock_id'] = $stockData['id'];
                $ret = $liveStock->insert($live_stock_data);
                if(!$ret){
                    Logger::error("stock syn to live_stock failed: ". var_export($live_stock_data, true).
                        "syned stock: ". var_export($stockData, true)
                    );
                }
                break;
            case "delete":
                $liveStock = new LiveStock();
                $liveStock->addWhere('stock_id', $stockData['id'])->delete();
                $ret = true;
                break;
            default:
                $ret = false;
                break;
        }
        return $ret;
    }

    /**
     * 获取直播中的商品/状态列表
     * @param $liveId
     * @param $pageId
     * @param $count(最大不超过6个每组)
     * @return array
     */
    public function getStateListOfLive($liveId,$pageId = 0,$count = 4){
        if(empty($liveId)){
            return array();
        }
        if($count >= 6 || $count <=0){
            $count = 6;
        }
        $offset = $pageId * $count;
        $liveStock = new self();
        //todo: 确定是flow_time还是id倒叙
        $stockIdList = array();
        $stateIdList = array();
        $ObjectList = $liveStock->addWhere('live_id',$liveId)->addWhere('status',"verified")->orderBy('id','desc')->limit($offset,$count)->find();
        //商品列表
        $stockIdList = array_map(function($state){
            if($state->mStockType == 1){
                return $state->mStockId;
            }
        },$ObjectList);
        $stockIdList = array_values($stockIdList);
        //状态列表
        $stateIdList = array_map(function($state){
            if($state->mStockType == 2){
                return $state->mStockId;
            }
        },$ObjectList);
        $favor = new Favor();
        //经过加工处理的商品详情
        $stockDetailList = (new Stock())->genStockDetailOfStockList($stockIdList);
        //经过加工处理的状态详情
        $stateDetailList = (new BuyerPic())->genStateDetailOfStateList($stateIdList);
        //用户对商品的喜欢列表
        $stockFavorList = $favor->likeStockList($stockIdList);
        //用户对状态的喜欢列表
        $stateFavorList = $favor->likeStateList($stateIdList);

        //商品/状态详情列表
        $ret = array();
        foreach($ObjectList as $object){
            if($object->mStockType == 1){
                //获取点赞列表
                $stockLikeList = $favor->getStockLikeList($object->mStockId,0,10);
                $tempData = $stockDetailList[$object->mStockId];
                $tempData['likeList'] = $stockLikeList;
                $tempData['followed'] = empty($stockFavorList[$object->mStockId])?false:true;
                $ret []= $tempData;
            }else if($object->mStockType == 2){
                $stateLikeList = $favor->getStateLikeList($object->mStockId,0,10);
                $tempData = $stateDetailList[$object->mStockId];
                $tempData['likeList'] = $stateLikeList;
                $tempData['followed'] = empty($stateFavorList[$object->mStockId])?false:true;
                $ret []= $tempData;
            }else{
            }
        }
        return $ret;
    }

    /**
     * 获取单场直播的所有商品
     * @param $liveId
     * @return array|null
     */
    public function getStockListOfLiveId($liveId){
        if(empty($liveId)){
            return null;
        }else{
            $stockList = $this->addWhere('live_id',$liveId)->find();
            if(!empty($stockList)){
                $stockList = array_map(function($stock){
                    return $stock->getData();
                },$stockList);
            }
            return array_filter($stockList);
        }
    }
}