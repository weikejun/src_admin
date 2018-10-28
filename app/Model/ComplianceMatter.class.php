<?php
class Model_ComplianceMatter extends Base_ComplianceMatter{
    public static function getCategoryChoices() {
        return [
            ['关联交易','关联交易'],
        ];
    }

    public static function getActionChoices() {
        return [
            ['批准','批准'],
            ['披露','披露'],
            ['手动','手动'],
        ];
    }

    public static function getActionTargetChoices() {
        return [
            ['LPAC','LPAC'],
            ['LP','LP'],
            ['LP大会','LP大会'],
            ['手动','手动'],
        ];
    }
}
