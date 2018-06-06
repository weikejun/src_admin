<?php
class Model_Company extends Base_Company{
    public static function getHoldStatusChoices() {
        return [
            ['完全持有','完全持有'],
            ['部分退出','部分退出'],
            ['全部退出','全部退出'],
            ['目标企业上市','目标企业上市'],
            ['清算退出','清算退出'],
            ['其他','其他'],
        ];
    }

    public static function getBussinessChoices() {
        return [
            ['消费','消费'],
            ['教育','教育'],
            ['Fintech','Fintech'],
            ['产业互联网','产业互联网'],
            ['出海','出海'],
            ['文娱','文娱'],
            ['社交','社交'],
            ['微信生态','微信生态'],
            ['汽车出行','汽车出行'],
            ['企业服务','企业服务'],
            ['医疗医美','医疗医美'],
            ['保险','保险'],
            ['房','房'],
            ['Enabling Tech','Enabling Tech'],
            ['AI','AI'],
            ['物流','物流'],
            ['电商','电商'],
            ['品牌','品牌'],
            ['O2O','O2O'],
            ['餐饮','餐饮'],
            ['其他','其他'],
        ];
    }

    public static function getRegionChoices() {
        return [
            ['北京','北京'],
            ['天津','天津'],
            ['广东','广东'],
            ['深圳','深圳'],
            ['上海','上海'],
            ['浙江','浙江'],
            ['苏州','苏州'],
            ['成渝地区','成渝地区'],
        ];
    }
}
