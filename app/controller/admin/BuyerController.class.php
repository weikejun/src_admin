<?php
class BuyerController extends Page_Admin_Base {
    public function bindModelEvent(){
        $before_status=false;
        $this->model->on("before_update",function($model)use(&$before_status){
            //update 之前，没有查询老的值，只能再查一次
            $class=get_class($model);
            $curModel=new $class();
            $curModel=$curModel->addWhere("id",$model->mId)->select();
            if(!$curModel){
                return false;
            }
            $before_status=$curModel->mStatus;
        });
        $this->model->on("after_update",function($model)use(&$before_status){
            $after_status=$model->mStatus;
            Logger::debug("after_update $before_status.$after_status");
            if($before_status&&$after_status&&$before_status!=$after_status){
                //TODO sendmail
                if($after_status=='be'){
                    EMail::send([
                        'title'=>"欢迎加入淘世界",     
                        'content'=> str_replace('$DATE$', date("Y.m.d"), file_get_contents(ROOT_PATH.'/template/edm/buyer_pass.tpl')),
                        'to'=>$model->mEmail,
                    ]);
                    if($model->mPicker){
                        $content=<<<END
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<h1>dear  “{$model->mPicker}”：</h1>

<p>买手“{$model->mName}”已成功通过审核，需要麻烦你跟进并通过微信联系买手沟通后续事宜。
如在交流中有任何问题，可反馈给买手运营部@linlin。</p>

<h3>买手信息</h3>
买手ID：{$model->mId}<br/>
用户名：{$model->mName}<br/>
真实姓名：{$model->mRealName}<br/>
国家：{$model->mCountry}<br/>
微信：{$model->mWeixin}<br/>
qq：{$model->mQq}<br>
</body>
</html>
END;
                        EMail::send([
                            'title'=>"［买手分配］“{$model->mName}”＋“{$model->mPicker}”",     
                            'content'=> $content,
                            'to'=>$model->mPicker."@aimeizhuyi.com",
                            'cc'=>['lengqiying@aimeizhuyi.com','linlin@aimeizhuyi.com','xiyajuan@aimeizhuyi.com','chenji@aimeizhuyi.com'],
                        ]);
                    }
                }
            }
        });
    }
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new Buyer();
        $this->model->orderBy("id","desc");
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);
        
        $this->bindModelEvent();

        $this->form=new Form(array(
            /*
            array('type'=>'text',"name"=>"name","label"=>'name',"required"=>true,'class'=>'wide'),
            array('type'=>'text',"name"=>"url","label"=>'url',"required"=>true,'class'=>'wide'),
            array('type'=>'text',"name"=>"img","label"=>'img','class'=>'wide'),
            array('type'=>'text',"name"=>"tags","label"=>'tags','class'=>'wide'),
            array('type'=>'text',"name"=>"status","label"=>'status','default'=>1,'class'=>'wide'),
            array('type'=>'text',"name"=>"ctime","label"=>'ctime','default'=>time(),'class'=>'wide'),
             */
            array('name'=>'name','label'=>'用户名','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'password','label'=>'密码重置','type'=>"password",'default'=>null,'required'=>false,),
            array('name'=>'password_again','label'=>'密码确认','type'=>"password",'default'=>null,'required'=>false,'validator'=>function($values){
                if(!$values['password']||
                    ($values['password']&&md5($values['password_again'])==$values['password'])){
                    return true;
                }else{
                    return "please retype the password";
                }
            }),
            array('name'=>'phone','label'=>'海外电话','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'email','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'create_time','label'=>'注册时间','type'=>"datetime",'default'=>null,'required'=>false,),
            //array('name'=>'update_time',      'type'=>"datetime",'default'=>null,'required'=>false,),
            array('name'=>'city','label'=>'城市','type'=>"text",'default'=>null,'required'=>true,),
            //array('name'=>'province',      'type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'country','label'=>'国家','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'address','label'=>'地址','label'=>'地址','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'real_name','label'=>'中文姓名','label'=>'真名','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'weixin','label'=>'微信/qq','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'qq','label'=>'微信/qq','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'easemob_username','label'=>'环信用户名','readonly'=>true,'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'easemob_password','label'=>'环信密码','readonly'=>true,'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'head','label'=>'头像','type'=>"simpleFile",'default'=>null,'required'=>false,),
            array('name'=>'id_num','label'=>'护照号','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'id_pics','label'=>'证件照','type'=>"simpleJsonFiles",'default'=>null,'required'=>true,),
            //array('name'=>'birthday','label'=>'生日',  'type'=>"date",'default'=>null,'required'=>false,),
            array('name'=>'valid','label'=>'账户状态',"choices"=>array(array('valid',"有效"),array('invalid',"无效"),), 'type'=>"choice",'default'=>'valid','null'=>false,),
            array('name'=>'fee_rate','label'=>'代购费率','type'=>"text","default"=>null,"required"=>false),
            array('name'=>'ship_percent','label'=>'发货结算比例','type'=>"text","default"=>'50%',"required"=>true),
            array('name'=>'picker','label'=>'选款师','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'status','label'=>'申请状态',"choices"=>Buyer::getAllStatus(), 'type'=>"choice",'default'=>'notapply','null'=>false,),
            array('name'=>'check_words','label'=>'审核意见','type'=>"text",'default'=>'','null'=>false,),
        ));
        $this->list_display=array(
            ['label'=>'买手ID','field'=>function($model){
                return $model->mId;
            }],
            ['label'=>'用户名','field'=>function($model){
                return $model->mName;
            }],
            ['label'=>'发货结算比例','field'=>function($model){
                return $model->mShipPercent;
            }],
            'email',
            ['label'=>'申请状态','field'=>function($model){
                foreach(Buyer::getAllStatus() as $status){
                    if($model->mStatus==$status[0]){
                        return $status[1];
                    }
                }
            }],
            ['label'=>'地址','field'=>function($model){
                return implode(",",[$model->mAddress,$model->mCity,$model->mCountry,/*$model->mProvince,*/]);
            }],
            ['label'=>'注册时间','field'=>function($model){
                return date('Y-m-d H:i:s', $model->mCreateTime);
            }],
        );
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'买手ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'用户名','paramName'=>'name','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'Email','paramName'=>'email','fusion'=>true]),
            new Page_Admin_ChoiceFilter([
                'paramName'=>'status',
                'name'=>'申请状态',
                'choices'=>Buyer::getAllStatus()
            ])
        );
        /*
        $this->list_filter=array(
            new Admin_SiteTagsFilter()
        );
        $this->inline_admin=array(
            new Page_Admin_InlineSiteModule($this,'site_id'),
        );
        $this->multi_actions=array(
            array('label'=>'添加到module','action'=>'javascript:add_to_module();return false;'),
        );*/

        $this->single_actions=[
            ['target'=>'_blank','label'=>'发送环信','action'=>function($model){
                return '/admin/user/send?to='.$model->mEasemobUsername;
            }],
            ['label'=>'直播','action'=>function($model){
                return '/admin/live?__filter='.urlencode('buyer_id='.$model->mId);
            }]
        ];
        
    }
}


