<?php

class MailGenerator_MailSignUpdate extends MailGenerator {
    protected function _getTrigger() {
        $deals = new Model_Project;
        $deals->addWhere('status', 'valid');
        $deals->addWhere('update_time', strtotime('-1 days'), '>=');
        $deals->addWhere('expect_sign_date', '0', '>');
        $deals->addWhereRaw('and (`deal_type` = "企业融资（源码投）" or `deal_type` = "源码独立CB")');
        return $this->_triggers = $deals->find();
    }

    protected function _genCycle($trigger) {
        return [$trigger->mExpectSignDate - 14 * 86400];
    }
}

