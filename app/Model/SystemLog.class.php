<?php
class Model_SystemLog extends Base_System_Log{
    public function doLog($model, $action) {
        $context = WinRequest::getModel('executeInfo');
        $detailStr = '';
        foreach($model->getData() as $key => $value) {
            if (trim($value) !== '') {
                $detailStr .= "$key=$value|";
            }
        }
        $this->setData([
            'operator_id' => Model_Admin::getCurrentAdmin()->mId,
            'operator_ip' => Utils::getClientIP(),
            'resource' => $context['controllerName'],
            'res_id' => $model->mId,
            'action' => $action,
            'method' => str_replace('Action', '', $context['methodName']),
            'create_time' => time(),
            'detail' => $detailStr,
        ]);
        $this->save();
    }
}
