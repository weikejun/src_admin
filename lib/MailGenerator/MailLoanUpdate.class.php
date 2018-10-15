<?php

class MailGenerator_MailLoanUpdate extends MailGenerator {
    protected function _getTrigger() {
        $deals = new Model_Project;
        $deals->addWhere('status', 'valid');
        $deals->addWhere('update_time', strtotime('-1 days'), '>=');
        $deals->addWhere('deal_type', '企业融资（源码投）');
        $deals->addWhere('loan_type', '过桥借款或过桥CB ');
        $deals->addWhere('decision_date', '0', '>');
        return $this->_triggers = $deals->find();
    }

    protected function _genCycle($trigger) {
        return [$trigger->mDecisionDate + 7 * 86400];
    }
}

