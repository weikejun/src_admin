<?php
class MailListController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_MailList();
        $this->model->orderBy('expect_time', 'DESC');
        WinRequest::mergeModel(array(
            'controllerText' => "提醒邮件列表",
            'tableWrap' => '2500px',
        ));

        $this->form=new Form_MailList();
        $this->list_display = [];
        foreach(Form_MailList::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : function($model)use($field){
                        return htmlspecialchars($model->getData($field['name']));
                    }),
                ];
            }
        }

        $strategys = new Model_MailStrategy;
        $strategys = $strategys->findMap('name');
        $choiceSt = [];
        foreach($strategys as $sKey => $st) {
            $choiceSt[] = [$st->mId, $sKey];
        }
        $this->list_filter = [
            new Page_Admin_ChoiceFilter(['name'=>Form_MailList::getFieldViewName('status'),'paramName'=>'status','choices'=>Model_MailList::getStatusChoices(),'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_MailList::getFieldViewName('strategy_id'),'paramName'=>'strategy_id','choices'=>$choiceSt,'class'=>'keep-all']),
            new Page_Admin_TimeRangeFilter(['name'=>Form_MailList::getFieldViewName('expect_time'),'paramName'=>'expect_time','class'=>'keep-all']),
            new Page_Admin_TimeRangeFilter(['name'=>Form_MailList::getFieldViewName('create_time'),'paramName'=>'create_time','class'=>'keep-all']),
        ];

        $this->single_actions=[
            ['label'=>'预览','target'=>'_blank','action'=>function($model){
                return '/admin/mailList/preview?id='.$model->mId;
            }],
        ];
        //$this->search_fields = ['name','description','tp'];
    }

    public function sendAction() {
        $id = $_GET['id'];
        if ($id) {
            $mail = new Model_MailList;
            $mail->addWhere('id', $id);
            $mail->addWhere('status', '待发送');
            $mail->select();
            if ($mail->mId) {
                $mail->mStatus = '发送中';
                $mail->mExpectTime = time();
                $mail->save();
                $error = EMail::send([
                    'to' => explode(';', $mail->mMailTo),
                    'cc' => explode(';', $mail->mMailCc),
                    'title' => $mail->mTitle,
                    'content' => $mail->mContent,
                    'from' => SMTP_FROM,
                    'fromName' => SMTP_FROM_NAME
                ]);
                $mail->mStatus = $error ? "发送失败：$error" : '已发送';
                $mail->save();
            }
        }
        return ['redirect:'.$this->getBackUrl()];
    }

    public function previewAction() {
        $id = $_GET['id'];
        $mail = new Model_MailList;
        if ($id) {
            $mail->addWhere('id', $id);
            $mail->select();
        }
        return ['admin/mail_tpl/preview.html',['mail' => $mail->getData()]];
    }
}


