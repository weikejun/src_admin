<?php

class MailGenerator_MailDealDecision extends MailGenerator {
    protected $_deals;

    protected function _setTplVars($trigger) {
        $vars = parent::_setTplVars($this->_deals[$trigger->mProjectId]);
        $vars['dealDecision'] = $trigger->getData();
        $vars['dealDecision']['partner'] = Model_Member::getEmailById($trigger->mPartner);
        foreach(['pre_money', 'post_money', 'our_amount'] as $i => $key) {
            if ($vars['project'][$key]) {
                $vars['project'][$key] = number_format($vars['project'][$key], 2);
            } else {
                $vars['project'][$key] = '（金额未填写）';
            }
        }
        if (isset($vars['project']['entity_id'])) {
            $entity = new Model_Entity;
            $entity->mId = $vars['project']['entity_id'];
            $entity->select();
            $vars['project']['entity_id'] = $entity->mName;
        }
        return $vars;
    }

    protected function _getTrigger() {
        $decisions = new Model_DealDecision;
        $decisions = $decisions->find();
        $dealIds = [];
        foreach($decisions as $i => $decision) {
            $dealIds[] = $decision->mProjectId;
        }
        $deals = new Model_Project;
        $deals->addWhere('status', 'valid');
        //$deals->addWhere('update_time', strtotime('-1 days'), '>=');
        $deals->addWhere('id', $dealIds, 'IN');
        $deals->addWhere('decision_date', 0, '>');
        $deals = $deals->findMap('id');
        $dealIds = array_keys($deals);
        foreach($decisions as $i => $decision) {
            if (isset($deals[$decision->mProjectId])) {
                $this->_triggers[] = $decision;
            } 
        }
        $this->_deals = $deals;
        return $this->_triggers;
    }

    protected function _genCycle($trigger) {
        $deal = $this->_deals[$trigger->mProjectId];
        foreach([3, 7, 15] as $i => $day) {
            $expect = $deal->mDecisionDate + $day * 86400;
            if ($expect > time() && empty($trigger->mDecision)) {
                return [$expect];
            }
        }
        return [];
    }
}

class MailGenerator_MailDealDecisionExpire extends MailGenerator_MailDealDecision {
    protected function _genCycle($trigger) {
        $deal = $this->_deals[$trigger->mProjectId];
        return [
            $deal->mDecisionDate + 16 * 86400,
        ];
    }
}

