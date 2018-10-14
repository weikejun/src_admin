<?php
class Model_MailList extends Base_MailList{
    public static function getStatusChoices() {
        return [
            ['待发送','待发送'],
            ['发送中','发送中'],
            ['已发送','已发送'],
            ['发送失败','发送失败'],
        ];
    }
}
