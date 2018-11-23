<?php
class Model_FundLp extends Base_FundLp{
    public static function getCertTypeChoices() {
        return [
            ['营业执照','营业执照'],
            ['身份证','身份证'],
            ['备案编码','备案编码'],
            ['护照','护照'],
        ];
    }

    public static function getJoinWayChoices() {
        return [
            ['新增份额','新增份额'],
            ['受让老份额','受让老份额'],
            ['新增+受让','新增+受让'],
            ['其他','其他'],
        ];
    }

    public static function getPartnerTypeChoices() {
        return [
            ['LP','LP'],
            ['GP','GP'],
            ['其他','其他'],
        ];
    }

    public static function getSubscriberOrgChoices() {
        return [
            ['自然人','自然人'],
            ['合伙','合伙'],
            ['公司','公司'],
        ];
    }

    public static function getHaveNotChoices() {
        return [
            ['有','有'],
            ['无','无'],
        ];
    }

    public static function getYesNoChoices() {
        return [
            ['是','是'],
            ['否','否'],
        ];
    }

    public static function getCompleteChoices() {
        return [
            ['完整','完整'],
            ['ecopy已全待邮寄','ecopy已全待邮寄'],
            ['e/hard copy均缺','e/hard copy均缺'],
            ['无须','无须'],
        ];
    }

    public static function getDocOptionChoices() {
        return [
            ['完整','完整'],
            ['不完整','不完整'],
            ['无须','无须'],
        ];
    }

    public static function getInvestorTypeChoices() {
        return [
            ['专业','专业'],
            ['普通','普通'],
            ['当然合格投资者','当然合格投资者'],
            ['其他','其他'],
        ];
    }

    public static function getCoolingoffPeriodChoices() {
        return [
            ['无须','无须'],
            ['可豁免','可豁免'],
            ['已操作','已操作'],
            ['待操作','待操作'],
        ];
    }

    public static function getComplianceCheckChoices() {
        return [
            ['待核对','待核对'],
            ['核对已满足','核对已满足'],
            ['不适用','不适用'],
        ];
    }

    public static function getFillingListCheckChoices() {
        return [
            ['待核对','待核对'],
            ['核对完整','核对完整'],
            ['不适用','不适用'],
        ];
    }

    public static function getMailStatusChoices() {
        return [
            ['是','是'],
            ['否','否'],
            ['无须','无须'],
        ];
    }

    public static function getIsExitChoices() {
        return [
            ['未退伙','未退伙'],
            ['已退伙','已退伙'],
        ];
    }
}
