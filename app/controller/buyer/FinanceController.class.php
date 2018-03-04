<?php
class FinanceController extends AppBaseController{
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new BuyerLoginInterceptor());
        $this->addInterceptor(new DBTransactionInterceptor('withdrawAction'));
    }
    public function infoAction(){
        $buyer=Buyer::getCurrentBuyer();
        $live=new Live();
        $livesMap=$live->addWhere('buyer_id',$buyer->mId)->addWhere("valid","valid")->findMap("id");
        foreach($livesMap as $id=>$live){
            $livesMap[$id]=[
                'name'=>$live->mName,
                'address'=>$live->mAddress,
                'country'=>$live->mCountry,
                'province'=>$live->mProvince,
                'city'=>$live->mCity,
                'total_cash'=>0,
            ];
        }

        $order=new Order();
        $orders=$order->addWhere("live_id",array_keys($livesMap),"in")->addWhere('status',['to_user','post_sale','success'],"in")->find();
        $can_withdraw_cash=0;
        $withdrawed_cash=0;
        foreach($orders as $order){
            $livesMap[$order->mLiveId]['total_cash']+=$order->mSumPrice;
            if(!$order->mBuyerWithdrawId){
                $can_withdraw_cash+=$order->mSumPrice;
            }else{
                $withdrawed_cash+=$order->mSumPrice;
            }
        }
        

        return ['json:',AppUtils::returnValue([
            'lives'=>array_values($livesMap),
            'total_cash'=>array_reduce(array_values($livesMap),function($total_cash,$live){
                return $total_cash+=$live['total_cash'];
            },0),
            'can_withdraw_cash'=>round($can_withdraw_cash*0.95,2),
            'withdrawed_cash'=>round($withdrawed_cash*0.95,2),
        ],0)];
        /*
        [
        'lives'=>[
                [name,city,province,country,address,total_cash]
            ],
        'total_cash',//总交易成功金额
        'withdrawed_cash',//已提取金额
        'can_withdraw_cash',//可提取金额
        ]
         */

    }
    public function accountInfoAction(){
        $buyerAccount=new BuyerAccount();
        $buyerAccount=$buyerAccount->addWhere("buyer_id",Buyer::getCurrentBuyer()->mId)->select();
        return ['json:',AppUtils::returnValue($buyerAccount?$buyerAccount->getData():[],0)];
        
    }
    public function withdrawAction(){

        //account     账号id
        //account_type    账号类型，哪个银行的卡？
        //account_name   账号的真实姓名
        //password   每次提取，都验证下密码


        $type=$this->_POST("type","",10001);
        if($type=='foreign'){
            $params=['type', 'no', 'name', 'address', 'bank', 'swift', 'routing', 'country', 'city'];
        }elseif($type=='local'){
            $params=['type', 'no', 'name', 'bank'];
        }else{
            return ['json:',AppUtils::returnValue(['type wrong,must be foreign or local'],10001)];
        }
        
        $accountData=array_map(function($param){
            return $_POST[$param];
        },$params);
        $accountData=array_combine($params,$accountData);



        //$account=$this->_POST("account","",10001);
        //$account_type=$this->_POST("account_type","",10002);
        //$account_name=$this->_POST("account_name","",10003);
        //$password=$this->_POST("password","",10004);
        //$use_md5=$this->_POST("use_md5",0);
/*
        if($use_md5!=1){
            $password=md5($password);
        }
 */
        $buyer=Buyer::getCurrentBuyer();
        $live=new Live();
        $livesMap=$live->addWhere('buyer_id',$buyer->mId)->addWhere("valid","valid")->findMap("id");


/*
        if($password!=$buyer->mPassword){
            return ['json:',AppUtils::returnValue(['password wrong'],10005)];
        }
         
 */

        
        
        $order=new Order();
        $orders=$order->addWhere("live_id",array_keys($livesMap),"in")->addWhere('status',['to_user','post_sale','success'],"in")->addWhere("buyer_withdraw_id",null,"=")->findMap('id');
        if(count(array_keys($orders))==0){
            return ['json:',AppUtils::returnValue([],0)];
        }
        $can_withdraw_cash=0;
        foreach($orders as $order){
            if(!$order->mBuyerWithdrawId){
                $can_withdraw_cash+=$order->mSumPrice;
            }
        }
        
        $buyerWithdraw=new BuyerWithdraw();
        $buyerWithdraw->mBuyerId=$buyer->mId;
        $buyerWithdraw->mNote=$this->_POST("note","");
        $buyerWithdraw->mCreateTime=time();
        $buyerWithdraw->mUpdateTime=time();
        $buyerWithdraw->mAmount=$can_withdraw_cash;
        $buyerWithdraw->mLog=json_encode(array_keys($orders));
        

        //$buyerWithdraw->mAccount=$account;
        //$buyerWithdraw->mAccountType=$account_type;
        //$buyerWithdraw->mAccountName=$account_name;
        $withdrawAccountData=array_combine(
            array_map(
                function($param){return "account_$param";},
                array_keys($accountData)
            ),
            array_values($accountData));

        $buyerWithdraw->setDataMerge($withdrawAccountData);
            
        $ret=$buyerWithdraw->save();

        if(!$ret){
            throw new ModelAndViewException("save error:".$buyer->mId."\t".implode("\t",$_POST),1,"text:{$amount->mAmount}");
        }
        
        $orderTbl=new DBTable('order');
        $orderTbl->addWhere("id",array_keys($orders),'in')->update(['buyer_withdraw_id'=>$buyerWithdraw->mId]);


        $buyerAccount=new BuyerAccount();
        $buyerAccount->addWhere("buyer_id",$buyer->mId)->select();
        $buyerAccount->setDataMerge($accountData);
        $buyerAccount->save();
        
        return ['json:',AppUtils::returnValue([],0)];
    }
}

