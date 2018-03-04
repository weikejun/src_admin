<?php

class LiveController extends AppBaseController{
    public function __construct(){
        #$this->addInterceptor(new LoginInterceptor());        
    }
    public function listAction(){
        $pageId=$this->_GET('pageId', 1);
        $count=$this->_GET('count', 20);
        $tp=$this->_GET('tp',1);

        $country=$this->_GET('country',null);
        $province=$this->_GET('province',null);
        $city=$this->_GET('city',null);

        $cache = new Memcache;
        $cache->connect(MEMCACHED_ADDR, MEMCACHED_PORT);
        $cacheKey = "live/list|$pageId|$count|$tp|$country|$province|$city";
        if($result = $cache->get($cacheKey)) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($result)];
        }
        
        $live=new Live();
        $live->setAutoClear(false);
        $live=$live->addWhere('list_show', '1');

        if(!is_null($country)){
            $live=$live->addWhere('country',$country);
        }
        if(!is_null($province)){
            $live=$live->addWhere('province',$province);
        }
        if(!is_null($city)){
            $live=$live->addWhere('city',$city);
        }

        $t = time();
        // 0表示全部，1表示正在直播，2表示结束，3表示直播预告(运营相关，暂时默认为全部)
        switch ($tp){
            case 0:
                $live=$live->addWhere('valid','valid');
                $allCount=$live->count();
                break;
            case 1:
            case 4:
                $live=$live->addWhere('valid','valid')->addWhere('status', 'verified')->addWhere("start_time",$t,'<=')->addWhere("end_time",$t,'>=')->orderBy('start_time', 'DESC');
                $allCount=$live->count();
                break;
            case 2:
                $live=$live->addWhere('valid','valid')->addWhere("end_time",$t,'<');
                $allCount=$live->count();
                break;
            case 3:
                $live=$live->addWhere('valid','valid')->addWhere("status", 'verified')->addWhere("start_time",$t,'>')->orderBy('start_time', 'ASC');
                $allCount=$live->count();
                break;
            default:
                $live=$live->addWhere('valid','valid');
                $allCount=$live->count();
                break;
        }
        $pageInfo=$this->_PAGE($allCount,$pageId,$count);
        $lives=$live->limit(($pageId-1)*$count,$count)->find();
        if($tp == 4) {
            $finder = new Live();
            $exlives=$finder->addWhere('valid','valid')->addWhere('list_show', '1')->addWhere("status",'verified')->addWhere("end_time",$t,'<')->orderBy('end_time', 'DESC')->limit($count - $allCount)->find();
            $lives = array_merge($lives, $exlives);
        }

        $lives = self::genLiveDetail($lives);
        if ($tp != 3) {
            $lives=array_values(array_filter($lives, function($live) {
                /*if(count($live['stock_imgs']) == 0) {
                    return false;
                }*/
                return true;
            }));
        }else{
            $reminderList = array();
            $liveIdList = array_map(function($live){
                 return $live['id'];
            },$lives);
            if(count($liveIdList) >=1){
                $reminderList = (new UserReminder())->getLiveRemindList($liveIdList);
            }
            $lives = array_map(function($live)use($reminderList){
                $live['remindered'] = empty($reminderList[$live['id']])?false:true;
                return $live;
            },$lives);
        }
        $lives=array_filter($lives);

        $cache->set($cacheKey, ["lives"=>array_values($lives),"pageInfo"=>$pageInfo], MEMCACHE_COMPRESSED, 10);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["lives"=>array_values($lives),"pageInfo"=>$pageInfo])];
    }
    public function genLiveDetail($lives){
        if(count($lives) == 0) return $lives;
        $buyerIds=array_map(function ($live){
            return $live->mBuyerId;
        },$lives);
        $liveIds=array_map(function ($live) {
            return $live->mId;
        },$lives);
        
        $buyer = new Buyer();
        $buyerMap = $buyer->addWhere("id",$buyerIds,'in')->findMap("id");
        $flow = new LiveFlow();
        $flowMap = $flow->addWhere('live_id', $liveIds, 'in')->addWhere('status', 1)->groupBy('live_id')->findMap('live_id');
        
        $lives=array_map(
            function($live)use($buyerMap,$flowMap){
                $data = $live->getData();
                $buyer = $buyerMap[$live->mBuyerId];
                if($buyer->mStatus != 'be'){
                    return null;
                }
                $data['buyer_name'] = $buyer->mName;
                $data['buyer_country'] = $buyer->mCountry;
                $data['buyer_head'] = $buyer->mHead;
                //$data['buyer_country_pic'] = $buyer->mCountryPic;
                $data['brands'] = json_decode($data['brands']);
                if(isset($data['brands'])){
                    $data['brands_label'] = implode("/",$data['brands']);
                }else{
                    $data['brands_label'] = '';
                }
                $data['imgs'] = json_decode($data['imgs']);
                $data['dim_imgs'] = json_decode($data['dim_imgs']);
                $data['buyer_level'] = Buyer::level($buyer->mLevel);
                $data['is_flow'] = isset($flowMap[$live->mId]) ? 1 : 0;
                $imgs = $this::genStocksImgs($data['id']);
                shuffle($imgs);
                $data['stock_imgs'] = array_slice($imgs,0,5);
                if(time() <= $data['end_time']) {
                    $data['user_num'] = intval((time() - $data['start_time']) * 0.05);
                    $data['left_time'] = $data['end_time'] - time();
                } else {
                    $data['user_num'] = intval(($data['end_time'] - $data['start_time']) * 0.05 + (time() - $data['end_time']) * 0.005);
                }
                $data['country_flag']=NationalFlag::getUrl($data['country']);
                $data['buyer_country_flag']=NationalFlag::getUrl($buyer->mCountry);
                $data['buyer_easemob_username']=$buyer->mEasemobUsername;
                $data['share_title'] = array(
                    'wechat' => '',
                    'wechat_moments' => '',
                    'qzone' => '',
                    'weibo' => !empty($data['brands'])?'现在就去海外血拼，'.$data['name'].implode("、",$data['brands']).'拿到手软':'现在就去海外血拼，'.$data['name'].'拿到手软'
                );

                $endtime = $live->mEndTime;
                $is_close = 1;
                if($endtime && time()<$endtime){
                    $is_close=0;
                }
                $data['is_close']=$is_close;
                $data['is_open'] = (time() < $live->mStartTime ? 0 : 1);
                if(!$data['is_open']){
                    $data['left_time'] = $live->mStartTime - time();
                }
                if($data['type']){
                    $data['name']="【{$data['type']}】{$data['name']}";
                }
                return $data;
            },
            $lives
        );
        return array_values(array_filter($lives));
    } 
    public function showAction(){
        $id=$this->_GET('id',"1");
        $live=new Live();
        $live=$live->addWhere('id',$id)->addWhere('valid','valid')->select();
        $lives = self::genLiveDetail([$live]);
        if ($this->_GET('os') == 'android') {
            $lives[0] = ['live' => $lives[0]];
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($lives[0])];
    }

    public function stocksAction(){
        $live_id= $this->_GET("id","");
        $count = $this->_GET("count",30);
        $pageId = $this->_GET("pageId",1);
        list($stocks,$allCount) = $this->genStocks($live_id, $pageId, $count);
        $pageInfo=$this->_PAGE($allCount,$pageId,$count);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['stocks'=>$stocks, 'pageInfo'=>$pageInfo])];
    }

    public function flowAction(){
        $live_id= $this->_GET("id","");
        $count = $this->_GET("count",10);
        $pageId = $this->_GET("pageId",1);
        $order = $this->_GET("order",'DESC');
        if($order!='DESC' && $order!='ASC'){
            $order='DESC';
        }
        $onlystock = $this->_GET("onlystock",0); // 1表示是，0表示否,默认是0，表示展示全部
        if($onlystock!=0 && $onlystock!=1){
            $onlystock=0;
        }
        $cache = new Memcache;
        $cache->connect(MEMCACHED_ADDR, MEMCACHED_PORT);
        $cacheKey = "live/flow|$live_id|$pageId|$count|$order|$onlystock";
        if($result = $cache->get($cacheKey)) {
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($result)];
        }
        $live=new Live();
        $live=$live->addWhere('id',$live_id)->addWhere('valid','valid')->select();
        if(!$live){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'live does not exist'],50023)];
        }
        $lives = self::genLiveDetail([$live]);
       
        list($flows,$allCount) = $this->genFlowInfo($live_id, $pageId, $count, $order, $onlystock);
        $pageInfo=$this->_PAGE($allCount,$pageId,$count);
        $cache->set($cacheKey, ['liveInfo'=>$lives[0],'flows'=>$flows, 'pageInfo'=>$pageInfo], MEMCACHE_COMPRESSED, 10);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['liveInfo'=>$lives[0],'flows'=>$flows, 'pageInfo'=>$pageInfo])];
    }

    public function genFlowInfo($live_id, $pageId, $count, $order, $onlystock){
        $stock=new Stock();
        $stock->setAutoClear(false);

        $liveStocks = (new LiveStock())->addWhere('live_id',$live_id)->addWhere('stock_type',1)->addWhere('status','verified')->orderBy('flow_time',$order)->find();
        $stockIdList = array_filter(array_map(function($liveStock){
            return $liveStock->mStockId;
        },$liveStocks));
        //这边的商品逻辑走live_stock表,不再继续走stock表了@dingping 2015-01-06
        $stock->addWhere('id',$stockIdList,'in')->orderBy('flow_time', $order);

        $data=[];
        $stocks=array_map(
            function($stock){
                $data = $stock->getData();
                if($data['onshelf']!=1)
                    return null;
		        $data['imgs'] = $data['imgs'] ? json_decode($data['imgs']) : '[]';
                $meta = json_decode($data['sku_meta']);
                $meta_arr = [];
                $i = 0;
                foreach($meta as $key=>$val){
                    $meta_arr[$i++] = ['meta'=>$key, 'value'=>$val];
                }
                $data['sku_meta'] = $meta_arr;
		        $data['limit_num'] = 1;
                $data['type'] = 1;
                $data['pay_show']=TableUtils::getUnitShow($stock->mPriceoutUnit);
                //$data['flow_time'] = $data['create_time'];
		        return $data;
            },
            $stock->find()
        );
        $stocks=array_values(array_filter($stocks));
        $datas=array();
        foreach($stocks as $stock){
            $data[$stock['flow_time'].sprintf("0%08d", $stock->mId)]=$stock;
        }

        if($onlystock == 0){
            $LiveFlow=new LiveFlow();
            $LiveFlow->setAutoClear(false);
            $LiveFlow->addWhere('live_id',$live_id)->addWhere('status', 1)->orderBy('flow_time', $order);
            $flows=array_map(
                function($flow){
                    $data = $flow->getData();
                    $data['type'] = 0;
		            $data['imgs'] = $data['imgs'] ? json_decode($data['imgs']) : null;
                    unset($data['create_time']);
                    unset($data['update_time']);
		            return $data;
                },
                $LiveFlow->find()
            );
            $flows=array_values(array_filter($flows));

            foreach($flows as $flow){
                $data[$flow['flow_time'].sprintf("1%08d", $flow->mId)]=$flow;
            }
            $order=='DESC' ? krsort($data) : ksort($data);
        }

        $data=array_values($data);
        $show=array();
        $allcount=count($data);
        $c=0;
        $stockIds=array();
        for($i=($pageId-1)*$count;$i<$allcount;$i++){
            $show[$c]=$data[$i];
            if($show[$c]['type']==1){
                array_push($stockIds,$show[$c]['id']);
            }
            $c++;
            if($c==$count)  break;
        }
        $orderNumMap = Order::genOrderNum($stockIds);
        foreach($show as &$s){
            if($s['type']==1){
                $s['order_num'] = is_null($orderNumMap[$s['id']]) ? 0 : $orderNumMap[$s['id']];
            }
        }

        return [$show, $allcount];
    }
    # 返回正在直播的商品
    /**
    public function livingStocksAction(){
        $live=new Live();
        $t = time();
        $lives=$live->setCols(['id'])->addWhere('valid','valid')->addWhere('status', 'verified')->addWhere("start_time",$t,'<=')->addWhere("end_time",$t,'>=')->orderBy('create_time', 'DESC')->find();
        return $lives;
    }
     */

    # 获取直播订单top3商品
    public function recommendStocksAction(){
        $live_id= $this->_GET("id");
        $stockLimit = 3;
        // 可选参数，传入筛选掉的商品ID
        $exclude_stock_id= $this->_GET("exclude_stock_id", -1);
        $sql = 'select stock_id from `order` where live_id = '.$live_id.' and stock_id != '.$exclude_stock_id.' group by stock_id order by count(stock_id) desc limit 5';
        $res = DB::query($sql);
        $stockIDs = array_map(
            function($item){
		        return $item['stock_id'];
            },
            $res
        );
        $stock = new Stock();
        $phStockMap = [];
        $phStockIDs = [];
        if(empty($stockIDs) || count($stockIDs) < 3) {
            $phStockMap = $stock->addWhere("live_id", $live_id)->addWhere('id', array_merge([$exclude_stock_id], $stockIDs), "not in")->addWhere('status', 'verified')->findMap("id");
            $phStockIDs = array_keys($phStockMap);
            shuffle($phStockIDs);
        } 
        $stockMap = $stock->addWhere("id",$stockIDs,'in')->addWhere('status', 'verified')->limit($stockLimit)->findMap("id");
        $stockIDs = array_merge($stockIDs, $phStockIDs);
        $stocks = array();
        // 保证顺序
        foreach($stockIDs as $ID){
            $stock=$stockMap[$ID] ? $stockMap[$ID] : $phStockMap[$ID];

            if(!is_null($stock)){
                $s = $stock->getData();
                $data = array();
                $data['id'] = $s['id'];
		        $data['imgs'] = $s['imgs'] ? json_decode($s['imgs']) : '[]';
                array_push($stocks, $data);
            } 
            if(count($stocks) > $stockLimit) {
                break;
            }
        }
        if(count($stocks) != 0) {
            for($i = 0; $i < 3 - count($stocks); $i++) {
                array_push($stocks, $stocks[0]);
            }
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['stocks'=>$stocks])];
    }

    public function genStocks($live_id, $pageId, $count, $order = 'DESC'){
        $stock=new Stock();
        $stock->setAutoClear(false);
        $stock->addWhere('live_id',$live_id)->addWhere('status', 'verified')->orderBy('create_time', $order);
        $allCount=$stock->count();
        $pageInfo=$this->_PAGE($allCount,$pageId,$count);
        
        $stocks=array_map(
            function($stock){
                $data = $stock->getData();
                if($data['status']!='verified' || $data['onshelf']!=1)
                    return null;
		        $data['imgs'] = $data['imgs'] ? json_decode($data['imgs']) : '[]';
		        $data['sku_meta'] = json_decode($data['sku_meta']);
		        $data['limit_num'] = 1;
		        return $data;
            },
            $stock->limit(($pageId-1)*$count,$count)->find()
        );
        $stocks=array_values(array_filter($stocks));
        return [$stocks,$allCount];
    }

    public function genStocksImgs($live_id){
        list($stocks,$allCount) = $this->genStocks($live_id,1,10);
        $imgs = array();
        foreach($stocks as $stock){
            $stock_imgs = $stock['imgs'];
            $imgs = array_merge($imgs,$stock_imgs);
        }
        return $imgs;
    }
    
    public function myAction(){
        //TODO
        /*
        $pageId=$this->_GET('pageId',1);
        $count=$this->_GET('count',10);
        $status=$this->_GET('status');
        
        $live=new Live();
        $live->setAutoClear(false);
        if($status){
            $live->addWhere('status',$status);
        }
        $allCount=$live->addWhere('valid','valid')->count();
         */
    }

    public function addReminderAction(){
        $id=intval($this->_POST("id",null,99999));

        $reminder=new UserReminder();
        $pushId = User::getCurrentUser() ? User::getCurrentUser()->mEasemobUsername : $_SESSION['easemob_anonymous']['username'];
        $res=$reminder->addWhere('push_id', $pushId)->addWhere("model_id",$id)->addWhere("model_type","live")->addWhere("type","before5")->select();
        if($res){
            return ['json:',AppUtils::returnValue(['msg'=>'预定成功'])];
        }
        $reminder->clear();
        $reminder->mUserId=User::getCurrentUser() ? User::getCurrentUser()->mId : 0;
        $reminder->mPushId=$pushId;
        $reminder->mModelType="live";
        $reminder->mModelId=$id;
        if(!$reminder->mModelId){
            return ['json:',AppUtils::returnValue(['msg'=>'no live id', 'pushId' => $pushId],99999)];
        }
        $reminder->mType='before5';//开始5分钟前提醒
        $reminder->mStatus='not_send';
        $reminder->mCreateTime=time();
        $reminder->mUpdateTime=time();
        if(!$reminder->save()){
            return ['json:',AppUtils::returnValue(['msg'=>'出错了', 'pushId' => $pushId],99999)];
        }
        $cache = new Memcache;
        $cache->connect(MEMCACHED_ADDR, MEMCACHED_PORT);
        $cacheKey = "live/addReminder|$pushId|$id";
        $cache->set($cacheKey, $reminder, MEMCACHE_COMPRESSED, 0);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['msg'=>'预定成功'])];
    }
    public function getReminderAction(){
        $id=intval($this->_GET("id",null,99999));
        $pushId = User::getCurrentUser() ? User::getCurrentUser()->mEasemobUsername : $_SESSION['easemob_anonymous']['username'];
        $cache = new Memcache;
        $cache->connect(MEMCACHED_ADDR, MEMCACHED_PORT);
        $cacheKey = "live/addReminder|$pushId|$id";
        $result = $cache->get($cacheKey);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['has'=>!!$result])];
        /*
        $reminder=new UserReminder();
        $reminder=$reminder->addWhere("push_id", $pushId)->addWhere("model_id",$id)->addWhere("model_type","live")->addWhere("type","before5")->select();
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['has'=>!!$reminder])];
         */
    }
    public function getRemindersAction(){
        $ids=explode(",", $this->_GET("ids",'',99999));
        $pushId = User::getCurrentUser() ? User::getCurrentUser()->mEasemobUsername : $_SESSION['easemob_anonymous']['username'];
        $cache = new Memcache;
        $cache->connect(MEMCACHED_ADDR, MEMCACHED_PORT);
        foreach($ids as $id) {
            $cacheKey = "live/addReminder|$pushId|$id";
            $result[] = ['id'=>$id, 'has'=>!!$cache->get($cacheKey)];
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['res'=>$result])];
        /*
        $reminder=new UserReminder();
        $reminder=$reminder->addWhere("push_id", $pushId)->addWhere("model_id",$id)->addWhere("model_type","live")->addWhere("type","before5")->select();
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['has'=>!!$reminder])];
         */
    }

    public function forenoticeAction(){
        $id=intval($this->_GET("id",null,99999));
        $forenotice=new LiveForenotice();
        if(!$forenotice->addWhere("live_id",$id)->select()){
            return ['json:',AppUtils::returnValue(['content'=>''])];
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['content'=>$forenotice->mContent])];
    }

    /**
     * 新版的直播详情页
     */
    public function flowNewAction(){
        $liveId= $this->_GET("live_id");
        $pageId = $this->_GET("pageId",1);
        $count = $this->_GET("count",4);

        if(empty($liveId)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["msg" => "缺少直播id"],50003)];
        }else{
            //1. 直播详情
            $liveInfo = (new Live())->getLiveInfoByLiveId($liveId);
            if(time() <= $liveInfo['end_time']) {
                $liveInfo['user_num'] = intval((time() - $liveInfo['start_time']) * 0.05);
                $liveInfo['left_time'] = $liveInfo['end_time'] - time();
            } else {
                $liveInfo['user_num'] = intval(($liveInfo['end_time'] - $liveInfo['start_time']) * 0.05 + (time() - $liveInfo['end_time']) * 0.005);
            }
            //2. 买手信息
            $buyerInfo = (new Buyer())->getBuyerInfo($liveInfo['buyer_id']);
            //3. 商品/状态列表
            $stateDetail = (new LiveStock())->getStateListOfLive($liveId,$pageId-1,$count);
            //4. pageInfo
            $stateDetail = array_map(function($state){
                if(($state['onshelf']==1 && $state['type']==1)||$state['type']==2){
                    return $state;
                }
            },$stateDetail);
            //如果直播未到开始时间，则stateList至为空
            $time = time();
            if($liveInfo['start_time'] >= $time){
                $liveInfo['is_open'] = 0;
                $liveInfo['is_close'] = 0;
                $liveInfo['left_time'] = $liveInfo['start_time'] -$time;
                $stateDetail = null;
            }else if($liveInfo['start_time'] < $time && $liveInfo['end_time'] >= $time){
                $liveInfo['is_open'] = 1;
                $liveInfo['is_close'] = 0;
            }else{
                $liveInfo['is_open'] = 0;
                $liveInfo['is_close'] = 1;
            }
            //5. 过滤已下架的商品
            $pageInfo=$this->_PAGEV2($pageId,count($stateDetail),$count);

            $rst = [
                "buyerInfo" => $buyerInfo,
                "liveInfo" => $liveInfo,
                "stateDetail" => array_values(array_filter($stateDetail)),
                "pageInfo" => $pageInfo,
            ];

            if($pageId >= 2){
                unset($rst["buyerInfo"]);
                unset($rst["liveInfo"]);
            }
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($rst)];
        }
    }

    /** q
     * 直播列表
     * @return array
     */
    public function livingAction(){
        $country=$this->_GET('country',null);
        $province=$this->_GET('province',null);
        $city=$this->_GET('city',null);

        $live=new Live();
        $live->setAutoClear(false);
        $live=$live->addWhere('list_show', '1');

        if(!is_null($country)){
            $live=$live->addWhere('country',$country);
        }
        if(!is_null($province)){
            $live=$live->addWhere('province',$province);
        }
        if(!is_null($city)){
            $live=$live->addWhere('city',$city);
        }

        $t = time();
        //直播预告
        $live=$live->addWhere('valid','valid')->addWhere('status', 'verified')->addWhere("start_time",$t,'<=')->addWhere("end_time",$t,'>=')->orderBy('end_time', 'ASC');
        $allCount=$live->count();
        $lives=$live->find();
        $lives = self::genLiveDetail($lives);
        $livingList = array_map(function($live){
            if($live['is_close'] == 0){
                return $live;
            }
        },$lives);

        //首页banner
        $bannerList = (new IndexNew())->getLiveBanner(0,10);
        //第一个正在直播的列表加入标志位
        if(count($livingList) >= 1){
            $livingList[0]['start'] = true;
        }

        //直播预告列表
        $live=(new Live())->addWhere('valid','valid')->addWhere("status", 'verified')->addWhere("start_time",$t,'>')->orderBy('start_time', 'ASC');
        $noticeLives = $live->find();
        $noticeLives = self::genLiveDetail($noticeLives);
        $reminderList = array();
        $liveIdList = array_map(function($live){
            return $live['id'];
        },$noticeLives);
        if(count($liveIdList) >=1){
            $reminderList = (new UserReminder())->getLiveRemindList($liveIdList);
        }
        $noticeLives = array_map(function($live)use($reminderList){
            $live['remindered'] = empty($reminderList[$live['id']])?false:true;
            return $live;
        },$noticeLives);

        //图案页bannerList
        $wallBannerList = StockBook::category();

        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([
            "livingList"=>array_values($livingList),
            "noticeLiveList"=>array_values($noticeLives),
            "bannerList" => $bannerList,
            "categoryList"=> $wallBannerList,
        ])];
    }


}
