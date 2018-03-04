<?php
function doPush($userIds, &$task) {
    $offset = 20;
    for($i = 0; $i < count($userIds); $i+=$offset) {
        $sendUsers = array_slice($userIds, $i, $offset);
        $notify=Easemob::getInstance()->sendMsg(
            $sendUsers,
            $task->mContent,
            'admin',
            'admin',
            ['order_id' => '','trade_title' => '','stock_imageUrl' => '',]
        );
        var_dump($notify['data']);
        if(!$notify['data']) {
            $task->mFail += $offset;
        }
        foreach($notify['data'] as $key => $status) {
            if($status == 'success') {
                $task->mSuccess++;
            } else {
                $task->mFail++;
            }
        }
        if($i%1000 == 0) {
            $task->save();
        }
    }
}

function pushMsg(&$users, &$task, $fieldsName, $resPerPage) {
    $count = $users->count();
    for($i = 0; $i < $count/$resPerPage; $i++) {
        $users->limit($i*$resPerPage, $resPerPage);
        $userData = $users->findMap($fieldsName);
        $userIds = array_keys($userData);
        $userIds = array_map(function($userId) {
            return trim($userId);
        }, $userIds);
        if(empty($userIds)) {
            continue;
        }
        doPush($userIds, $task);
    } 
}

$nowTime = time();
$task = new TaskPush;
$task = $task->addWhere('status', '0')->addWhere('push_time', $nowTime, '<')->addWhere('push_time', $nowTime - 600, '>')->select();

if(empty($task)) {
    exit;
}

$task->mStatus = 1;
$task->save();

if($task->mType == 0) {
    $fieldsName = 'easemob_username';
    $users = new User;
    $users->setAutoClear(false);
    $users = $users->addWhere($fieldsName, '', '!=')->setCols([$fieldsName]);
    pushMsg($users, $task, $fieldsName, 10000);
    $fieldsName = 'username';
    $easeUsers = new EasemobAnonymous;
    $easeUsers = $easeUsers->addWhere($fieldsName, '', '!=')->setCols([$fieldsName]);
    pushMsg($easeUsers, $task, $fieldsName, 10000);
} elseif($task->mType == 1) {
    $userIds = explode(',', str_replace('，', ',', $task->mUserIds));
    $fieldsName = 'easemob_username';
    $users = new User;
    $users = $users->addWhere('id', $userIds, 'in')->setCols([$fieldsName]);
    pushMsg($users, $task, $fieldsName, 10000);
}

$task->mStatus = 2;
$task->mEndTime = time();
$task->save();
