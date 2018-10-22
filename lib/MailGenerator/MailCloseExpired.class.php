<?php

class MailGenerator_MailCloseExpired extends MailGenerator {
    protected function _getTrigger() {
        $deals = new Model_Project;
        $deals->addWhere('status', 'valid');
        $deals->addWhere('update_time', strtotime('-1 days'), '>=');
        $deals->addWhere('close_notice', '开启');
        $deals->addWhereRaw('and ((`proj_status` != "终止不做" and `proj_status` != "已交割付款") or (`close_date` is null or `close_date` = 0 or `close_date` = ""))');
        return $this->_triggers = $deals->find();
    }

    protected function _genCycle($trigger) {
        for($i = 2; $i <= 24; $i++) {
            $expect = $trigger->mCreateTime + $i * 30 * 86400;
            if ($expect > time()) {
                return [$expect];
            }
        }
        return [];
    }
}

