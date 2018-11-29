<?php

class MailGenerator_MailClosingOriginal extends MailGenerator {
    protected function _getTrigger() {
        $deals = new Model_Project;
        $deals->addWhere('status', 'valid');
        $deals->addWhere('close_date', 0, '>');
        $deals->addWhereRaw('and (`closing_original` = "待存档" or `closing_original` = "" or `closing_original` is null)');
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

