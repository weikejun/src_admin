<?php
class Model_Project extends Base_Project{
    public static function getPendingChoices() {
        return [
            ['有','有'],
            ['无','无'],
            ['已完成','已完成'],
        ];
    }

    public static function getAicRegistraionChoices() {
        return [
            ['已办理','已办理'],
            ['待办理','待办理'],
            ['无需办理','无需办理'],
            ['不适用','不适用'],
            ['其他','其他'],
        ];
    }

    public static function getStandardArchiveChoices() {
        return [
            ['已存档','已存档'],
            ['待存档','待存档'],
            ['无需存档','无需存档'],
            ['不适用','不适用'],
            ['其他','其他'],
        ];
    }

    public static function getMirrorHoldRatioChoices() {
        return [
            ['象征性','象征性'],
            ['mirror','mirror'],
            ['其他','其他'],
        ];
    }

    public static function getMirrorHoldChoices() {
        return [
            ['RMB项目不适用','RMB项目不适用'],
            ['USD项目有镜像','USD项目有镜像'],
            ['USD项目无镜像','USD项目无镜像'],
            ['其他','其他'],
        ];
    }

    public static function getEntityOdiChoices() {
        return [
            ['不涉及ODI','不涉及ODI'],
            ['可能要做ODI','可能要做ODI'],
            ['已做ODI','已做ODI'],
            ['其他','其他'],
        ];
    }

    public static function getRightUpdateChoices() {
        return [
            ['已更新','已更新'],
            ['待更新','待更新'],
            ['无须更新','无须更新'],
            ['不适用','不适用'],
            ['其他','其他'],
        ];
    }

    public static function getAntiDilutionWayChoices() {
        return [
            ['完全棘轮','完全棘轮'],
            ['狭义加权平均','狭义加权平均'],
            ['广义加权平均','广义加权平均'],
            ['其他','其他']
        ];
    }

    public static function getLiquidationPreferenceWayChoices() {
        return [
            ['参与分配','参与分配'],
            ['不参与分配','不参与分配'],
            ['其他','其他'],
            ['不适用','不适用'],
        ];
    }

    public static function getBuybackObligorChoices() {
        return [
            ['仅公司','仅公司'],
            ['仅创始人','仅创始人'],
            ['公司加创始人','公司加创始人'],
            ['不适用','不适用'],
        ];
    }

    public static function getStandardVetoChoices() {
        return [
            ['有独立veto','有独立veto'],
            ['有不独立veto','有不独立veto'],
            ['没有','没有'],
            ['个别关键事项否决权','个别关键事项否决权']
        ];
    }

    public static function getObserverChoices() {
        return [
            ['有席位','有席位'],
            ['无席位','无席位'],
            ['不适用','不适用'],
        ];
    }

    public static function getStandardRightChoices() {
        return [
            ['约定有','约定有'],
            ['约定无','约定无'],
            ['无特别约定','无特别约定'],
            ['不适用','不适用'],
        ];
    }

    public static function getOurBoardRegisterChoices() {
        return [
            ['已完成','已完成'],
            ['未完成','未完成'],
            ['待完成','待完成'],
            ['待变更登记','待变更登记'],
            ['其他','其他'],
        ];
    }

    public static function getOurBoardStatusChoices() {
        return [
            ['在职','在职'],
            ['退出席位','退出席位'],
            ['待更换','待更换'],
            ['其他','其他'],
        ];
    }

    public static function getStandardOptionChoices() {
        return [
            ['有','有'],
            ['没有','没有'],
        ];
    }

    public static function getStandard3OptionChoices() {
        return [
            ['有','有'],
            ['没有','没有'],
            ['不适用','不适用'],
        ];
    }

    public static function getStandard4OptionChoices() {
        return [
            ['有','有'],
            ['没有','没有'],
            ['不适用','不适用'],
            ['其他','其他'],
        ];
    }

    public static function getStandardSelectInputChoices() {
        return [
            ['场景不适用','场景不适用'],
            ['没有','没有'],
            ['待确认','待确认'],
            ['略','略'],
            ['其他','其他']
        ];
    }

    public static function getFirstFinancingChoices() {
        return [
            ['是','是'],
            ['否','否'],
            ['不适用','不适用']
        ]; 
    }

    public static function getShareholdingESOPHolderChoices() {
        return [
            ['founder代持','founder代持'],
            ['持股平台持有','持股平台持有'],
            ['其他','其他']
        ];
    }

    public static function getStockPropertyChoices() {
        return [
            ['优先股','优先股'],
            ['受限普通股','受限普通股'],
            ['不受限普通股','不受限普通股'],
            ['全部股东同股同权','全部股东同股同权'],
            ['其他','其他'],
        ];
    }

    public static function getOtherInvestorChoices() {
        return [
            ['有','有'],
            ['无','无'],
            ['不适用','不适用']
        ];
    }

    public static function getCurrencyChoices() {
        return [
            ['USD','USD'],
            ['RMB','RMB'],
            ['HKD','HKD'],
            ['其他','其他'],
        ];
    }

    public static function getValueCurrencyChoices() {
        return [
            ['USD','USD'],
            ['RMB','RMB'],
            ['HKD','HKD']
        ];
    }

    public static function getLoanProcessChoices() {
        return [
            ['待处理','待处理'],
            ['已直接债转股','已直接债转股'],
            ['已归还作为投资款支付','已归还作为投资款支付'],
            ['已清偿','已清偿'],
            ['已坏账','已坏账'],
            ['其他','其他'],
        ];
    }

    public static function getLoanTypeChoices() {
        return [
            ['过桥借款或过桥CB','过桥借款或过桥CB'],
            ['独立非过桥CB','独立非过桥CB'],
            ['其他','其他'],
        ];
    }

    public static function getExitProfitChoices() {
        return [
            ['溢价退出','溢价退出'],
            ['平价退出','平价退出'],
            ['残值退出','残值退出'],
            ['零对价退出','零对价退出'],
            ['其他','其他'],
        ];
    }

    public static function getExitTypeChoices() {
        return [
            ['股权出售','股权出售'],
            ['并购重组','并购重组'],
            ['源码转关联方','源码转关联方'],
            ['IPO','IPO'],
            ['创始人或公司回购','创始人或公司回购'],
            ['视同清算','视同清算'],
            ['实际清算','实际清算'],
            ['其他','其他'],
        ];
    }

    public static function getExitCurrencyChoices() {
        return [
            ['USD','USD'],
            ['RMB','RMB'],
            ['HKD','HKD'],
            ['其他','其他'],
        ];
    }

    public static function getLoanCurrencyChoices() {
        return [
            ['USD','USD'],
            ['RMB','RMB'],
            ['HKD','HKD'],
            ['其他','其他'],
        ];
    }

    public static function getInvestCurrencyChoices() {
        return [
            ['USD','USD'],
            ['RMB','RMB'],
            ['HKD','HKD'],
            ['其他','其他'],
        ];
    }

    public static function getResConsiderationChoices() {
        return [
            ['有','有'],
            ['没有','没有'],
            ['不适用','不适用'],
        ];
    }

    public static function getNewOldStockChoices() {
        return [
            ['新股','新股'],
            ['老股','老股'],
            ['未投资','未投资'],
            ['不适用','不适用'],
            ['其他','其他']
        ];
    }

    public static function getProjStatusChoices() {
        return [
            ['进展中','进展中'],
            ['已签署待交割','已签署待交割'],
            ['已交割付款','已交割付款'],
            ['暂停','暂停'],
            ['终止不做','终止不做'],
            ['其他','其他']
        ];
    }

    public static function getDealTypeChoices() {
        return [
            ['源码投','源码投'],
            ['企业融资（源码不投）','企业融资（源码不投）'],
            ['源码退出','源码退出'],
            ['源码独立CB','源码独立CB'],
            ['重组','重组'],
            ['IPO','IPO'],
            ['其他','其他']
        ];
    }

    public static function getItemStatusChoices() {
        return [
            ['已完成','已完成'],
            ['待完成','待完成'],
            ['其他','其他']
        ];
    }

    public static function getTurnChoices() {
        return [
            ['A前','A前'],
            ['A','A'],
            ['B','B'],
            ['C','C'],
            ['D','D'],
            ['E','E'],
            ['F','F'],
            ['F后','F后'],
            ['不适用','不适用']
        ];
    }

    public static function getNewFollowChoices() {
        return [
            ['新项目','新项目'],
            ['老项目','老项目'],
            ['其他','其他']
        ];
    }

    public static function getOtherEnterExitTypeChoices() {
        return [
            ['企业无其他投资人','企业无其他投资人'],
            ['新股','新股'],
            ['老股','老股'],
            ['新股+老股','新股+老股'],
            ['未投资','未投资'],
            ['不适用','不适用'],
            ['其他','其他']
        ];
    }

    public static function getEnterExitTypeChoices() {
        return [
            ['领投','领投'],
            ['跟投','跟投'],
            ['不投资','不投资'],
            ['部分退出','部分退出'],
            ['全部退出','全部退出'],
            ['清算退出','清算退出'],
            ['目标企业上市','目标企业上市'],
            ['其他','其他']
        ];
    }

    public static function getFieldViewName($field) {
        $map = [
            'id' => '交易ID',
            'company_id' => '公司名称',
            'code' => '项目编号',
            'item_status' => '整理状态',
            'turn' => '轮次大类',
            'turn_sub' => '轮次详情',
            'new_follow' => '新老类型',
            'enter_exit_type' => '投退类型',
            'new_old_stock' => '新股老股',
            'decision_date' => '决策日期',
            'proj_status' => '交易状态',
            'close_date' => 'Close Date',
            'law_firm' => '负责律所',
            'observer' => '观察员',
            'info_right' => '信息权',
            'info_right_threshold' => '信息权门槛',
            'currency' => '计价货币',
            'pre_money' => '公司投前估值',
            'financing_amount' => '本轮融资总额',
            'value_change' => '公司估值涨幅',
            'entity_id' => '源码投退主体',
            'rmb_usd' => 'RMB/USD',
            'period' => '期数/专项',
            'mirror' => '镜像持股',
            'entrustment' => '基金代持及主体',
            'our_amount' => '源码投资金额',
            'other_amount' => '其他投资人及金额',
            'amount_memo' => '金额备注',
            'loan' => '借款/CB',
            'loan_memo' => '借款备注',
            'loan_expiration' => '借款到期日',
            'stocknum_all' => '投时公司总股数',
            'stocknum_get' => '投时持有本轮股数',
            'stocknum_new' => '源码持有最新股数',
            'shareholding_member' => '团队持股比例',
            'shareholding_esop' => 'ESOP',
            'term_limit' => '投资限制',
            'term_limit_other' => '对投资人其他限制或责任',
            'term_stock_transfer_limit' => '源码转竞品限制',
            'term_stock_transfer_limit_other' => '源码转让其他限制',
            'term_limit_other' => '对投资人其他限制或责任',
            'term_founder_transfer_limit' => '创始人转让限制',
            'term_holder_veto' => '股东会veto',
            'term_board_veto' => '董事会veto',
            'term_preemptive' => '优先认购权',
            'term_pri_assignee' => '对创始人优先受让权',
            'term_sell_together' => '对创始人共售权',
            'term_pri_common_stock' => '对普通股优先权',
            'term_buyback_standard' => '回购金额标准',
            'term_buyback_start' => '回购起算日',
            'term_buyback_period' => '回购年限',
            'term_buyback_date' => '本轮可回购时间',
            'term_waiting_period' => '等待期年限',
            'term_ipo_period' => '合格IPO年限',
            'term_anti_dilution' => '反稀释方法',
            'term_liquidation_preference' => '优先清算权方法',
            'term_drag_along_right' => '拖售权',
            'term_dar_illustrate' => '拖售权说明',
            'term_warrant' => 'Warrant',
            'term_dividends_preference' => '优先分红权',
            'term_valuation_adjustment' => '对赌/估值调整',
            'term_spouse_consent' => '配偶同意函',
            'term_longstop_date' => 'Longstop date',
            'term_important_changes' => '相对上轮重大变化',
            'term_good_item' => '不常见好条款摘选',
            'arch_final_captalbe' => 'Final Captalbe',
            'arch_final_word' => 'Final Word',
            'arch_closing_pdf' => 'Closing PDF',
            'arch_closing_original' => 'Closing原件',
            'arch_overseas_stockcert' => '境外股票证书',
            'arch_aic_registration' => '境内工商登记',
            'pending_detail' => '未决事项说明',
            'work_memo' => '工作备忘',
            'update_time' => '更新时间',
        ];
        return isset($map[$field]) ? $map[$field] : $field;
    }
}
