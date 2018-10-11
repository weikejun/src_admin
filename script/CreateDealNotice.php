<?php

$strategys = new Model_MailStrategy;
$strategys = $strategys->find();
/*$deals = new Model_Project;
$deals->addWhere('update_time', strtotime('-1 days'), '>=');
$deals = $deals->find();
 */
$emails = new Model_Member;
$emails = $emails->findMap('id');
$template = DefaultViewSetting::getTemplate();
DefaultViewSetting::setTemplateSetting($template);
$ref = [];
$oMap = [];

foreach($strategys as $strategy) {
    $cons = new Model_MailTrigger;
    $cons->addWhere('strategy_id', $strategy->mId);
    $cons->orderBy('id', 'asc');
    $cons = $cons->find();
    if (count($cons) < 1) continue; // 策略没有触发条件，跳过
    $cycs = new Model_MailCycle;
    $cycs->addWhere('strategy_id', $strategy->mId);
    $cycs->orderBy('id', 'asc');
    $cycs = $cycs->find();
    if (count($cycs) < 1) continue; // 策略没有发送周期，跳过
    $modelClass = 'Model_' . ucfirst($strategy->mRef);
    if (!class_exists($modelClass)) {
        continue;
    }
    $formClass = 'Form_' . ucfirst($strategy->mRef);
    if (!class_exists($formClass)) {
        continue;
    }
    if (!isset($ref[$modelClass])) { // 初始化触发对象，最近24小时有更新的记录
        $model = new $modelClass;
        $model->addWhere('update_time', strtotime('-1 days'), '>=');
        $ref[$modelClass] = $model->find();
    }
    foreach($ref[$modelClass] as $i => $item) {
        $bool = true;
        foreach($cons as $con) {
            $conBool = true;
            $field = $formClass::getFieldNameByView($con->mField);
            if (!$field) break;
            $value = $item->getData($field);
            switch($con->mFieldOpr) {
            case '<':
                $conBool = ($value < $con->mValue);
                break;
            case '>':
                $conBool = ($value > $con->mValue);
                break;
            case '<=':
                $conBool = ($value <= $con->mValue);
                break;
            case '>=':
                $conBool = ($value >= $con->mValue);
                break;
            case '==':
                $conBool = ($value == $con->mValue);
                break;
            case '!=':
                $conBool = ($value != $con->mValue);
                break;
            default:
                $conBool = false;
                break;
            }
            switch($con->mLogicOpr) {
            case '&&':
                $bool = ($bool && $conBool);
                break;
            case '||':
                $bool = ($bool || $conBool);
                break;
            default:
                $bool = false;
                break;
            }
        }
        if ($bool) { // 满足发送条件
            // 清理之前邮件
            if ($strategy->mId == 6) {
                var_dump($item->mId);
            }
            $cleaner = new Model_MailList;
            $cleaner->addWhere('strategy_id', $strategy->mId);
            $cleaner->addWhere('ref', strtolower($strategy->mRef));
            $cleaner->addWhere('ref_id', $item->mId);
            $cleaner->addWhere('status', '待发送');
            $cleaner->delete();
            // 插入新邮件 TODO: 生成收件人、标题、内容
            foreach($cycs as $cyc) {
                $cycModelClass = 'Model_' . ucfirst($cyc->mRef);
                if (!class_exists($cycModelClass)) {
                    continue;
                }
                $cycFormClass = 'Form_' . ucfirst($cyc->mRef);
                if (!class_exists($cycFormClass)) {
                    continue;
                }
                $field = $cycFormClass::getFieldNameByView($cyc->mField);
                if (empty($field)) break;
                $refItem = $item;
                if ($modelClass != $cycModelClass) {
                    $refItem = new $cycModelClass;
                    $refItem->addWhere('id', $item->getData(strtolower($cyc->mRef).'_id'))->select();
                }
                $value = trim($refItem->getData($field));
                if (empty($value)) continue; // 计时起点未填写
                $oMap = [];
                $oMap[get_class($item)] = $item;
                $oMap[get_class($refItem)] = $refItem;
                foreach([$item, $refItem] as $obj) {
                    if (get_class($obj) == 'Model_Project') {
                        $co = new Model_Company;
                        $co->addWhere('id', $obj->mCompanyId);
                        $co->select();
                        $oMap[get_class($co)] = $co;
                    }
                }
                if (isset($oMap['Model_Company'])) {
                    $oMap['Model_Company']->mPartner = Model_Member::getEmailById($oMap['Model_Company']->mPartner);
                    $oMap['Model_Company']->mManager = Model_Member::getEmailById($oMap['Model_Company']->mManager);
                    $oMap['Model_Company']->mLegalPerson = Model_Member::getEmailById($oMap['Model_Company']->mLegalPerson);
                    $oMap['Model_Company']->mFinancePerson = Model_Member::getEmailById($oMap['Model_Company']->mFinancePerson);
                    $oMap['Model_Company']->mFillingKeeper = Model_Member::getEmailById($oMap['Model_Company']->mFillingKeeper);
                }
                if (isset($oMap['Model_DealDecision'])) {
                    $oMap['Model_DealDecision']->mPartner = Model_Member::getEmailById($oMap['Model_DealDecision']->mPartner);
                }
                $template->assign('project', $oMap['Model_Project']);
                $template->assign('company', $oMap['Model_Company']);
                $template->assign('dealDecision', isset($oMap['Model_DealDecision']) ? $oMap['Model_DealDecision'] : null);
                for($i = 0; $i < $cyc->mRepeat; $i++) {
                    $sendTm = strtotime(sprintf('%s %s', $cyc->mDuration * ($i + 1), $cyc->mUnit), $value);
                    if ($sendTm < time()) { // 发送时间已过，不再添加
                        continue;
                    }
                    $mail = new Model_MailList;
                    $mail->setData([
                        'status' => '待发送',
                        'strategy_id' => $strategy->mId,
                        'ref' => strtolower($strategy->mRef),
                        'ref_id' => $item->mId,
                        'mail_to' => $template->fetch('string:'.$strategy->mMailTo),
                        'mail_cc' => '',
                        'title' => $template->fetch('string:'.$strategy->mTitle),
                        'content' => $template->fetch('string:'.$strategy->mContent),
                        'expect_time' => $sendTm,
                        'create_time' => time(),
                        'create_type' => '自动',
                    ]);
                    $mail->save();
                }
            }
        }
    }
}
