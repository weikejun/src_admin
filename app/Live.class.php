<?php
class Live extends Base_Live{

    /**
     * 所有直播
     */
    const ALL_LIVING = 0;

    /**
     * 正在直播
     */
    const LIVING = 1;

    /**
     * 成功直播
     */
    const LIVED = 2;

    /**
     * 预告
     */
    const TO_LIVE = 3;
    /*
public function getData($field=null){
$d=parent::getData($field);
if(is_null($field)){
    $d['brands']=json_decode($d['brands'],true);
    $d['brands']=$d['brands']?$d['brands']:[];
    $d['imgs']=json_decode($d['imgs'],true);
    $d['imgs']=$d['imgs']?$d['imgs']:[];
    $d['imgs_urls']=array_map(function($img){
        return BASE_URL.PUBLIC_IMAGE_URI.$img;
    },$d['imgs']);
}
return $d;
}
     */
    public static function getListShow(){
        return [
            ['0','不显示'],
            ['1','显示'],
        ];
    }
    public static function getAllStatus(){
        return [
            ['not_verify','申请未通过'],
            ['verifying','申请中'],
            ['verified','申请已通过'],
            ['cancel','已撤销'],
        ];
    }
    public static function getAllTypes(){
        return [
            ["","（无类别）"],
            ["鞋子","鞋子"],
            ["护肤","护肤"],
            ["母婴","母婴"],
            ["健康","健康"],
            ["大牌","大牌"],
            ["服装","服装"],
            ["配饰","配饰"],
            ["包包","包包"],
            ["日用","日用"],
            ["特色","特色"],
            ["食品","食品"],
        ];
    }

    /**
     * 根据买手id获取状态为status的直播列表详情
     * @param $buyerId
     * @param $status
     * @param $pageId
     * @param $count
     * @return array
     */
    public function getLiveByBuyerId($buyerId, $status, $pageId = 0, $count = 4){
        $live=new self();
        $t = time();
        // 0表示全部，1表示正在直播，2表示结束，3表示直播预告(运营相关，暂时默认为全部)
        switch ($status){
            case 0:
                $live=$live->addWhere('buyer_id', $buyerId)->addWhere('valid','valid');
                break;
            case 1:
            case 4:
                $pageId = 0;
                $count = 1;
                $live=$live->addWhere('buyer_id', $buyerId)->addWhere('valid','valid')->addWhere('status', 'verified')->addWhere("start_time",$t,'<=')->addWhere("end_time",$t,'>=');
                break;
            case 2:
                $live=$live->addWhere('buyer_id', $buyerId)->addWhere('valid','valid')->addWhere("end_time",$t,'<');
                break;
            case 3:
                $live=$live->addWhere('buyer_id', $buyerId)->addWhere('valid','valid')->addWhere("status", 'verified')->addWhere("start_time",$t,'>');
                break;
            default:
                $live=$live->addWhere('buyer_id', $buyerId)->addWhere('valid','valid');
                break;
        }
        $lives=$live->limit($pageId*$count,$count)->find();

        $lives = $this->genLiveDetail($lives);
        return $lives;
    }

    /**
     * 将直播列表进行数据丰富
     * @param $lives
     * @return array
     */
    public function genLiveDetail($lives){
        if(empty($lives) || count($lives) <= 0 ){
            return array();
        }
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

        $lives = array_map(function($live)use($buyerMap,$flowMap){
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
            $data['imgs'] = json_decode($data['imgs']);
            $data['dim_imgs'] = json_decode($data['dim_imgs']);
            $data['is_flow'] = isset($flowMap[$live->mId]) ? 1 : 0;
//            $imgs = $this::genStocksImgs($data['id']);
//            shuffle($imgs);
//            $data['stock_imgs'] = array_slice($imgs,0,5);
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
            if($data['type']){
                $data['name']="【{$data['type']}】{$data['name']}";
            }
            return $data;
        },$lives);

        return $lives;
    }

    /**
     * 获取买家已经成功直播的计数
     * @param $buyerId
     * @param $status(// 0表示全部，1表示正在直播，2表示结束，3表示直播预告(运营相关，暂时默认为全部))
     * @return int
     */
    public function getLiveCountByBuyerId($buyerId,$status){
        if(empty($buyerId)){
            return 0;
        }else{
            $t = time();
            // 0表示全部，1表示正在直播，2表示结束，3表示直播预告(运营相关，暂时默认为全部)
            switch ($status){
                case 0:
                    $live=$this->addWhere('buyer_id', $buyerId)->addWhere('valid','valid');
                    break;
                case 1:
                case 4:
                    $live=$this->addWhere('buyer_id', $buyerId)->addWhere('valid','valid')->addWhere('status', 'verified')->addWhere("start_time",$t,'<=')->addWhere("end_time",$t,'>=');
                    break;
                case 2:
                    $live=$this->addWhere('buyer_id', $buyerId)->addWhere('valid','valid')->addWhere("end_time",$t,'<');
                    break;
                case 3:
                    $live=$this->addWhere('buyer_id', $buyerId)->addWhere('valid','valid')->addWhere("status", 'verified')->addWhere("start_time",$t,'>');
                    break;
                default:
                    $live=$this->addWhere('buyer_id', $buyerId)->addWhere('valid','valid');
                    break;
            }
            $count = $live->count();
            return $count;
        }
    }

    /**
     * 根据直播Id获取直播信息
     * @param $liveId
     * @return array
     */
    public function getLiveInfoByLiveId($liveId){
        if(empty($liveId)){
            return array();
        }
        $data = $this->addWhere('id',$liveId)->select()->getData();
        if(!empty($data)){
            $data['imgs'] = !empty($data['imgs'])?json_decode($data['imgs']):null;
            $data['brands'] = !empty($data['brands'])?json_decode($data['brands']):null;
            $data['brands_label'] = implode("/",$data['brands']);
            $data['dim_imgs'] = !empty($data['dim_imgs'])?json_decode($data['dim_imgs']):null;
            return $data;
        }else{
            return array();
        }
    }

    /**
     * 直播的可以显示的字段
     */
    public static function getFieldFilter(){
        return [
            "id",
            "name",
            "intro",
            "buyer_id",
            "country",
            "province",
            "city",
            "address",
            "brands",
            "start_time",
            "end_time",
            "create_time",
            "valid",
            "update_time",
            "status",
            "imgs",
            "check_time",
            "checker_id",
            "check_words",
            "selector",
            "editor",
            "list_show",
            "type"
        ];
    }
}
