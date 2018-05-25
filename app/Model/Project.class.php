<?php
class Model_Project extends Base_Project{
    public static function getItemStatusChoices() {
        return [
            ['已完成','已完成'],
            ['待完成','待完成'],
            ['其他','其他']
        ];
    }

    public static function getTurnChoices() {
        return [
            ['A轮','A轮'],
            ['B轮','B轮'],
            ['C轮','C轮'],
            ['D轮','D轮'],
            ['E轮','E轮'],
            ['F轮','F轮'],
            ['F轮后','F轮后'],
            ['不适用','不适用']
        ];
    }

    public static function getNewFollowChoices() {
        return [
            ['new','new'],
            ['follow on','follow on'],
            ['其他','其他']
        ];
    }

    public static function getEnterExitTypeChoices() {
        return [
            ['领投','领投'],
            ['跟投','跟投'],
            ['不跟投','不跟投'],
            ['部分退出','部分退出'],
            ['全部退出','全部退出'],
            ['清算退出','清算退出'],
            ['重组','重组'],
            ['上市','上市'],
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
