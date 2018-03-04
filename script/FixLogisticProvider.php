<?php

$logistic = new Logistic();
$list = $logistic->addWhere('id', 0, '>')->find();
foreach( $list as $v ) {
    $logistic_provider_fixed = Logistic::getFixedProvider($v->mLogisticProvider);
    $logistic->addWhere('id', $v->mId)->update(array('logistic_provider_fixed'=>$logistic_provider_fixed));
    //var_dump($logistic_provider_fixed, $v->getData()); exit;
    //暂时不推送到kuaidi100，后续有需要可以推送
    //$logistic->registerLogic($v->mLogisticNo, $logistic_provider_fixed);
}

echo 'success!'; exit;
