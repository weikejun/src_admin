<?php
# 负责手机常用功能的工具类
class PhoneUtil{
    public static function sendSMS($phone,$sms){
        SMS::sendSMS($phone, $sms."【淘世界】");
    }
}
