<?php

$mails = new Model_MailList;
$mails->addWhere('status', '待发送');
$mails = $mails->find();

foreach($mails as $i => $mail) {
    if ($mail->mExpectTime > time()) 
        continue;
    $mail->mStatus = '发送中';
    $mail->mSendTime = time();
    $mail->save();
    $error = EMail::send([
        'to' => explode(';', $mail->mMailTo),
        'cc' => explode(';', $mail->mMailCc),
        'title' => $mail->mTitle,
        'content' => $mail->mContent,
        'from' => SMTP_FROM,
        'fromName' => SMTP_FROM_NAME
    ]);
    $mail->mStatus = $error ? '发送失败' : '已发送';
    $mail->save();
}
