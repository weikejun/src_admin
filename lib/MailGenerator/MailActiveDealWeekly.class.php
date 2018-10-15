<?php

class MailGenerator_MailActiveDealWeekly extends MailGenerator {
    protected function _setTplVars($trigger) {
        $vars['date'] = date('Ymd');
        $briefFields = [
            'id' => [],
            '_company_short' => [],
            'deal_type' => [],
            'deal_progress' => [],
            'manager' => [],
            'decision_date' => [],
            'pre_money' => [],
            'post_money' => [],
            'financing_amount' => [],
            'ts_ratio' => [],
            '_stock_ratio' => [],
            'loan_amount' => [],
            'loan_schedule' => [],
            'trade_file_schedule' => [],
            'expect_pay_schedule' => [],
            'trade_schedule_memo' => [],
        ];
        $vars['list_display'] = [];
        foreach(Form_Project::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator'
                && $field['type'] != 'seperator2') {
                if (isset($briefFields[$field['name']])) {
                    $vars['list_display'][] = [
                        'name' => $field['name'],
                        'label' => $field['label'],
                        'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                    ];
                }
            }
        }
        $deals = new Model_Project;
        $deals->addWhere('status', 'valid');
        $deals->addWhere('active_deal', '是');
        $vars['data_list'] = $deals->find();
        return $vars;
    }

    protected function _getTrigger() {
        $weekday = date('w') + 1;
        $hour = date('H');
        $o = new stdClass;
        $o->mId = 0;
        if ($weekday == 1 && $hour == 22) {
            return $this->_triggers = [$o];
        }
        return [];
    }

    protected function _genCycle($trigger) {
        return [time() + 30 * 60];
    }
}

