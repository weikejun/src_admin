<?php
class LiveController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new BuyerLoginInterceptor());
    }
    public function listAction(){
        $buyer=Buyer::getCurrentBuyer();
        $live=new Live();
        if($this->_GET('last_id',"")){
            $live->addWhere('id',intval($this->_GET('last_id',"0")),'<');
        }
        $lives=$live->addWhere('buyer_id',$buyer->mId)->orderBy("start_time","desc")->addWhere("valid","valid")->limit(50)->find();
        $data=array_map(
            function($live) use($buyer){
                $d=$live->getData();
                $d['brands']=json_decode($d['brands'],true);
                $d['brands']=$d['brands']?$d['brands']:[];
                $d['imgs']=json_decode($d['imgs'],true);
                $d['imgs']=$d['imgs']?$d['imgs']:[];

                $stock=new Stock();
                $d['stock_count']=$stock->addWhere("live_id",$live->mId)->addWhere('buyer_id',$buyer->mId)->count();
                $order=new Order();
                $orders=$order->addWhere("live_id",$live->mId)->find();
                /*
                //修改获取商品数的数据源，改从live_stock中获取 by boshen@20141202
                $livestock=new LiveStock();
                $stock_ids = array();
                $live_stocks = $livestock->addWhere("live_id",$live->mId)->addWhere('status', 'verified')->addWhere('stock_type', 1)->find();
                $d['stock_count'] = count($live_stocks);
                foreach( $live_stocks as $live_stock ) {
                    $stock_ids[] = $live_stock->getData('stock_id');
                }

                $orders = array();
                if( !empty($stock_ids) ) {
                    //获取订单也改进，后续订单和直播没有必然联系。只能通过stock关联。 by boshen@20141202
                    $order=new Order();
                    $orders=$order->addWhere("stock_id", $stock_ids, 'in')->find();
                }
                 */
                $d['buy_count']=0;
                $d['payed_count']=0;
                $d['order_count']=0;
                $d['prepayed_count']=0;
                $d['wait_pay_count']=0;
                $invalOrderStatus = ['wait_prepay', 'canceled', 'refund', 'returned', 'fail', 'wait_refund', 'timeout', 'pre_canceled'];
                $d['total_cash']=array_reduce($orders,function($res,$order)use(&$d, $invalOrderStatus){
                    if(!in_array($order->mStatus,['wait_prepay','prepayed','refund','canceled','timeout'])){
                        $d['buy_count']++;
                    }
                    if($order->mStatus=='wait_pay'){
                        $d['wait_pay_count']++;
                    }
                    if($order->mStatus=='prepayed'){
                        $d['prepayed_count']++;
                    }
                    if($order->mStatus=='payed'){
                        $d['payed_count']++;
                    }
                    if(!in_array($order->mStatus, $invalOrderStatus)){
                        $res+=$order->mSumPrice;
                    }
                    return $res;
                },0);
                //$d['buy_count']=$buy_count;
                foreach($orders as $order) {
                    if(!in_array($order->mStatus, $invalOrderStatus)) {
                        $d['order_count']++;
                    }
                }
                $this->calcIsOpen($d);
                return $d;
            },$lives);
        return ['json:',AppUtils::returnValue(['lives'=>$data],0)];
    }
    public function createAction(){
        $live=new Live();
        $data=[];
        foreach(['name','country','city','address','intro','brands','imgs','start_time','end_time'] as $field){
            if(isset($_POST[$field])){
                $data[$field]=$_POST[$field];
            }
        }
        $live->setData($data);
        $live->mCreateTime=time();
        $live->mBuyerId=Buyer::getCurrentBuyer()->mId;
        $live->mStatus='verifying';
        $ret=$live->save();
        if(!$ret){
            return ['json:',AppUtils::returnValue(['create live fail'],99999)];
        }
        return ['json:',AppUtils::returnValue([],0)];
    }
    public function updateAction(){
        if(!isset($_POST['id'])){
            return ['json:',AppUtils::returnValue(['no live id'],99999)];
        }
        $live=new Live();
        $live=$live->addWhere('id',$_POST['id'])->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$live){
            return ['json:',AppUtils::returnValue(['no live'],99999)];
        }
        $data=[];
        if($live->mStatus=='not_verify'){
            foreach(['name','country','city','address','intro','brands','imgs','start_time','end_time'] as $field){
                if(isset($_POST[$field])){
                    $data[$field]=$_POST[$field];
                }
            }
            $live->mStatus='verifying';
        }
        if($live->mStatus=='verified'){
            foreach(['intro','brands','imgs'] as $field){
                if(isset($_POST[$field])){
                    $data[$field]=$_POST[$field];
                }
            }
        }
        $live->setDataMerge($data);
        $live->save();
        /*
        $ret=$live->save();
        if(!$ret){
            return ['json:',AppUtils::returnValue(['update live fail'],99999)];
        }*/
        return ['json:',AppUtils::returnValue([],0)];
    }
    public function uploadImgsAction(){
        $paths=FileUtil::uploadFile(isset($_FILES['imgs_file'])?$_FILES['imgs_file']:$_POST['imgs_file'],PUBLIC_IMAGE_BASE,["png",'jpg','jpeg','gif'],PUBLIC_IMAGE_URI);
        if(!$paths){
            return ['json:',AppUtils::returnValue(['upload error'],99999)];
        }
        return ['json:',AppUtils::returnValue($paths,0)];
    }
    public function addImgAction(){
        $paths=FileUtil::uploadFile(isset($_FILES['imgs_file'])?$_FILES['imgs_file']:$_POST['imgs_file'],PUBLIC_IMAGE_BASE,["png",'jpg','jpeg','gif'],PUBLIC_IMAGE_URI);
        if(!$paths){
            return ['json:',AppUtils::returnValue(['upload error'],99999)];
        }
        $imgs=array_merge($imgs,$paths);
        $live->mImgs=json_encode($imgs);
        
        $res=$live->save();
        if($res){
            return ['json:',AppUtils::returnValue($imgs,0)];
        }else{
            return ['json:',AppUtils::returnValue(['save live error'],99999)];
        }
    }
    private static $cityTable=[
        '中国'=>['北京','上海','广州','深圳'],
        '美国'=>['洛杉矶','纽约','华盛顿'],
        '德国'=>['柏林','慕尼黑'],
        '英国'=>['伦敦','曼彻斯特'],
        ];
    public function listCityAction(){
        return self::$cityTable[$this->_GET('country',"","99999")];
    }
    public function listCountryAction(){
        return array_keys(self::$cityTable);
    }
    public function applyAction(){
        $live=new Live();
        $live=$live->addWhere('id',$this->_POST('id',"",99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$live){
            return ['json:',AppUtils::returnValue(['no live'],99999)];
        }
        if($live->mStatus!='not_verify'){
            return ['json:',AppUtils::returnValue(['status wrong'],99999)];
        }
        $live->mStatus='verifying';
        $res=$live->save();
        return ['json:',AppUtils::returnValue([],$res?0:99999)];
    }
    public function showAction(){
        $live=new Live();
        $live=$live->addWhere('id',$this->_GET('id',"",99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        $liveData=$live->getData();
        $liveData['brands']=json_decode($liveData['brands'],true);
        $liveData['brands']=$liveData['brands']?$liveData['brands']:[];
        $liveData['imgs']=json_decode($liveData['imgs'],true);
        $liveData['imgs']=$liveData['imgs']?$liveData['imgs']:[];
        $this->calcIsOpen($liveData);
        return ['json:',AppUtils::returnValue($liveData,0)];
    }
    public function calcIsOpen(&$liveData){
        $now=time();
        if($liveData['status']!='verified'){
            $liveData['is_open']='not_verify';
        }elseif($liveData['start_time']>$now){
            $liveData['is_open']='start';
        }elseif($liveData['end_time']<$now){
            $liveData['is_open']='end';
        }else{
            $liveData['is_open']='living';
        }
    }

    public function deleteAction(){
        $live=new Live();
        $live=$live->addWhere('id',$this->_POST('id',"",99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$live){
            return ['json:',AppUtils::returnValue(['no live'],99999)];
        }
        if($live->mStatus=='not_verify'){
            $live->delete();
        }else{
            $live->mStatus="cancel";
            $res=$live->save();
        }
        return ['json:',AppUtils::returnValue([],0)];
    }
    public function calendarAction(){
        $time=$this->_GET("time");
        if(!$time){
            $time=time();
        }
        list($month,$day,$year)=explode("-",date("n-j-Y",$time));
        $day_start=mktime(0,0,0,$month,$day,$year);
        $day_end=mktime(0,0,0,$month,$day+1,$year)-1;
        
        $live=new Live();
        $lives=$live->addWhereRaw("(start_time>$day_start and start_time<$day_end)or(end_time>$day_start and end_time<$day_end)")->addWhere('valid','valid')->find();
        $results=[];
        foreach(range(0,23)as $i){
            $results[$i]=0;
        }
        foreach($lives as $live){
            $start_time=max($day_start,$live->mStartTime);
            $end_time=min($day_end,$live->mEndTime);
            $start_hour=date("G",$start_time);
            $end_hour=date("G",$end_time);
            for($i=$start_hour;$i<=$end_hour;$i++){
                $results[$i]++;
            }
        }
        return ['json:',AppUtils::returnValue($results,0)];
    }
    public function delImgAction(){
        $live=new Live();
        $live=$live->addWhere('id',$this->_POST('id',"",99999))->addWhere('buyer_id',Buyer::getCurrentBuyer()->mId)->select();
        if(!$live){
            return ['json:',AppUtils::returnValue(['no live'],99999)];
        }
        $imgs=json_decode($live->mImgs,true);
        $path=$this->_POST('path',"",99999);
        $imgs=array_values(array_filter($imgs,function($img)use($path){
            return $img!=$path;
        }));
        $live->mImgs=json_encode($imgs);
        $live->save();
        return ['json:',AppUtils::returnValue($imgs,0)];
    }

    //买手版需求
    //获取买手正在进行的直播列表 by boshen@20141201
    public function getBuyerBeingLivesAction() {
        $buyer = Buyer::getCurrentBuyer();
        $buyer_id = $buyer->mId;
        $live=new Live();
        $now_time = time();
        //$now_time = 1408012238;
        //获取live列表
        $list = $live->addWhere('buyer_id', $buyer_id)->addWhere("valid","valid")->addWhere('start_time', $now_time, '<=')->addWhere('end_time', $now_time, '>=')->addWhere('status', 'verified')->find();
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
                $v['stock_num'] = intval($live_stock_nums[$v['id']]);
                $v['order_num'] = intval($live_order_nums[$v['id']]);
                $list[$k] = $v;
            }
        }

        return ['json:', AppUtils::returnValue($list)];
    }

    //获取直播列表的说说、商品
    //by boshen@20141202
    public function getLiveStockListAction() {
        $live_id = $this->_GET('liveId', 0);
        $live = new Live();
        $live_info = $live->addWhere('id', $live_id)->select();
        if(!$live_info){
            return ['json:',AppUtils::returnValue(['no live'], 50023)];
        }

        if( Buyer::getCurrentBuyer()->mId != $live_info->getData('buyer_id') ) {
            return ['json:',AppUtils::returnValue(['auth error'], 50024)];
        }

        $perpage = $this->_GET('perPage', 20);
        $perpage = $perpage>0 ? intval($perpage) : 20;
        $pageId = $this->_GET('pageId', 1);
        $pageId = $pageId>0 ? intval($pageId) : 1;
        $offset = $perpage * ($pageId - 1);

        $status = $this->_GET('status', 'verified');

        $live_stock = new LiveStock();
        $live_stock_num = $live_stock->addWhere('live_id', $live_id)->addWhere('status', $status)->count();
        $live_stocks = $live_stock->addWhere('live_id', $live_id)->addWhere('status', $status)->orderBy('flow_time', 'DESC')->limit($offset, $perpage)->find();
        $stock_ids = $pic_ids = array();
        foreach($live_stocks as $live_stock) {
            if(1 == $live_stock->getData('stock_type')) {
                $stock_ids[] = $live_stock->getData('stock_id');
            } else {
                $pic_ids[] = $live_stock->getData('stock_id');
            }
        }

        $stocks = $pics = array();
        if( !empty($stock_ids) ) {
            $stock = new Stock();
            $stocks = $stock->addWhere('id', $stock_ids, 'in')->findMap('id');
        }
        if( !empty($pic_ids) ) {
            $buyer_pic = new BuyerPic();
            $pics = $buyer_pic->addWhere('id', $pic_ids, 'in')->findMap('id');
        }

        foreach($live_stocks as $k=>$live_stock) {
            $live_stock = $live_stock->getData();
            if( 1 == $live_stock['stock_type'] ) {
                $live_stock['stock'] = $stocks[$live_stock['stock_id']]->getData();
            } else {
                $pic = $pics[$live_stock['stock_id']]->getData();
                $pic = json_decode($pic['imgs'], true);
                $live_stock['pic'] = $pic;
            }

            $live_stocks[$k] = $live_stock;
        }

        $pageInfo=$this->_PAGE($live_stock_num, $pageId, $perpage);
        $data = array( 'Stocks' => $live_stocks, 'PageInfo' => $pageInfo );
        return ['json:', AppUtils::returnValue($data)];
    }

    //买手在直播中发表说说
    //by boshen@20141202
    public function createLivePicAction() {
        $buyer = Buyer::getCurrentBuyer();
        $buyer_id = $buyer->mId;

        $imgs = $this->_POST('imgs', json_encode(array()));
        $note = $this->_POST('note', '');
        $location = $this->_POST('location', '');

        $live_id = $this->_POST('liveId', 0);
        $live = new Live();
        $live_info = $live->addWhere('id', $live_id)->addWhere('buyer_id', $buyer_id)->limit(1)->select();
        if( empty($live_info) ) {
            return ['json:',AppUtils::returnValue(['live empty'], 99999)];
        }
        if( $live_info->getData('buyer_id') != $buyer_id ) {
            return ['json:',AppUtils::returnValue(['auth error'], 50024)];
        }

        $now_time = time();
        $row = array();
        $row['buyer_id'] = $buyer_id;
        $row['note'] = $note;
        $row['imgs'] = $imgs;
        $row['location'] = $location;
        $row['create_time'] = $now_time;
        $row['update_time'] = $now_time;
        $buyer_pic = new BuyerPic();
        $pic_id = $buyer_pic->insert($row);
        if(!$pic_id) {
            return ['json:',AppUtils::returnValue(['create pic error'], 99999)];
        }

        //添加关联记录
        $live_stock = new LiveStock();
        $live_stock->mLiveId = $live_id;
        $live_stock->mStockId = $pic_id;
        //默认审核通过，可以展示
        $live_stock->mStatus = 'verified';
        $live_stock->mStockType = 2;
        $live_stock->mFlowTime = $now_time;
        $live_stock->mSellTime = $now_time;
        $live_stock->mCreateTime = $now_time;
        $live_stock->mUpdateTime = $now_time;
        $ret = $live_stock->save();
        if(!$ret) {
            return ['json:',AppUtils::returnValue(['pic relate live error'], 99999)];
        }

        $data = array( 'LiveId' => $live_id, 'PicId' => $pic_id );
        return ['json:', AppUtils::returnValue($data)];
    }

}
