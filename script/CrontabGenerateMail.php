<?php

(new MailGenerator_MailActiveDealWeekly('active项目进度简报', '_SysDate'))->generate();

(new MailGenerator_MailCloseExpired('进度异常提醒'))->generate();

(new MailGenerator_MailComplianceReview('合规性审查'))->generate();

(new MailGenerator_MailSignUpdate('交割update'))->generate();

(new MailGenerator_MailLoanUpdate('借款update'))->generate();

(new MailGenerator_MailDealDecision('投决意见', 'DealDecision'))->generate();

(new MailGenerator_MailDealDecisionExpire('投决意见超期', 'DealDecision'))->generate();

(new MailGenerator_MailComplianceReviewSpecial('合规性审查-RMBIII&GFO'))->generate();

(new MailGenerator_MailClosingOriginal('Closing原件提醒'))->generate();

(new MailGenerator_MailAicAndFinalClosing('工商及final交割提醒'))->generate();
