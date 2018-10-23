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
}
