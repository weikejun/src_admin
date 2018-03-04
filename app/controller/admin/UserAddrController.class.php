<?php
class UserAddrController extends Page_Admin_Base {
    use Page_Admin_InlineBase;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->addInterceptor(new AdminLogInterceptor());
        $this->model=new UserAddr();
        $this->model->orderBy("id","desc");
        //$this->model->on('beforeinsert','beforeinsert',$this);
        //$this->model->on('beforeupdate','beforeupdate',$this);

        $this->form=new Form(array(
            array('name'=>'user_id','label'=>'用户ID','type'=>"choosemodel",'model'=>'User','default'=>null,'required'=>false,),
            array('name'=>'country','label'=>'国家','type'=>"text",'default'=>'中国','required'=>true,),
            array('name'=>'province','label'=>'省','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'city','label'=>'城市','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'addr','label'=>'地址','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'postcode','label'=>'邮编','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'name','label'=>'姓名','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'cellphone','label'=>'手机','type'=>"text",'default'=>null,'required'=>true,),
            array('name'=>'valid','label'=>'有效','type'=>"hidden",'default'=>'valid','required'=>true,),
            array('name'=>'create_time','label'=>'创建时间','type'=>"datetime",'readonly'=>true,'default'=>null,'required'=>false,),
        ));
        $this->list_display=array(
            ['label'=>'用户ID','field'=>function($model){
                return $model->mUserId;
            }],
            ['label'=>'注册用户名','field'=>function($model){
                $user = new User();
                $user = $user->addWhere('id', $model->mUserId)->select();
                if($user) {
                    return $user->mName;
                } else {
                    return $model->mUserId;
                }
            }],
            ['label'=>'收货信息','field'=>function($model){
                return "$model->mName $model->mPhone $model->mCellphone<br /> $model->mProvince,$model->mCity,$model->mAddr";
            }],
        );
        $this->single_actions=[
            ['target'=>'_blank','label'=>'发送环信','action'=>function($model){
            }],
        ];
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'用户ID','paramName'=>'user_id','fusion'=>false, 'in'=>true]),
            new Page_Admin_TextFilter(['name'=>'用户名','paramName'=>'name','fusion'=>true]),
        );
    }

}


