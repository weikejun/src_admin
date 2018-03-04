<?php
class EasemobController extends AppBaseController{
    public function __construct(){
        //$this->addInterceptor(new LoginInterceptor());        
    }
    private $salt_user='sdjks@#%677&^c5';
    private $salt_pass='@#ffy%6f7&^c82_';
    public function anonymousAction(){
        if(isset($_SESSION['easemob_anonymous'])){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($_SESSION['easemob_anonymous'])];
        }
        
        $anonymous=new EasemobAnonymous();
        $anonymous=$anonymous->addWhere("session_id",session_id())->select();
        if($anonymous){
            $data=['username'=>$anonymous->mUsername,
                'password'=>$anonymous->mPassword,
                ];
        }else{
            $data['username']="user_".md5($this->salt_user.time().session_id());
            $data['password']=md5($this->salt_pass.time().session_id());
            $ret=Easemob::getInstance()->createUser($data['username'],$data['password']);
            if(isset($ret['error'])){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue([$ret['error_description']],99999)];
            }
            $anonymous=new EasemobAnonymous();
            $anonymous->mUpdateTime=$anonymous->mCreateTime=time();
            $anonymous->mSessionId=session_id();
            $anonymous->setDataMerge($data)->save();
        }
        $_SESSION['easemob_anonymous']=$data;

        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($data)];
    }
    public function meAction(){
        if($user=User::getCurrentUser()){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['username'=>$user->mEasemobUsername,'password'=>$user->mEasemobPassword])];
        }
        return $this->anonymousAction();
    }
    public function infoAction(){
        $username=$this->_GET('username','',99999);
        if(preg_match("/^user/",$username)){
            $user=new User();
            $user=$user->addWhere('easemob_username',$username)->select();
            if($user){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(["type"=>"user",'name'=>$user->mName,'head'=>$user->mAvatarUrl, 'uid'=>$user->mId],0)];
            }
        }
        
        if(preg_match("/^buyer/",$username)){
            $buyer=new Buyer();
            $buyer=$buyer->addWhere('easemob_username',$username)->select();
            if($buyer){
                return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['type'=>"buyer",'name'=>$buyer->mName,'head'=>$buyer->mHead, 'uid'=>$buyer->mId],0)];
            }
        }
        if(preg_match("/^admin/",$username)){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['type'=>'admin','name'=>'系统消息','head'=>"/public_upload/2/4/6/246ddb61067692cbc0150b51d0c0ab7b.jpg"],0)];
        }
        if($username=='trade'){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['type'=>'trade','name'=>'交易消息','head'=>"/public_upload/f/1/a/f1a6c48179a976b04d01e953aa20f45f.jpg"],0)];
        }
        if($username=='comment'){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['type'=>'comment','name'=>'评论','head'=>"/public_upload/f/f/8/ff8a34e27eb3d708645dddab4f6c2065.jpg"],0)];
        }
        
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['name'=>"淘世界_".substr($username,-5,5),'head'=>"/winphp/metronic/media/image/avatar.png"],0)];
        
    }

    public function infosAction(){
        $usernames=$this->_POST('usernames','[]',99999);
        $usernames=json_decode($usernames,true);
        //$usernames=array_slice($usernames,0,20);
        $infos=[];
        //$buyers=[];


        if($users=array_values(array_filter($usernames,function($username){
            return preg_match("/^user/",$username);
        }))){
            $ids = $users;
            $user=new User();
            $users=$user->addWhere('easemob_username',$ids,"in")->find();
            foreach($users as $user){
                $infos[$user->mEasemobUsername]=['type'=>'user','name'=>$user->mName,'head'=>$user->mAvatarUrl, 'uid'=>$user->mId];
                $infos[$user->mEasemobUsername]=['type'=>'user','name'=>empty($user->mName)?"":$user->mName,'head'=>$user->mAvatarUrl, 'uid'=>$user->mId];
            }
            $user = new EasemobAnonymous();
            $users = $user->addWhere('username',$ids,"in")->find();
            foreach($users as $user){
                $infos[$user->mUsername]=['type'=>'user','name'=>"淘世界_".substr($user->mUsername,-5,5),'head'=>"/winphp/metronic/media/image/avatar.png"];
            }
        }
        if($buyers=array_values(array_filter($usernames,function($username){
                return preg_match("/^buyer/",$username);
            }))){
            $buyer=new Buyer();
            $buyers=$buyer->addWhere('easemob_username',$buyers,"in")->find();
            foreach($buyers as $buyer){
                $infos[$buyer->mEasemobUsername]=['type'=>'buyer','name'=>$buyer->mName,'head'=>$buyer->mHead, 'uid'=>$buyer->mId];
                $infos[$buyer->mEasemobUsername]=['type'=>'buyer','name'=>empty($buyer->mName)?"":$buyer->mName,'head'=>$buyer->mHead, 'uid'=>$buyer->mId];
            }
        }
        if($admins=array_values(array_filter($usernames,function($username){
                return preg_match("/^admin/",$username)||$username=="trade"||$username=="comment";
            }))){
            foreach($admins as $admin){
                if(preg_match("/^admin/",$admin)){
                    $infos['admin']=['type'=>'admin','name'=>'系统消息','head'=>"/public_upload/2/4/6/246ddb61067692cbc0150b51d0c0ab7b.jpg"];
                }
                if($admin=='trade'){
                    $infos['trade']=['type'=>'trade','name'=>'交易消息','head'=>"/public_upload/f/1/a/f1a6c48179a976b04d01e953aa20f45f.jpg"];
                }
                if($admin=='comment'){
                    $infos['comment']=['type'=>'comment','name'=>'评论','head'=>"/public_upload/f/f/8/ff8a34e27eb3d708645dddab4f6c2065.jpg"];
                }
            }
        }
        if($this->_POST('os') == 'android') {
            $infos = ['usernames' => $infos];
        } 
        return ['json:',AppUtils::returnValue($infos,0)];
    }

    public function contactsAction() {
        $emName = EASEMOB_ORG."#".EASEMOB_APP.'_';
        $emId = $this->_GET('easemobId');
        $limit = $this->_GET('limit', 50);
        $emMsg = new EasemobMsg;
        $emMsg = $emMsg->setCols(['from','to'])->addComputedCol('IF(`from` = "'.$emId.'", `to`, `from`) finder')->addWhere('to', $emId)->addWhere('from', $emId, '=', 'or')->orderBy('id', 'desc')->groupBy('finder')->limit($limit)->find();
        $emIds = [];
        foreach($emMsg as $msg) {
            $selEmId = '';
            $selEmId = $emId == $msg->mTo ? $msg->mFrom : $msg->mTo;
            $emIds[] = [
                'groups' => [],
                'jid' => $emName . $selEmId . '@easemob.com',
                'name' => $selEmId,
                'subscription' => 'both',
            ];
        }
        return ['json:',AppUtils::returnValue(['contacts'=>$emIds],0)];
    }

    public function getHistoryAction() {
        $id1 = $this->_GET('id1');
        $id2 = $this->_GET('id2');
        $timeEnd = intval($this->_GET('end_time'));
        if(!$timeEnd) {
            $timeEnd = time();
        }
        $limit = $this->_GET('limit', 20);
        if(empty($id1) || empty($id2)) {
            return ['json:',AppUtils::returnValue(['msg' => 'id empty'], 13003)];
        }
        $emMsg = new EasemobMsg;
        $emMsg = $emMsg->addWhereRaw("((`to` = '$id1' and `from` = '$id2') or (`to` = '$id2' and `from` = '$id1'))")->addWhere('send_time', $timeEnd, '<')->orderBy('send_time', 'desc')->limit($limit)->find();
        $data = array_map(function($msg) {
            $detail = $msg->getData();
            $detail['rawdata'] = json_decode($detail['rawdata'],true);
            return $detail;
        }, $emMsg);
        $data = array_reverse($data);
        return ['json:',AppUtils::returnValue(['msg' => $data], 0)];
    }

    public function getCSAction() {
        $finder = new Buyer;
        $kefus = $finder->addWhere('weixin', 'aimeizhuyi.kefu')->findMap('easemob_username');
        $kefuIds = array_keys($kefus);
        if(empty($kefuIds)) {
            return ['json:',AppUtils::returnValue(['msg' => 'fail'], 13004)];
        }
        $finder = new EasemobMsg;
        $from = $this->_GET('easemob_username', '');
        if(!$from) {
            shuffle($kefuIds);
            return ['json:',AppUtils::returnValue(['kefu_info' => ['name' => $kefuIds[0]]], 0)];
        }
        $msg = $finder->addWhere('from', $from)->addWhere('to', $kefuIds, 'in')->addWhere('send_time', time()-3*86400, '>')->findMap('to');
        if(!$msg) {
            shuffle($kefuIds);
            return ['json:',AppUtils::returnValue(['kefu_info' => ['name' => $kefuIds[0]]], 0)];
        }
        $preKefuIds = array_keys($msg);
        return ['json:',AppUtils::returnValue(['kefu_info' => ['name' => $preKefuIds[0]]], 0)];
    }

    /*
    public function infoUserAction(){
        $username=$this->_GET('username','',99999);
        $user=new User();
        $user=$user->addWhere('easemob_username',$username)->select();
        if(!$user){
            return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['no this user'],99999)];
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue(['name'=>$user->mNick,'head'=>$user->mAvatarUrl])];
    }*/
    
}
