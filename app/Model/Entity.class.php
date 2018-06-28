<?php
class Model_Entity extends Base_Entity{
    public static function getTpChoices() {
        return [
            ['主基金相关','主基金相关'],
            ['专项基金相关','专项基金相关'],
            ['管理公司关联方','管理公司关联方'],
            ['管理公司','管理公司'],
            ['其他','其他'],
        ];
    }

    public static function getCoInvestmentChoices() {
        return [
            ['是','是'],
            ['否','否'],
        ];
    }
}
