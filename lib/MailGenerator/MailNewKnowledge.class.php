<?php

class MailGenerator_MailNewKnowledge extends MailGenerator {
    protected $_deals;

    protected function _setTplVars($trigger) {
        $vars['knowledgeList'] = $trigger->getData();
        $admin = new Model_Admin;
        $admin->addWhere('name', $vars['knowledgeList']['operator']);
        $admin->select();
        if ($admin->mId) {
            $vars['knowledgeList']['operator'] = $admin->mRealName;
        }
        return $vars;
    }

    protected function _getTrigger() {
        $know = new Model_KnowledgeList;
        $know->addWhere('create_time', time() - 86400, '>=');
        $this->_triggers = [];
        foreach($know->find() as $k) {
            $mail = new Model_MailList;
            $mail->addWhere('strategy_id', $this->_mailTpl->mId);
            $mail->addWhere('ref_id', $k->mId);
            $mail->addWhere('ref', $this->_refType);
            if ($mail->count()) continue;
            $this->_triggers[] = $k;
        }
        return $this->_triggers;
    }

    protected function _genCycle($trigger) {
        return [time()];
    }

    protected function _clear($refId) {}
}
