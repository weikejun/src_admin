<?php
class Model_MailStrategy extends Base_MailStrategy{
    public static function getProgramChoices() {
        return [
            ['common', 'common'],
            ['decision', 'decision'],
        ];
    }
}
