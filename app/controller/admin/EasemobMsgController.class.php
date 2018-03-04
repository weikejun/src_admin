<?php
class EasemobMsgController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new EasemobMsg();
        $this->model
            ->setCols(['id','from','to','msg_text','send_time','msg_type','rawdata'])
            ->addWhere('from', ['admin', 'trade'], 'not in')
            ->addWhere('to', ['admin', 'trade'], 'not in')
            ->orderBy("send_time","desc");
        if($this->_GET('target')) {
            $this->model->addWhereRaw('and (`from`="'.$this->_GET('target').'" or `to`="'.$this->_GET('target').'")');
        }
        self::$PAGE_SIZE=20;
        if($this->_GET('pairs')) {
            $ids = explode(',', $this->_GET('pairs'));
            $this->model->addWhereRaw('and ((`from`="'.$ids[0].'" and `to`="'.$ids[1].'") or (`from`="'.$ids[1].'" and `to`="'.$ids[0].'"))');
        }
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'msg_id','label'=>'msg_id','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'from','label'=>'from','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'to','label'=>'to','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'msg_type','label'=>'类型','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'msg_text','label'=>'内容','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'send_time','label'=>'发送时间','type'=>"datetime",'default'=>null,'required'=>false,),

        ));
        $this->list_display=array(
            ['label'=>'消息ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'发送者','field'=>function($model){
                $content = $model->mFrom;
                if(preg_match("/^buyer_/",$model->mFrom)) {
                    $buyer = self::_getResource($model->mFrom, 'buyer', new Buyer, 'easemob_username');
                    $content = "买手(ID:".$buyer[0]->mId.")-".$buyer[0]->mName;
                } elseif(preg_match("/^user_/",$model->mFrom)) {
                    $user = self::_getResource($model->mFrom, 'user', new User, 'easemob_username');
                    $content = $user ? "买家(ID:".$user[0]->mId.")-".$user[0]->mName : "匿名买家";
                }
                return "<a href='/admin/easemobMsg?__filter=".urlencode("from=".$model->mFrom)."'>".$content."</a>";
            }],
            ['label'=>'接收者','field'=>function($model){
                $content = $model->mTo;
                if(preg_match("/^buyer_/",$model->mTo)) {
                    $buyer = self::_getResource($model->mTo, 'buyer', new Buyer, 'easemob_username');
                    $content = "买手(ID:".$buyer[0]->mId.")-".$buyer[0]->mName;
                } elseif(preg_match("/^user_/",$model->mTo)) {
                    $user = self::_getResource($model->mTo, 'user', new User, 'easemob_username');
                    $content = $user ? "买家(ID:".$user[0]->mId.")-".$user[0]->mName : "匿名买家";
                }
                return "<a href='/admin/easemobMsg?__filter=".urlencode("to=".$model->mTo)."'>".$content."</a>";
            }],
            ['label'=>'发送时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mSendTime);
            }],
            ['label'=>'消息内容','field'=>function($model){
                if($model->mMsgType == 'img') {
                    $data = json_decode($model->mRawdata, true);
                    if($data) {
                        return '<img width="200px" src="'.$data['payload']['bodies'][0]['thumb'].'" />';
                    }
                }
                return $model->mMsgText;
            }],
        );
        $this->list_filter=array(
            new Page_Admin_HiddenFilter(['name'=>'发送者ID','paramName'=>'from','fusion'=>false]),
            new Page_Admin_HiddenFilter(['name'=>'接收者ID','paramName'=>'to','fusion'=>false]),
        );
        if($this->_GET('target')) {
        } else {
            $this->list_filter[] = new Page_Admin_TextFilter(['name'=>'消息内容','paramName'=>'msg_text','fusion'=>true]);
            $this->list_filter[] = new Page_Admin_TimeRangeFilter(['name'=>'发送时间','paramName'=>'send_time']);
        }
        $this->hide_action_new = true;
        $this->single_actions = [
            ['label'=>'完整对话','target'=>'_blank','action'=>function($model){
                return "/admin/easemobMsg?pairs=".$model->mTo.",".$model->mFrom;
            }]
        ];
        $this->single_actions_default = [
            'edit' => false,
            'delete' => false,
        ];
    }
}

