<?php
class Base_User extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'name','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'phone','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'password','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'valid','type'=>"string",'key'=>false,'defalut'=>'valid','null'=>false,],
            ['name'=>'phone_verified','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'phone1','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'qq','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'email_verified','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'email','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'address','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'nick','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'city','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'province','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'country','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'weixin_id','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'weibo_id','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'grade','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'gender','type'=>"string",'key'=>false,'defalut'=>'woman','null'=>false,],
            ['name'=>'birthday','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'married','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'year_income','type'=>"int",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'interests','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'id_num','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'create_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'last_update_time','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'avatar_url','type'=>"string",'key'=>false,'defalut'=>'users/noAvatar.jpg','null'=>true,],
            ['name'=>'question','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'answer','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'points','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'wx_openid','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'third_platform_type','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'wx_accesstoken','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'wx_createtime','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'wx_refreshtoken','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'easemob_username','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>true,],
            ['name'=>'easemob_password','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'source','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}