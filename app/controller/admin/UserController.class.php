<?php
require_once("UserAddrController.class.php");
class UserController extends Page_Admin_Base {
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new User();
        $this->model->orderBy("id","desc");

        $this->form=new Form(array(
            array('name'=>'name',      'label'=>'用户名','readonly'=>true,'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'password',      'type'=>"password",'default'=>null,'required'=>false,),
            array('name'=>'password_again','type'=>"password",'default'=>null,'required'=>false,'validator'=>function($values){
                if(!$values['password']||
                    ($values['password']&&md5($values['password_again'])==$values['password'])){
                    return true;
                }else{
                    return "please retype the password";
                }
            }),
            array('name'=>'phone',      'type'=>"text",'default'=>null,'required'=>false,'readonly'=>true),
            array('name'=>'qq',      'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'email',      'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'email_verified',      'type'=>"choice",
                'choices'=>[[0,'未验证'],[1,'已验证']],
                'default'=>null,'required'=>false,),
            array('name'=>'city',      'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'province',      'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'country',      'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'birthday',    'label'=>'生日',  'type'=>"date",'default'=>null,'required'=>false,),
            array('name'=>'question',      'label'=>'安全问题','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'answer',      'label'=>'安全问题答案','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'id_num',      'label'=>'身份证号','type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'avatar_url',      'label'=>'头像','type'=>"simpleFile",'default'=>null,'required'=>false,),
            array('name'=>'easemob_username','label'=>'环信用户名','readonly'=>true,'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'easemob_password','label'=>'环信密码','readonly'=>true,'type'=>"text",'default'=>null,'required'=>false,),
            array('name'=>'valid',"choices"=>array(array('valid',"有效"),array('invalid',"无效"),), 'type'=>"choice",'default'=>'valid','null'=>false,),
        ));
        $this->list_display=array('id','name','email',
            array('label'=>'电话','field'=>function($model){
                return $model->mPhone;
            }),
            array('label'=>'手机验证', 'field'=>function($model) {
                return $model->mPhoneVerified ? '是' : '否';
            }),
            array('label'=>'地址','field'=>function($model){
                $addrs = new UserAddr();
                $addrs = $addrs->addWhere('user_id', $model->mId)->find();
                $addrs = array_map(function($addr) {
                    return "$addr->mName $addr->mPhone $addr->mCellphone<br /> $addr->mProvince,$addr->mCity,$addr->mAddr";
                }, $addrs);
                return implode("<br />",$addrs);
            }),
            array('label'=>'终端','field'=>function($model){
                if(stripos($model->mSource,'iphone') || stripos($model->mSource,'ipad')) {
                    return 'iOS设备';
                } elseif(stripos($model->mSource,'android')) {
                    return 'Android设备';
                }
                return '未知';
            }),
            array('label'=>'注册时间','field'=>function($model){
                return date("Y-m-d H:i:s", $model->mCreateTime);
            }),
        );
        /*
        $this->list_filter=array(
            new Admin_SiteTagsFilter()
        );
        $this->inline_admin=array(
            new Page_Admin_InlineSiteModule($this,'site_id'),
        );
         */
        $inlineUserAddr = new UserAddrController();
        $inlineUserAddr->setForeignKeyName("user_id");
        $this->inline_admin=array(
            $inlineUserAddr
        );

        $this->multi_actions=array(
            //__ids_json__
            array('label'=>'alert','action'=>'javascript:alert(__ids_json__);'),
            //__ids__会被替换成逗号分隔的id
            array('label'=>'multi','action'=>'/admin/user/multi?ids=__ids__'),
        );
        $this->single_actions=[
            ['target'=>'_blank','label'=>'发送环信','action'=>function($model){
                return '/admin/user/send?to='.$model->mEasemobUsername;
            }],
        ];
        
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'手机号','paramName'=>'phone','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'用户名','paramName'=>'name','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'用户ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextFilter(['name'=>'终端','paramName'=>'source','fusion'=>true]),
            new Page_Admin_TextFilter(['name'=>'email','paramName'=>'email','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>'注册时间','paramName'=>'create_time']),
        );
        $this->multi_actions=array(
            array('label'=>'导出全部','required'=>false,'action'=>'/admin/user/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        );
    }
    public function display_ctime($modelData){
        return strftime("%Y-%m-%d",$modelData->mCreateTime);
    }
    public function sendAction(){
        $to=$this->_POST("to");
        $msg=$this->_POST("msg");
        $type=$this->_POST("type","admin");
        $from=$this->_POST("from","admin");
        $order_id=$this->_POST("order_id");
        $trade_title=$this->_POST("trade_title");
        //$stock_imageUrl=json_decode($this->_POST("stock_imageUrl"),true);
        //$stock_imageUrl=$stock_imageUrl?$stock_imageUrl:[];
        $stock_imageUrl=$this->_POST("stock_imageUrl");
        if($to&&$msg){
            $notify=Easemob::getInstance()->sendMsg($to,$msg,$type,$from,
                    [
                        'order_id'=>$order_id,
                        'trade_title'=>$trade_title,
                        'stock_imageUrl'=>$stock_imageUrl,
                    ]
            );
            return ["redirect:/admin/user/send?from=$from&to=$to&type=$type&order_id=$order_id&trade_title=$trade_title&stock_imageUrl=".urlencode($stock_imageUrl)."&notify=".urlencode(var_export($notify,true))];
        }
        return ["cadmin/user/send_easemob.tpl",$_GET];

    }

    use ExportToCsvAction;

}


