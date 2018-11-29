<?php

class MailGenerator_MailAicAndFinalClosing extends MailGenerator {
    protected function _getTrigger() {
        $deals = new Model_Project;
        $deals->addWhere('status', 'valid');
        $deals->addWhere('close_date', 0, '>');
        $deals->addWhereRaw('and (`deal_type` = "企业融资（源码投）" or `deal_type` = "源码退出" or `deal_type` = "源码独立CB" or `deal_type` = "重组") and (`aic_registration` = "待办理" or `aic_registration` = "" or `aic_registration` is null)');
        return $this->_triggers = $deals->find();
    }

    protected function _genCycle($trigger) {
        for($i = 1; $i <= 100; $i++) {
            $expect = $trigger->mCloseDate + $i * 10 * 86400;
            if ($expect > time()) {
                return [$expect];
            }
        }
        return [];
    }
}

