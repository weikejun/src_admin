<?php
class Model_Entity extends Base_Entity{
    public static function getFundTeamChoices() {
        return [
            ['总负责人','总负责人'],
            ['IR负责人','IR负责人'],
            ['财务负责人','财务负责人'],
            ['法务负责人','法务负责人'],
        ];
    }

    public static function getToDoChoices() {
        return [
            ['有','有'],
            ['无','无'],
        ];
    }

    public static function getAicChangeChoices() {
        return [
            ['已完成','已完成'],
            ['待完成工商','待完成工商'],
            ['无须备案','无须备案'],
        ];
    }

    public static function getPutOnRecordChoices() {
        return [
            ['已备案','已备案'],
            ['待完成备案','待完成备案'],
            ['不适用','不适用'],
        ];
    }

    public static function getTrusteeshipChoices() {
        return [
            ['已托管','已托管'],
            ['待托管','待托管'],
            ['不托管','不托管'],
        ];
    }

    public static function getFdpeCodeChoices() {
        return [
            ['已完成','已完成'],
            ['待完成','待完成'],
            ['无须','无须'],
        ];
    }

    public static function getMfnChoices() {
        return [
            ['已完成','已完成'],
            ['待完成','待完成'],
            ['无须','无须'],
        ];
    }

    public static function getRankMaterialChoices() {
        return [
            ['有','有'],
            ['待补','待补'],
            ['无须','无须'],
        ];
    }

    public static function getDmMaterialChoices() {
        return [
            ['有','有'],
            ['待补','待补'],
            ['无须','无须'],
        ];
    }

    public static function getAssociationCateChoices() {
        return [
            ['私募创业投资','私募创业投资'],
            ['私募股权投资','私募股权投资'],
            ['不适用','不适用'],
        ];
    }

    public static function getOrgTypeChoices() {
        return [
            ['合伙企业','合伙企业'],
            ['公司','公司'],
            ['其他','其他'],
        ];
    }

    public static function getCateChoices() {
        return [
            ['集合基金','集合基金'],
            ['专项基金','专项基金'],
            ['SPV','SPV'],
            ['其他','其他'],
        ];
    }

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
