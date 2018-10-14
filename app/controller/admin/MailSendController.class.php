<?php
class MailSendController extends Page_Admin_Base {
    use ControllerPreproc;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        $this->model=new Model_Project();
        WinRequest::mergeModel(array(
            'controllerText'=>"邮件模板",
        ));

        $this->form=new Form([
            ['name'=>'id','label'=>'交易ID','type'=>'choosemodel','model'=>'Model_Project','default'=>null,'required'=>true,'show'=>'id'],
            ['name'=>'field-index-mail','label'=>'邮件内容','type'=>'seperator']
        ]);

        $this->list_display = [];
        foreach(Form_Project::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator'
                && $field['type'] != 'seperator2') {
                $this->list_display[$field['name']] = [
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }
    }

    public function index() {
        $model = new Model_Project;
        if (!Model_AdminGroup::isCurrentAdminRoot()) {
            $persIds = Model_ItemPermission::getAdminItem();
            $model->addWhereRaw('(company_id IN ('.implode(',', $persIds['company']).') OR id IN ('.implode(',', $persIds['project']).'))');
        }
        $model->addWhere('id', $_REQUEST['id']);
        $model->select();
        if ($model->getData('id')) {
            foreach($this->list_display as $name => $field) {
                if (is_callable($field['field'])) {
                    $val = call_user_func($field['field'], $model);
                } else {
                    $val = $model->getData($name);
                }
                $tplVars[$name] = $val;
            }
        }
        $template = DefaultViewSetting::getTemplate();
        DefaultViewSetting::setTemplateSetting($template);
        $this->assign('mailContent', $template->fetch('admin/mail_tpl/deal_apply.html', ['vars'=>$tplVars]));
        $this->_read();
        return $this->display("admin/mail_send/generate.html");
    }
}


