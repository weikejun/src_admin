<?php
class Model_ContractTerm extends Base_ContractTerm{
    public static function getTradeDocChoices() {
        return [
            ['SPA','SPA'],
            ['SHA','SHA'],
            ['AOA','AOA'],
            ['SRA','SRA'],
            ['ETA','ETA'],
        ];
    }

    public static function getCheckStatusChoices() {
        return [
            ['未审核','未审核'],
            ['已审核','已审核'],
        ];
    }

    public static function getUncheckStatusChoices() {
        return [
            ['草稿','保存草稿'],
            ['未审核','提交审核'],
        ];
    }
}
