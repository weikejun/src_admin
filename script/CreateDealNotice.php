<?php

$strategys = new Model_MailStrategy;
$strategys->addWhere('program', 'common');
$strategys = $strategys->find();
$deals = new Model_Project;
$deals->addWhere('update_time', strtotime('-1 days'), '>=');
$deals = $deals->find();

foreach($strategys as $strategy) {
    $cons = new Model_MailTrigger;
    $cons->addWhere('strategy_id', $strategy->mId);
    $cons = $cons->find();
    if (count($cons) < 1) continue;
    $cycs = new Model_MailCycle;
    $cycs->addWhere('strategy_id', $strategy->mId);
    $cycs = $cycs->find();
    if (count($cycs) < 1) continue;
    foreach($deals as $deal) {
        $bool = true;
        foreach($cons as $con) {
            $conBool = true;
            $field = Form_Project::getFieldNameByView($con->mField);
            if (!$field) break;
            $value = $deal->getData($field);
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
            $cleaner = new Model_MailList;
            $cleaner->addWhere('strategy_id', $strategy->mId);
            $cleaner->addWhere('project_id', $deal->mId);
            $cleaner->addWhere('status', '待发送');
            $cleaner->delete();
            // 插入新邮件 TODO: 生成收件人、标题、内容
            foreach($cycs as $cyc) {
                $field = Form_Project::getFieldNameByView($cyc->mField);
                if (empty($field)) break;
                $value = trim($deal->getData($field));
                if (empty($value)) continue; // 计时起点未填写
                for($i = 0; $i < $cyc->mRepeat; $i++) {
                    $sendTm = strtotime(sprintf('%s %s', $cyc->mDuration, $cyc->mUnit), $value);
                    if ($sendTm < time()) { // 发送时间已过，不再添加
                        continue;
                    }
                    $mail = new Model_MailList;
                    $mail->setData([
                        'status' => '待发送',
                        'strategy_id' => $strategy->mId,
                        'project_id' => $deal->mId,
                        'mail_to' => '',
                        'mail_cc' => '',
                        'title' => $strategy->mTitle,
                        'content' => $strategy->mContent,
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
