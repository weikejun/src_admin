<?php

class MailGenerator_MailComplianceReviewSpecial extends MailGenerator {
    protected function _getTrigger() {
        $deals = new Model_Project;
        $deals->addWhere('status', 'valid');
        $deals->addWhere('update_time', time() - 86400, '>=');
        $deals->addWhere('expect_sign_date', '0', '>');
        $deals->addWhereRaw('and (`deal_type` = "企业融资（源码投）" or `deal_type` = "源码独立CB")');
        $entitys = new Model_Entity;
        $entitys->addWhere('name', '苏州源瀚股权投资合伙企业（有限合伙）');
        $entitys->addWhere('name', '苏州源启股权投资中心（有限合伙）', '=', 'or');
        $entitys = $entitys->findMap('id');
        $entityIds = array_keys($entitys);
        $deals = $deals->findMap('id');
        $this->_triggers = [];
        foreach($deals as $dealId => $deal) {
            if (in_array($deal->mEntityId, $entityIds)
                || in_array($deal->mLoanEntityId, $entityIds)) {
                $this->_triggers[] = $deal;
            }
        }
        return $this->_triggers;
    }

    protected function _genCycle($trigger) {
        $expect = $trigger->mExpectSignDate - 14 * 86400;
        if ($expect > time()) {
            return [$expect];
        }

        return [];
    }
}
