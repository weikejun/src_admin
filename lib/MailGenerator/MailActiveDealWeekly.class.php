<?php

class MailGenerator_MailActiveDealWeekly extends MailGenerator {
    protected function _setTplVars($trigger) {
        $vars['date'] = date('Ymd');
        $listDisplay = [];
        foreach(Form_Project::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator'
                && $field['type'] != 'seperator2') {
                $listDisplay[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }
        $briefFields = [
            Form_Project::getFieldViewName('id') => [],
            Form_Project::getFieldViewName('_company_short') => [],
            Form_Project::getFieldViewName('turn_sub') => [],
            Form_Project::getFieldViewName('deal_type') => [],
            Form_Project::getFieldViewName('decision_date') => [],
            Form_Project::getFieldViewName('deal_progress') => [],
            Form_Project::getFieldViewName('post_money') => [],
            Form_Project::getFieldViewName('our_amount') => [],
            Form_Project::getFieldViewName('financing_amount') => [],
            Form_Project::getFieldViewName('_stock_ratio') => [],
            Form_Project::getFieldViewName('loan_amount') => [],
            Form_Project::getFieldViewName('loan_schedule') => [],
            Form_Project::getFieldViewName('expect_pay_schedule') => [],
            Form_Project::getFieldViewName('manager') => [],
            Form_Project::getFieldViewName('trade_schedule_memo') => [],
        ];

        $vars['list_display'] = [];
        for($i = 0; $i < count($listDisplay); $i++) {
            if (isset($briefFields[$listDisplay[$i]['label']])) {
                $briefFields[$listDisplay[$i]['label']] = $listDisplay[$i];
            }
        }
        $vars['list_display'] = array_values($briefFields);
        
        $deals = new Model_Project;
        $deals->addWhere('status', 'valid');
        $deals->addWhere('active_deal', '是');
        $deals = $deals->find();
        $tmpDeals = [];
        foreach($deals as $i => $deal) {
            $tmpDeals[$deal->mDealType][] = $deal;
        }
        $deals = [];
        // 固定排序
        foreach(['企业融资（源码投）','源码退出'] as $i => $dt) {
            if (isset($tmpDeals[$dt])) {
                $model = new Model_Project;
                $model->mDealType = $dt;
                $deals = array_merge($deals, [$model], $tmpDeals[$dt]);
                unset($tmpDeals[$dt]);
            }
        }
        foreach($tmpDeals as $dt => $tmpDeal) {
            $model = new Model_Project;
            $model->mDealType = $dt;
            $deals = array_merge($deals, [$model], $tmpDeal);
        }
        $vars['data_list'] = $deals;
        return $vars;
    }

    protected function _getTrigger() {
        $weekday = date('w') + 1;
        $hour = date('H');
        $o = new stdClass;
        $o->mId = 0;
        if ($weekday == 1 && $hour == 19 || IS_DEBUG) {
            return $this->_triggers = [$o];
        }
        return [];
    }

    protected function _genCycle($trigger) {
        return [time() + 30 * 60];
    }
}

