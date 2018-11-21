<?php
class Model_Checklist extends Base_Checklist{
    public static function getFieldChoices() {
        return [
            ['LP-合规要求','LP-合规要求'],
            ['LP-filing文件','LP-filing文件'],
            ['基金-合规要求','基金-合规要求'],
            ['基金-filing文件','基金-filing文件'],
            ['其他','其他'],
        ];
    }
}
