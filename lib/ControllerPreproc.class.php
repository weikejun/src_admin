<?php

trait ControllerPreproc{
    public function preMethod($tAction) {
        $logger = new Model_SystemLog();
        $logFun = function($model) use(&$logger, $tAction) {
            $logger->doLog($model, $tAction);
        };
        $this->model
            ->on('after_insert', $logFun)
            ->on('after_delete', $logFun)
            ->on('after_update', $logFun);
    }
}
