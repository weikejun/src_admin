<?php
class StockController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new BuyerLoginInterceptor());
    }
    public function listAction(){
        $live_id=$this->_GET("live_id",null,00001);
        $live=new Live();
        $live=$live->addWhere("id",$live_id)->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$live){
            return ['json:',AppUtils::returnValue(['no live'],10002)];
        }
        $stock=new Stock();
        $stocks=$stock->addWhere('live_id',$live->mId)->find();
        $stocksData=array_map(function($stock){
            $data=$stock->getData();
            $data['imgs']=json_decode($data['imgs'],true);
            $data['imgs']=is_array($data['imgs'])?$data['imgs']:[];
            $stockComment=new StockComment();
            $data['comment_count']=$stockComment->addWhere('stock_id',$stock->mId)->count();
            $order=new Order();
            $data['order_count']=$order->addWhere('stock_id',$stock->mId)->count();
            return $data;
        },$stocks);
        return ['json:',AppUtils::returnValue(['stocks'=>$stocksData,'live'=>$live->getData()],0)];
    }

    //获取live下面的商品和说说
    //by boshen@20141228
    public function listNewAction(){
        $live_id=$this->_GET("live_id",null,00001);
        $status = $this->_GET("status", "verified");
        $pageId = $this->_GET('page', 1);
        $perPage = $this->_GET('perPage', 20);
        $offset = $perPage * ($pageId - 1);

        $live=new Live();
        $live=$live->addWhere("id",$live_id)->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$live){
            return ['json:',AppUtils::returnValue(['no live'],10002)];
        }

        $stock_live = new LiveStock();
        $relates=$stock_live->addWhere('live_id',$live->mId)->addWhere('status', $status)->orderBy('id', 'DESC')->limit($offset, $perPage)->find();
        $stock_ids = $pic_ids = array();
        foreach($relates as $k=>$relate) {
            $relates[$k] = $relate = $relate->getData();
            if( 1 == $relate['stock_type'] ) {
                $stock_ids[] = $relate['stock_id'];
            } else {
                $pic_ids[] = $relate['stock_id'];
            }
        }

        $stocks = $pics = array();
        if( count($stock_ids) > 0 ) {
            $stock=new Stock();
            $stocks = $stock->addWhere('id', $stock_ids, 'in')->findMap('id');
        }
        if( count($pic_ids) > 0 ) {
            $pic = new BuyerPic();
            $pics = $pic->addWhere('id', $pic_ids, 'in')->findMap('id');
        }

        $list = array();
        foreach($relates as $relate) {
            if( 1 == $relate['stock_type'] ) {
                $stock = $stocks[$relate['stock_id']];
            } else {
                $stock = $pics[$relate['stock_id']];
            }
            $stock = empty($stock) ? null : $stock->getData();
            $stock['imgs'] = json_decode($stock['imgs'], true);
            $stock['stock_type'] = $relate['stock_type'];
            $list[] = $stock;
        }

        $data = array( 'stocks'=>$list, 'live'=>$live->getData() );
        return ['json:',AppUtils::returnValue($data, 0)];
    }

    //买手版：获取买手在架的商品列表，可分页
    //by boshen@20141202
    public function getOnShelfListAction(){
        $buyer_id = Buyer::getCurrentBuyer()->mId;

        $pageId=$this->_GET("pageId",1);
        $perpage = $this->_GET('perPage', 20);
        $offset = $perpage * ($pageId - 1);

        $stock=new Stock();
        $stock_num=$stock->addWhere('buyer_id',$buyer_id)->addWhere('onshelf', 1)->count();
        $stocks=$stock->addWhere('buyer_id',$buyer_id)->addWhere('onshelf', 1)->orderBy('id', 'DESC')->limit($offset, $perpage)->find();
        $stocksData=array_map(function($stock){
            $data=$stock->getData();
            $data['imgs']=json_decode($data['imgs'],true);
            $data['imgs']=is_array($data['imgs'])?$data['imgs']:[];
            return $data;
        }, $stocks);

        $pageInfo = $this->_PAGE($stock_num, $pageId, $perpage);

        return ['json:',AppUtils::returnValue(['stocks'=>$stocksData, 'PageInfo'=>$pageInfo],0)];
    }

    public function showAction(){
        $stock=new Stock();
        //$stock=$stock->addWhere('id',$this->_GET('id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        //由于客服后台需要看到具体的商品详情，所以这里read不做权限限制 by @boshen
        $stock=$stock->addWhere('id',$this->_GET('id','',99999))->select();
        if(!$stock){
            return ['json:',AppUtils::returnValue(['no this stock'],99999)];
        }
        $stockData=$stock->getData();
        $stockData['sku_meta']=json_decode($stockData['sku_meta'],true);
        $stockData['sku_meta']=is_array($stockData['sku_meta'])?$stockData['sku_meta']:[];
        $stockData['imgs']=json_decode($stockData['imgs'],true);
        $stockData['imgs']=is_array($stockData['imgs'])?$stockData['imgs']:[];
        $stockData['tags']=json_decode($stockData['tags'],true);
        
        $stockComment=new StockComment();
        $stockData['comment_count']=$stockComment->addWhere('stock_id',$stock->mId)->count();
        $order=new Order();
        $stockData['order_count']=$order->addWhere('stock_id',$stock->mId)->count();
        
        
        $stockAmount=new StockAmount();
        $stockAmounts=$stockAmount->addWhere("stock_id",$stock->mId)->find();
        return ['json:',AppUtils::returnValue([
            'stock'=>$stockData,
            'stockAmounts'=>array_map(function($stockAmount){
                $data=$stockAmount->getData();
                return $data;
            },$stockAmounts),
        ],0)];
    }
    public function deleteAction(){
        $stock=new Stock();
        $stock=$stock->addWhere('id',$this->_POST('id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$stock){
            return ['json:',AppUtils::returnValue(['no this stock'],99999)];
        }
        if($stock->mStatus=='not_verify'){
            $stock->delete();
            /** 同步stock到live_stock表 **/
            {
                $stockData = $stock->getData();
                //创建stock的时候同步数据到live_stock表
                $liveStock = new LiveStock();
                $liveStock->stockSynLiveStock($stockData, 'delete');
                //删除商品时同步到stockBook
                StockBook::getInstance()->stockSyncBook($stockData,'delete');
            }
            /**同步stock的update操作到live_stock表 end**/
            $stockAmount=new StockAmount();
            $stockAmount->addWhere("stock_id",$stock->mId)->delete();
            return ['json:',AppUtils::returnValue([$stock->mId],0)];
        }else{
            return ['json:',AppUtils::returnValue(['only not verified stock can be delete'],99999)];
        }
        
        
    }
    public function applyAction(){
        $stock=new Stock();
        $stock=$stock->addWhere('id',$this->_POST('id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$stock){
            return ['json:',AppUtils::returnValue(['no this stock'],99999)];
        }
        if($stock->mStatus=='not_verify'){
            $stock->mStatus='verifying';
            $stock->save();

            /**同步stock状态到live_stock的状态 start**/
            /** 同步stock到live_stock表 **/
            {
                $stockData = $stock->getData();
                //创建stock的时候同步数据到live_stock表
                $liveStock = new LiveStock();
                $liveStock->stockSynLiveStock($stockData, 'update');
                //申请的时候同步到图墙
                StockBook::getInstance()->stockSyncBook($stockData,'update');
            }
            /**同步stock的update操作到live_stock表 end**/
            /**同步stock状态到live_stock的状态 end**/
            return ['json:',AppUtils::returnValue([$stock->mId],0)];
        }else{
            return ['json:',AppUtils::returnValue(['only not verified stock can be apply'],99999)];
        }
    }
    //sku_meta:{'颜色'=>['红','黄','蓝'],'尺寸'=>['大','中','小']}
    //sku:{"红\t大":1,.....}
    //买手版：新发商品Stock的api by boshen@20141202
    //新增tags
    public function createAction(){
        $stock=new Stock();
        $data=[];
        foreach(['live_id','name','brand','pricein','pricein_unit','priceout','priceout_unit','imgs','note','sku_meta','sku', 'tags', 'onshelf', 'original_price'] as $field){
            if(isset($_POST[$field])){
                $data[$field]=$_POST[$field];
            }
        }
        //要把进货价格直接当成卖价
        if ($_SERVER['HTTP_VERSIONID']>='2.1.0') {
            $data['priceout'] = $data['pricein'];
            $data['priceout_unit'] = $data['pricein_unit'];
            $data['pricein'] = 0;
        }
        $stock->setData($data);
        $now_time = time();
        $stock->mCreateTime=$now_time;
        $stock->mUpdateTime=$now_time;
        $stock->mFlowTime=$now_time;
        //这个状态后续维护放在live_stock表里面，stock表的这个字段慢慢废弃掉，不得再读取这个字段
        //by boshen@20141202
        $stock->mStatus='verifying';
        $stock->mBuyerId=Buyer::getCurrentBuyer()->mId;
        $ret=$stock->save();
        if(!$ret){
            return ['json:',AppUtils::returnValue(['create stock fail'],99999)];
        }

        /** 同步stock到live_stock表 **/
        {
            $stockData = $stock->getData();
            //创建stock的时候同步数据到live_stock表
            $liveStock = new LiveStock();
            $liveStock->stockSynLiveStock($stockData, 'insert');
        }

        //如果默认上架的话，需要在StateRank里面同步记录。确保能在买手个人页展示出来
        //by boshen@20141202
        if( $stock->mOnshelf ) {
            $state_rank = new StateRank();
            $row = array(
                'buyer_id' => $stock->mBuyerId,
                'type' => 1,
                'state_id' => $stock->mId,
                'create_time' => $now_time,
                'update_time' => $now_time
            );
            $rank_id = $state_rank->insert($row);
        }

        $sku_meta=json_decode($data['sku_meta'],true);
        $sku=json_decode($_POST['sku'],true);
        if(!$sku||!$sku_meta){
            return ['json:',AppUtils::returnValue(['sku parse error'],99999)];
        }
        $combine_values=Stock::calcCombinedValues($sku_meta);
        
        $stockAmountsData=[];
        foreach($combine_values as $combine_value){
            $stockAmount=new StockAmount();
            $stockAmount->mStockId=$stock->mId;
            $stockAmount->mSkuValue=$combine_value;
            $stockAmount->mAmount=$sku[$combine_value]?$sku[$combine_value]:1;
            $stockAmount->mCreateTime=time();
            $stockAmount->save();
            $stockAmountsData[]=$stockAmount->getData();
        }
        return ['json:',AppUtils::returnValue([
            'stock'=>$stock->getData(),
            'stockAmounts'=>$stockAmountsData,
        ],0)];
        
    }

    public function combineValuesAction(){
        $sku_meta=$this->_GET('sku_meta',"",99999);
        $sku_meta=json_decode($sku_meta,true);
        if(!$sku_meta){
            return ['json:',AppUtils::returnValue(['sku parse error'],99999)];
        }
        return ['json:',AppUtils::returnValue(Stock::calcCombinedValues($sku_meta),0)];
        
    }
    public function updateAction(){
        $stock=new Stock();
        $stock=$stock->addWhere('id',$this->_POST('id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$stock){
            return ['json:',AppUtils::returnValue(['no this stock'],99999)];
        }
        $now_time = time();

        //新版商品买手可以自由修改，不受限制
        //by boshen@20141202
        $data = array();
        foreach(['name','brand','pricein','pricein_unit','priceout','priceout_unit','imgs','note','sku_meta','tags', 'original_price'] as $field){
            if(isset($_POST[$field])){
                $data[$field]=$_POST[$field];
            }
        }
        //要把进货价格直接当成卖价
        if ($_SERVER['HTTP_VERSIONID']>='2.1.0') {
            $data['priceout'] = $data['pricein'];
            $data['priceout_unit'] = $data['pricein_unit'];
            $data['pricein'] = 0;
        }

        //兼容老业务，审核失败重新编辑之后修改status
        //by boshen
        if( $stock->mStatus == 'not_verify' ) {
            $stock->mStatus = 'verifying';
        }

        $stock->setDataMerge($data);
        $stock->mUpdateTime=$now_time;
        $ret=$stock->save();
        
        if(!$ret){
            return ['json:',AppUtils::returnValue(['update stock failed'],99999)];
        }

        /** 同步stock到live_stock表 **/
        {
            $stockData = $stock->getData();
            //创建stock的时候同步数据到live_stock表
            $liveStock = new LiveStock();
            $liveStock->stockSynLiveStock($stockData, 'update');
            //将下架商品同步到bookStock
            StockBook::getInstance()->stockSyncBook($stockData,'update');
        }
        /**同步stock的update操作到live_stock表 end**/

        $stockAmount=new StockAmount();
        $stockAmounts=$stockAmount->addWhere("stock_id",$stock->mId)->find();
        $sku=json_decode($_POST['sku'],true);
        foreach($stockAmounts as $stockAmount){
            if(isset($sku[$stockAmount->mSkuValue]) ){
                if($stockAmount->mAmount!=$sku[$stockAmount->mSkuValue]){
                    $stockAmount->mAmount=intval($sku[$stockAmount->mSkuValue]);
                    $stockAmount->save();
                }
                unset($sku[$stockAmount->mSkuValue]);
            }
        }
        foreach($sku as $skuValue=>$amount){
            $stockAmount=new StockAmount();
            $stockAmount->mStockId=$stock->mId;
            $stockAmount->mSkuValue=$skuValue;
            $stockAmount->mAmount=$amount;
            $stockAmount->mCreateTime=$now_time;
            $stockAmount->save();
        }

        return ['json:',AppUtils::returnValue([],0)];
    }

    public function setOnAction(){
        $stock=new Stock();
        $stock=$stock->addWhere('id',$this->_POST('id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$stock){
            return ['json:',AppUtils::returnValue(['no this stock'],99999)];
        }
        $onshelf=$this->_POST('onshelf',1);
        $onshelf=$onshelf==1?$onshelf:0;
        $stock->mOnshelf=$onshelf;
        $stock->save();
        return ['json:',AppUtils::returnValue([],0)];
    }
    public function uploadImgsAction(){
        $paths=FileUtil::uploadFile(isset($_FILES['imgs_file'])?$_FILES['imgs_file']:$_POST['imgs_file'],PUBLIC_IMAGE_BASE,["png",'jpg','jpeg','gif'],PUBLIC_IMAGE_URI);
        if(!$paths){
            return ['json:',AppUtils::returnValue(['upload error'],99999)];
        }
        return ['json:',AppUtils::returnValue($paths,0)];
        
    }
    public function delImgAction(){
        $stock=new Stock();
        $stock=$stock->addWhere('id',$this->_POST('id','',99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$stock){
            return ['json:',AppUtils::returnValue(['no this stock'],99999)];
        }

        $imgs=json_decode($stock->mImgs,true);
        $path=$this->_POST('path',"",99999);
        $imgs=array_values(array_filter($imgs,function($img)use($path){
            return $img!=$path;
        }));
        $stock->mImgs=json_encode($imgs);
        $stock->save();
        return ['json:',AppUtils::returnValue($imgs,0)];
    }
    public function unitsAction() {
        $units = Stock::getCurrencyUnit();
        $unitMap = [];
        foreach($units as $unit) {
            $unitMap[$unit[0]] = $unit[1];
        }
        return ['json:',AppUtils::returnValue(['units' => $unitMap],0)];
    }

    //买手版api
    //params: pageId/perPage/Shelf
    //获取卖家的商品列表 by boshen@20141202
    public function getStockListAction() {
        $buyer_id = Buyer::getCurrentBuyer()->mId;
        //默认为1上架商品，0为下架商品
        $shelf = $this->_GET('shelf', 1);
        $shelf = 1==$shelf ? 1 : 0;
        $pageId = $this->_GET('pageId', 1);
        $pageId = $pageId >= 0 ? intval($pageId) : 1;
        $perpage = $this->_GET('perPage', 20);
        $perpage = $perpage >= 0 ? intval($perpage) : 20;
        $offset = $perpage * ($pageId - 1);

        $stock = new Stock();
        $allCount = $stock->addWhere('buyer_id', $buyer_id)->addWhere('onshelf', $shelf)->count();
        $list = $stock->addWhere('buyer_id', $buyer_id)->addWhere('onshelf', $shelf)->orderBy('id', 'DESC')->limit($offset, $perpage)->find();
        //转换成array返回
        $list=array_map(function($stock){
            $data=$stock->getData();
            $data['imgs']=json_decode($data['imgs'],true);
            $data['imgs']=is_array($data['imgs'])?$data['imgs']:[];
            return $data;
        },$list);

        $pageInfo=$this->_PAGE($allCount, $pageId, $perpage);
        $data = array( 'Stocks'=>$list, 'pageInfo'=>$pageInfo );

        //如果page=1 则顺带获取买手正在进行的直播
        if( 1 == $pageId ) {
            $live=new Live();
            $now_time = time();
            //$now_time = 1408012238;
            //获取live列表
            $list = $live->addWhere('buyer_id', $buyer_id)->addWhere("valid","valid")->addWhere('start_time', $now_time, '<=')->addWhere('end_time', $now_time, '>=')->find();
            $live_ids = $stock_ids = array();
            foreach($list as $v) {
                $live_ids[] = $v->mId;
            }
            if( !empty($live_ids) ) {
                $livestock=new LiveStock();
                $live_stocks = $livestock->addWhere("live_id",$live_ids, 'in')->addWhere('status', 'verified')->addWhere('stock_type', 1)->find();
                $live_stock_nums = $live_order_nums = array();
                foreach( $live_stocks as $live_stock ) {
                    $live_stock_nums[$live_stock->getData('live_id')]++;
                    $stock_ids[] = $live_stock->getData('stock_id');
                }
                if( !empty($stock_ids) ) {
                    $order = new Order();
                    $orders = $order->addWhere("stock_id", $stock_ids, 'in')->find();
                    foreach( $orders as $order ) {
                        $live_order_nums[$order->getData('live_id')]++;
                    }
                }

                foreach($list as $k=>$v) {
                    $v = $v->getData();
                    $v['imgs'] = json_decode($v['imgs'], true);
                    $v['stock_count'] = intval($live_stock_nums[$v['id']]);
                    $v['order_count'] = intval($live_order_nums[$v['id']]);
                    $list[$k] = $v;
                }
            }
            $data['Lives'] = $list;
        }

        return ['json:',AppUtils::returnValue($data)];
    }

    //修改商品的上下架状态
    public function changeStockShelfAction() {
        //商品ID
        $stock_id = $this->_POST('stockId', 0);
        //默认下架
        $onshelf = $this->_POST('shelf', 0);
        $onshelf = 1==$onshelf ? 1 : 0;

        $stock = new Stock();
        $stock_info = $stock->addWhere('id', $stock_id)->limit(1)->select();
        if( !$stock_info ) {
            return ['json:',AppUtils::returnValue(['no this stock'],99999)];
        }
        $buyer_id = Buyer::getCurrentBuyer()->mId;
        if( $stock_info->getData('buyer_id') != $buyer_id ) {
            return ['json:',AppUtils::returnValue(['auth error'],99999)];
        }

        $data = array('shelf'=>$onshelf);
        if( $stock_info->getData('onshelf') != $onshelf ) {
            $now_time = time();
            $state_rank = new StateRank();
            $ret = $state_rank->addWhere('state_id', $stock_id)->addWhere('type', 1)->limit(1)->select();
            if( empty($ret) && 1 == $onshelf ) {
                $row = array(
                    'buyer_id' => $buyer_id,
                    'type' => 1,
                    'state_id' => $stock_id,
                    'create_time' => $now_time,
                    'update_time' => $now_time
                );
                $id = $state_rank->insert($row);
            } elseif( !empty($ret) ) {
                $id = $ret->mId;
                $ret = $state_rank->addWhere('id', $id)->limit(10)->update(array('status' => $onshelf, 'update_time' => $now_time));
            }
            $stock_info->mOnshelf = $onshelf;
            $stock_info->mUpdateTime = $now_time;
            $stock_info->save();

            $data['id'] = $id;
        }

        return ['json:',AppUtils::returnValue($data)];
    }

    //新发商品获取tags，后端配置（灵活）
    //by boshen@20141202
    public function getStockTagsAction() {
        $tags = array( '服饰', '鞋包', '珠宝首饰', '美容', '清仓', '生活保健', '母婴' );

        $data = array( 'tags' => $tags );
        return ['json:',AppUtils::returnValue($data)];
    }

    //选择现有的商品加入到直播中，等待审核
    //by boshen@20141203
    public function addStockToLiveAction() {
        $buyer_id = Buyer::getCurrentBuyer()->mId;

        $stock_ids = $this->_POST('stockIds', '');
        $stock_ids = empty($stock_ids) ? array() : explode(',', $stock_ids);
        $live_id = $this->_POST('liveId', 0);
        if(empty($live_id) || empty($stock_ids)) {
            return ['json:',AppUtils::returnValue(['params empty'], 10001)];
        }

        //开始权限检查，确认没问题
        $live = new Live();
        $live_info = $live->addWhere('id', $live_id)->limit(1)->select();
        if(empty($live_info) || $live_info->getData('status') != 'verified') {
            return ['json:',AppUtils::returnValue(['empty live'], 50023)];
        }
        if( $buyer_id != $live_info->getData('buyer_id') ) {
            return ['json:',AppUtils::returnValue(['auth error'], 50024)];
        }

        $stock = new Stock();
        $stocks = $stock->addWhere('id', $stock_ids, 'in')->find();
        foreach($stocks as $stock) {
            if( $stock->getData('buyer_id') != $buyer_id || !$stock->getData('onshelf') ) {
                return ['json:',AppUtils::returnValue(['auth error'], 50024)];
            }
        }

        //保存对应关系
        $now_time = time();
        $live_stock = new LiveStock();
        $live_stocks = $live_stock->addWhere('stock_id', $stock_ids, 'in')->addWhere('live_id', $live_id)->findMap('stock_id');
        foreach($stock_ids as $k=>$stock_id) {
            if( !isset($live_stocks[$stock_id]) ) {
                $row = array(
                    'live_id' => $live_id,
                    'stock_id' => $stock_id,
                    'status' => 'verifying',
                    'flow_time' => $now_time,
                    'sell_time' => $now_time,
                    'create_time' => $now_time,
                    'update_time' => $now_time
                );
                $live_stock_id = $live_stock->insert($row);
            }
        }

        $data = array('stockIds'=>$stock_ids, 'liveId'=>$live_id);
        return ['json:', AppUtils::returnValue($data)];
    }

    //新的商品图片上传接口
    //支持顺序传递 @by boshen@20141219
    public function uploadStockImgsAction(){
        $paths=FileUtil::uploadFile(isset($_FILES['imgs_file'])?$_FILES['imgs_file']:$_POST['imgs_file'],PUBLIC_IMAGE_BASE,["png",'jpg','jpeg','gif'],PUBLIC_IMAGE_URI);
        if(!$paths){
            return ['json:',AppUtils::returnValue(['upload error'],99999)];
        }

        $data = array( 'num'=>$this->_POST('num'), 'path'=> $paths[0] );
        return ['json:',AppUtils::returnValue($data, 0)];
    }
}
