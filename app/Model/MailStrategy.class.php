<?php
class Model_MailStrategy extends Base_MailStrategy{
    public static function getProgramChoices() {
        return [
            ['common', '收件人同时发送'],
            ['onebyone', '收件人逐个发送'],
        ];
    }

    public static function getRefChoices() {
        return [
            ['Project', '交易记录'],
            ['Company', '目标企业'],
            ['DealDecision', '投决意见'],
            ['_SysDate', '*系统时间'],
        ];
    }
}
