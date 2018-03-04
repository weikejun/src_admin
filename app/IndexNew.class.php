<?php
class IndexNew extends Base_Index_New{

    public static function getChannelDesc(){
        return array(
            "1" => "首页频道",
            "2" => "买手频道",
        );
    }

    /**
     * 首页直播频道
     */
    const LIVE  = 1;

    /**
     * 买手频道
     */
    const BUYER = 2;

    /**
     * 首页的banner列表
     * @param $pageId
     * @param $count
     * @return array
     */
    public function getLiveBanner($pageId, $count){
        return $this->getBanner(self::LIVE,$pageId,$count);
    }

    /**
     * 买手频道
     * @param $pageId
     * @param $count
     * @return array
     */
    public function getBuyerChannelBanner($pageId,$count){
        return $this->getBanner(self::BUYER,$pageId,$count);
    }

    /**
     * 获取banner的抽象类
     * @param $channel
     * @param $pageId
     * @param $count
     * @return array
     */
    protected function getBanner($channel, $pageId, $count){
        if($pageId < 0 || $pageId > 10 || $count >= 20 || $count < 0){
            return array();
        }
        $offset = $pageId * $count;
        $news=(new self())->addWhere('channel',$channel)->addWhere("valid","valid")->orderBy("order","desc")->orderBy("update_time","desc")->limit($offset,$count)->find();
        $news=array_map(function($new){
            $data=$new->getData();
            if( empty($data['url']) ) {
                switch($data['type']){
                    case "live":
                        $data['url'] = "AMCustomerURL://livedetail?id=".$data['model_id']."&title=".$data['title'];
                        break;
                    case "buyer":
                        $data['url'] = "AMCustomerURL://buyerdetail?buyer_id=".$data['model_id']."&title=".$data['title'];
                        break;
                    case "stock":
                        $data['url'] = "AMCustomerURL://stockdetail?stock_id=".$data['model_id']."&title=".$data['title'];
                        break;
                    default:
                        break;
                }
            }
            $data['imgs']=json_decode($data['imgs'],true);
            $data['imgs6']=json_decode($data['imgs6'],true);
            return $data;
        },$news);
        return $news;
    }
} 
