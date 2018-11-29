<?php
class Model_ComplianceMatter extends Base_ComplianceMatter{
    public static function getContrainedEntitysChoices() {
        return [
            ['仅本基金','仅本基金'],
            ['全部基金','全部基金'],
            ['全部美元基金','全部美元基金'],
            ['全部人民币基金','全部人民币基金'],
            ['管理公司及其关联方','管理公司及其关联方'],
        ];
    }

    public static function getPotenceChoices() {
        return [
            ['有效','有效'],
            ['失效','失效'],
            ['其他','其他'],
        ];
    }

    public static function getLimitSourceTypeChoices() {
        return [
            ['基金LPA','基金LPA'],
            ['基金SL','基金SL'],
            ['法律法规','法律法规'],
            ['投资交易文件','投资交易文件'],
            ['非投资交易文件','非投资交易文件'],
        ];
    }

    public static function getCategoryChoices() {
        return [
            ['新基金及其他利冲','新基金及其他利冲'],
            ['关联交易','关联交易'],
            ['投资限制及要求','投资限制及要求'],
            ['信息披露','信息披露'],
            ['期限相关','期限相关'],
            ['GPLP缴纳出资','GPLP缴纳出资'],
            ['管理人相关','管理人相关'],
            ['关键人士相关','关键人士相关'],
            ['GPLP合伙份额转退','GPLP合伙份额转退'],
            ['co-investment','co-investment'],
            ['Fee and Carry','Fee and Carry'],
            ['借款与担保','借款与担保'],
            ['项目转让','项目转让'],
            ['其他','其他'],
        ];
    }

    public static function getActionReqChoices() {
        return [
            ['批准','批准'],
            ['披露','披露'],
            ['不适用','不适用'],
            ['禁止','禁止'],
        ];
    }

    public static function getActionTargetChoices() {
        return [
            ['LPAC','LPAC'],
            ['LP大会/全体LP','LP大会/全体LP'],
            ['不适用','不适用'],
        ];
    }

    public static function getActionFreqChoices() {
        return [
            ['事项发生前','事项发生前'],
            ['月度','月度'],
            ['季度','季度'],
            ['年度','年度'],
        ];
    }
}
