<?php

abstract class MailGenerator {
    protected $_template;   // 邮件模板解析引擎 

    protected $_mailTpl;    // 邮件策略

    protected $_refType;    // 触发对象类型，默认Project

    protected $_triggers;    // 邮件触发对象，通常是Project

    public function __construct($strategy, $refType = 'Project') {
        $this->_template = DefaultViewSetting::getTemplate();
        DefaultViewSetting::setTemplateSetting($this->_template);

        $this->_mailTpl = new Model_MailStrategy;
        $this->_mailTpl->addWhere('name', $strategy);
        $this->_mailTpl->select();
        if (empty($this->_mailTpl->mId)) {
            echo '[Error]邮件模板为空';
            exit;
        }

        $this->_refType = $refType;
    }

    abstract protected function _getTrigger();

    abstract protected function _genCycle($trigger);

    protected function _clear($refId) {
        $mail = new Model_MailList;
        $mail->addWhere('strategy_id', $this->_mailTpl->mId);
        $mail->addWhere('status', '待发送');
        $mail->addWhere('ref', $this->_refType);
        $mail->addWhere('ref_id', $refId);
        $mail->delete();
    }

    protected function _genMail($data) {
        $mail = new Model_MailList;
        $mail->setData([
            'strategy_id'   =>  $this->_mailTpl->mId,
            'status'        =>  '待发送',
            'ref'           =>  $this->_refType,
            'ref_id'        =>  $data['ref_id'],
            'mail_to'       =>  $this->_template->fetch('string:'.$data['to']),
            'mail_cc'       =>  $this->_template->fetch('string:'.$data['cc']),
            'title'         =>  $this->_template->fetch('string:'.$data['title']),
            'content'       =>  $this->_template->fetch('string:'.$data['content']),
            'create_type'   =>  '自动',
            'expect_time'   =>  $data['expect_time'],
            'create_time'   =>  time(),
        ]);
        return $mail->save();
    }

    protected function _setTplVars($trigger) {
        $company = new Model_Company;
        $company->addWhere('id', $trigger->mCompanyId);
        $company->select();
        $tplVars['mail']['partner'] = Model_Member::getEmailById($company->mPartner);
        $tplVars['mail']['manager'] = Model_Member::getEmailById($company->mManager);
        $tplVars['mail']['legal_person'] = Model_Member::getEmailById($company->mLegalPerson);
        $tplVars['mail']['finance_person'] = Model_Member::getEmailById($company->mFinancePerson);
        $tplVars['mail']['filling_keeper'] = Model_Member::getEmailById($company->mFillingKeeper);
        $tplVars['company'] = $company->getData();
        $tplVars['project'] = $trigger->getData();
        return $tplVars;
    }

    public function generate() {
        foreach($this->_getTrigger() as $i => $trigger) {
            $tplVars = $this->_setTplVars($trigger);
            
            $this->_template->assign('vars', $tplVars);

            $this->_clear($trigger->mId);

            foreach($this->_genCycle($trigger) as $cycle) {
                if ($cycle < time()) continue;
                $this->_genMail([
                    'ref_id' => $trigger->mId,
                    'to' => $this->_mailTpl->mMailTo,
                    'cc' => $this->_mailTpl->mMailCc,
                    'title' => $this->_mailTpl->mTitle,
                    'content' => $this->_mailTpl->mContent,
                    'expect_time' => $cycle,
                ]);
            }
        }
    }
}
