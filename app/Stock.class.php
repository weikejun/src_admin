<?php

class Stock extends Base_Stock{
    public static function getAllStatus(){
        return array(
            ['not_verify',"审核驳回"],
            ['verifying',"审核中"],
            ['verified',"审核通过"],
        //    ['canceled',"已撤销"],
        );
    }

    public static function getCurrencyUnit() {
        return [
           ['AUD', '澳元(AUD)'],
           ['HKD', '港币(HKD)'],
           ['GBP', '英镑(GBP)'],
           ['USD', '美元(USD)'],
           ['JPY', '日元(JPY)'],
           ['EUR', '欧元(EUR)'],
           ['KRW', '韩元(KRW)'],
           ['THB', '泰铢(THB)'],
           ['CNY', '人民币(CNY)'],
           ['SGD', '新币(SGD)'],
           ['MYR', '令吉(MYR)'],
           ['CAD', '加拿大元(CAD)'],
           ['NPR', '尼泊尔卢比(NPR)'],
           ['NZD', '新西兰元(NZD)'],
        ];
    }
    
    public static function calcCombinedValues($sku_meta){
        $combine_values;
        foreach($sku_meta as $key => $values){
            if(!$values||count($values)==0){
                continue;
            }
            if(!$combine_values){
                $combine_values=$values;
                continue;
            }
            $new_combine_values=[];
            foreach($values as $value){
                foreach($combine_values as $combine_value){
                    $new_combine_values[]="$combine_value\t$value";
                }
            }
            $combine_values=$new_combine_values;
        }
        return $combine_values;
    }

    /**
     * 根据商品id列表返回商品详情
     * @param $stockIdList
     * @return array
     */
    public function genStockDetailOfStockList($stockIdList){
        if(count($stockIdList) <=0){
            return array();
        }
        $ret = array();
        $stockList = $this->addWhere('id',$stockIdList,'in')->findMap('id');

        $sortStockList = array();
        foreach($stockIdList as $stockId){
            $sortStockList []= $stockList[$stockId];
        }
        $stockList = array_filter($sortStockList);

        foreach($stockList as $stock){
            $data = $stock->getData();
            $data['imgs'] = $data['imgs'] ? json_decode($data['imgs'],true) : null;
            if(isset($data['sku_meta'])) {
                $meta = json_decode($data['sku_meta']);
                $meta_arr = [];
                $i = 0;
                foreach($meta as $key=>$val){
                    $meta_arr[$i++] = ['meta'=>$key, 'value'=>$val];
                }
                $data['sku_meta'] = $meta_arr;
            }
            if(isset($data['rate_tags'])){
                $data['rate_tags'] = (!empty($data['rate_tags']))?json_decode($data['rate_tags'],true):null;
            }
            $data['limit_num'] = 1;
            $data['type'] = 1;
            $data['lastTime'] = time()-$stock->mUpdateTime;

            //@alin
            //如果 售价 >=国内售价，则不显示国内售价
            if($data['priceout']>$data['original_price']){
                $data['original_price']=null;
            }

            $ret [$stock->mId] = $data;
        }
        return $ret;
    }

    /**
     * 根据stockId获取商品信息(sku通过stockAmount，buyer_name,order_num,buyer_info)
     * @param $stockId
     * @return array
     */
    public function getStockInfoByStockId($stockId){
        $stock=new self();
        $stock->setAutoClear(false);
        $stock=$stock->addWhere('id',$stockId)->addWhere('valid','valid')->select();
        if(empty($stock)){
            return array();
        }

        $skuMetaMapTmp = json_decode($stock->mSkuMeta, true);
        $skuMetaMap = [];
        $skuMetaOrder = 0;
        foreach($skuMetaMapTmp as $skuKey => $skuValues) {
            $skuMetaMap[$skuMetaOrder++] = array_flip($skuValues);
        }
        $data = $stock->getData();

        if(isset($data['imgs'])) $data['imgs'] = json_decode($data['imgs'],true);
        if($data['imgs']){
            $data['imgs_meta']=array_map(function($file){
                return ImageMagick::size($file);
            },$data['imgs']);
        }
        if(isset($data['sku_meta'])) {
            $meta = json_decode($data['sku_meta'],true);
            $meta_arr = [];
            $i = 0;
            foreach($meta as $key=>$val){
                $meta_arr[$i++] = ['meta'=>$key, 'value'=>$val];
            }
            $data['sku_meta'] = $meta_arr;
        }
        if(isset($data['rate_tags'])){
            $data['rate_tags'] = (!empty($data['rate_tags']))?json_decode($data['rate_tags'],true):null;
        }
        $data['pay_unit']=$stock->mPriceoutUnit;
        $data['pay_show']=TableUtils::getUnitShow($stock->mPriceoutUnit);
        $data['prepay'] = GlobalMethod::countPrepay($stock->mPriceout,GlobalMethod::ALL_PAY_SWITCH);

        $data['limit_num'] = 1;
        //todo:这边的分享标题需要和全款的开关关联在一起
        $data['share_title'] = array(
            'wechat' => '首付'.$data['prepay'].'就能带回家',
            'wechat_moments' => '首付'.$data['prepay'].'就能带回家',
            'qzone' => '首付'.$data['prepay'].'就能带回家',
            'weibo' => '现在就去海外血拼，首付'.$data['prepay'].'就能带回家',
        );
        //@alin
        //如果 售价 >=国内售价，则不显示国内售价
        if($data['priceout']>$data['original_price']){
            $data['original_price']=null;
        }
        
        return $data;
    }

    /**
     * 获取原生的商品信息
     * @param $stockId
     * @return array
     */
    public function getBaseInfoByStockId($stockId){
        if(empty($stockId)){
            return array();
        }else{
            $ret = (new self())->addWhere('id', $stockId)->select();
            if(!empty($ret)){
                $ret = $ret->getData();
            }
            return $ret;
        }
    }

    /**
     * 商品评价列表
     */
    public static function getStockTagList(){
        return array(
            "颜色正",
            "商品很赞",
            "质量好",
            "尺寸无偏差",
            "款式好",
            "看起来很好",
        );
    }

    //将stock对象转换成array数据
    public static function getDataFromObject($stock) {
        if(empty($stock)) return false;

        if(is_object($stock)) {
            $stock = $stock->getData();
        }
        $stock['imgs'] = is_array($stock['imgs']) ? $stock['imgs'] : json_decode($stock['imgs'], true);
        $stock['sku_meta'] = is_array($stock['sku_meta']) ? $stock['sku_meta'] : json_decode($stock['sku_meta'], true);
        $stock['rate_tags'] = is_array($stock['rate_tags'])? $stock['rate_tags'] : json_decode($stock['rate_tags'],true);
        $stock['tags'] = is_array($stock['tags'])? $stock['tags'] : json_decode($stock['tags'],true);

        return $stock;
    }

    /**
     * 商品下架
     * @param $stockIdList
     * @return bool
     */
    public function offShelf($stockIdList){
        if(count($stockIdList) == 0){
            return true;
        }else{
            //1. 商品下架
            $this->addWhere('id',$stockIdList,'in')->update(array(
                'onshelf' => 0
            ));
            //2. 图墙下架
            (new StockBook())->offWall($stockIdList);
            return true;
        }
    }

}
