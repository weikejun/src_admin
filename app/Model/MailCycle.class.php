<?php
class Model_MailCycle extends Base_MailCycle{
    public static function getDurationChoices() {
        return [
            ['days','days'],
            ['hours','hours'],
        ];
    }
}
