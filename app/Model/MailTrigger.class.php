<?php
class Model_MailTrigger extends Base_MailTrigger{
    public static function getFieldOprChoices() {
        return [
            ['==','=='],
            ['>','>'],
            ['>=','>='],
            ['<','<'],
            ['<=','<='],
            ['!=','!='],
        ];
    }

    public static function getLogicOprChoices() {
        return [
            ['&&','&&'],
            ['||','||'],
        ];
    }
}
