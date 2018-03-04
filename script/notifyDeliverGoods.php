<?php
class notify {

    //买手发货提醒
    //$param['title']
    //$param['content']
    //$param['buyer_id']
    public  static function notifyBuyer($param) {
        Notification::sendNotification4Buyer($param['buyer_id'], ['title'=>$param['title'], 'type'=>$param['type'], 'from'=>'trade',
            'data'=>[
                'title'=>$param['title'],
                'content'=>$param['content'],
            ]
        ]);
    }

    public  static function notifyBuyer1() {
        Notification::sendNotification(2610,['title'=>$notifyStr,'type'=>'trade','from'=>'trade',
            'data'=>[
                'order_id'=>132423,
                'trade_title'=>1231,
                'stock_imageUrl'=>11111
            ]
        ]);
    }

    public static function addTrademsgQueue($param) {
        $tradeQueue = new TrademsgQueue();
        $tradeQueue->mSendType = 'buyer';
        $tradeQueue->mToId = $param['buyer_id'];
        $tradeQueue->mMsgType = $param['msg_type'];
        $tradeQueue->mContent = $param['content'];
        $tradeQueue->mstatus = 1;
        $tradeQueue->mSendTime = $param['time'];
        $tradeQueue->mCreateTime = $param['time'];
        $tradeQueue->mLiveId = $param['live_id'];
        $tradeQueue->save();
    }

    //采购提醒
    public static function purchase() {
        $now = time();
        $order = new Order();
        $orders = $order->addWhere('status','prepayed')->find();
        $liveids = [];
        foreach($orders as $order){
            $liveids[$order->mLiveId] +=1;
        }
        foreach($liveids as $liveId=>$num) {
            $live = new Live();
            $live = $live->addWhere('id',$liveId)->select();
            if ($now <= $live->mEndTime){//还没结束不发送
                continue;
            }
            if (($now-$live->mEndTime)/86400>=3) {
                $tradeQueue = new TrademsgQueue();
                $tradeQueue = $tradeQueue->addWhere('send_type', 'buyer')->addWhere('to_id', $live->mBuyerId)->addWhere('msg_type', 'purchase')->addWhere('live_id', $live->mId)->orderBy('id', 'desc')->select();
                if (!$tradeQueue || ($tradeQueue && ($now - $tradeQueue->mSendTime)>=86400*2)) {
                    $param['title'] = '买家已支付定金,等待采购';
                    $live_name = mb_substr($live->mName, 0, 5, 'utf-8').'...'.mb_substr($live->mName, -5, 5, 'utf-8');
                    $param['content'] = "“".$live_name."”"."已结束,现在还有".$num."个订单等待您来采购并发送补款通知。";
                    $param['buyer_id'] = $live->mBuyerId;
                    $param['time'] = $now;
                    $param['msg_type'] = 'purchase';
                    $param['live_id'] = $live->mId;
                    $param['type'] = 'Prepayed';
                    self::notifyBuyer($param);
                    self::addTrademsgQueue($param);
                }
            }
        }
    }

    //发货提醒
    public static function sendOut() {
        $now = time();
        $order = new Order();
        $orders = $order->addWhere('status','payed')->find();
        $live_ids = [];
        foreach($orders as $order) {
            $live_ids[] = $order->mLiveId;
        }
        $live_ids = array_unique($live_ids);
        foreach ($live_ids as $live_id) {
            $live = new Live();
            $live = $live->addWhere('id', $live_id)->select();
            if ($now <= $live->mEndTime) {//还没结束不发送
                continue;
            }
            $statusArr = ['prepayed','wait_pay','payed','packed','to_demostic','demostic','to_user','success'];
            $order = new Order();
            $orders = $order->addWhere('live_id', $live_id)->addWhere("status", $statusArr, 'in')->find();
            $total = count($orders);
            if ($total == 0) {
                continue;
            }
            $statuaArr = ['payed'=>0,'prepayed'=>0,'wait_pay'=>0];
            foreach($orders as $order) {
                $statuaArr[$order->mStatus]++;
            }

            if (($now-$live->mEndTime)<9*86400 && $statuaArr['payed']/$total>0.5 && $statuaArr['payed']/$total<1) {
                $tradeQueue = new TrademsgQueue();
                $tradeQueue = $tradeQueue->addWhere('send_type', 'buyer')->addWhere('msg_type','fahuo1')->addWhere('to_id', $live->mBuyerId)->addWhere('live_id',$live->mId)->orderBy('id', 'desc')->select();
                if (!$tradeQueue) {
                    $param['title'] = '买家已补余款,等待发货';
                    $live_name = mb_substr($live->mName, 0, 5, 'utf-8').'...'.mb_substr($live->mName, -5, 5, 'utf-8');
                    $param['content'] = "“".$live_name."”"."您全部订单中已有一半的订单已经补完余款啦,正在等您发货,发货后将收到已发货包裹的50%的结算款项,为了您的资金流通,请迅速发货哦!";
                    $param['buyer_id'] = $live->mBuyerId;
                    $param['time'] = $now;
                    $param['msg_type'] = 'fahuo1';
                    $param['live_id'] = $live->mId;
                    $param['type'] = 'Payed';
                    self::notifyBuyer($param);
                    self::addTrademsgQueue($param);
                }
            } elseif (($now-$live->mEndTime)<9*86400 && $statuaArr['wait_pay']==0 && $statuaArr['prepayed']==0) {
                $tradeQueue = new TrademsgQueue();
                $tradeQueue = $tradeQueue->addWhere('send_type', 'buyer')->addWhere('msg_type','fahuo2')->addWhere('to_id', $live->mBuyerId)->addWhere('live_id',$live->mId)->orderBy('id', 'desc')->select();
                if (!$tradeQueue) {
                    $param['title'] = '买家已补余款,等待发货';
                    $live_name = mb_substr($live->mName, 0, 5, 'utf-8').'...'.mb_substr($live->mName, -5, 5, 'utf-8');
                    $param['content'] = "“".$live_name."”"."您全部订单都已补完余款啦,正在等待您的发货,发货后将收到已发货包裹的50%的结算款项,为了您的资金流通,请迅速发货哦!";
                    $param['buyer_id'] = $live->mBuyerId;
                    $param['time'] = $now;
                    $param['msg_type'] = 'fahuo2';
                    $param['live_id'] = $live->mId;
                    $param['type'] = 'Payed';
                    self::notifyBuyer($param);
                    self::addTrademsgQueue($param);
                }
            } elseif(($now-$live->mEndTime)>9*86400 && $statuaArr['payed']>0) {
                $tradeQueue = new TrademsgQueue();
                $tradeQueue = $tradeQueue->addWhere('send_type', 'buyer')->addWhere('msg_type','fahuo3')->addWhere('to_id', $live->mBuyerId)->addWhere('live_id',$live->mId)->orderBy('id', 'desc')->select();
                if (!$tradeQueue || ($tradeQueue && ($now - $tradeQueue->mSendTime)>=86400)) {
                    $param['title'] = '买家已补余款,等待发货';
                    $live_name = mb_substr($live->mName, 0, 5, 'utf-8').'...'.mb_substr($live->mName, -5, 5, 'utf-8');
                    $param['content'] = "“".$live_name."”"."您还有".$statuaArr['payed']."个订单正在等待您的发货,发货后将收到已发货包裹的50%的结算款项,为了您的资金流通,请迅速发货哦!";
                    $param['buyer_id'] = $live->mBuyerId;
                    $param['time'] = $now;
                    $param['msg_type'] = 'fahuo3';
                    $param['live_id'] = $live->mId;
                    $param['type'] = 'Payed';
                    self::notifyBuyer($param);
                    self::addTrademsgQueue($param);
                }
            }
        }
    }
}
notify::purchase();
notify::sendOut();
