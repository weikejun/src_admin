<?php
class FundLpController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    private function _initForm() {
        $this->form=new Form_FundLp();
    }

    private function _initListDisplay() {
        $this->list_display = [];
        foreach(Form_FundLp::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }
    }

    private function _initSingleActions() {
        $this->single_actions=[
            ['label'=>'预览','action'=>function($model){
                return '/admin/fundLp/check?id='.$model->mId;
            }],
            ['label'=>'复制','action'=>function($model){
                return '/admin/fundLp?action=clone&ex=gp_mailed,gp_mailed_detail,lp_mailed,lp_mailed_detail,gp_received,mail_receive_date,mailing_memo,create_time&id='.$model->mId;
            }],
        ];

        $this->single_actions_default = [
            'delete' => false,
            'edit' => true,
        ];
    }

    private function _initMultiActions() {
        $this->multi_actions=array(
            array('label'=>'导出csv','required'=>false,'action'=>'/admin/fundLp/exportToCsv?__filter='.urlencode($this->_GET("__filter"))),
        );
    }

    private function _initListFilter() {
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>Form_FundLp::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'in'=>true,'class'=>'keep-all']),
            new Page_Admin_TextForeignFilter(['name'=>Form_FundLp::getFieldViewName('entity_id'),'paramName'=>'name|entity_id','fusion'=>true,'foreignTable'=>'Model_Entity','class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_FundLp::getFieldViewName('subscriber'),'paramName'=>'subscriber','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('subscribe_currency'),'paramName'=>'subscribe_currency','choices'=>Model_Project::getCurrencyChoices(),'class'=>'keep-all']),
            new Page_Admin_TextForeignFilter(['name'=>Form_FundLp::getFieldViewName('subscriber_controller'),'paramName'=>'name|subscriber_controller','fusion'=>true,'foreignTable'=>'Model_ControllerActual']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('top_special'),'paramName'=>'top_special','choices'=>Model_FundLp::getYesNoChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('is_gov_capital'),'paramName'=>'is_gov_capital','choices'=>Model_FundLp::getYesNoChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('have_for_capital'),'paramName'=>'have_for_capital','choices'=>Model_FundLp::getYesNoChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('aic_pending'),'paramName'=>'aic_pending','choices'=>Model_FundLp::getHaveNotChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('undetermined'),'paramName'=>'undetermined','choices'=>Model_FundLp::getHaveNotChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('communication'),'paramName'=>'communication','choices'=>Model_FundLp::getYesNoChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('join_way'),'paramName'=>'join_way','choices'=>Model_FundLp::getJoinWayChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('share_transfer'),'paramName'=>'share_transfer','choices'=>Model_FundLp::getHaveNotChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('share_transfer_file'),'paramName'=>'share_transfer_file','choices'=>Model_FundLp::getCompleteChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('capital_reduce'),'paramName'=>'capital_reduce','choices'=>Model_FundLp::getHaveNotChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('capital_reduce_file'),'paramName'=>'capital_reduce_file','choices'=>Model_FundLp::getCompleteChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('subscribe_pdf'),'paramName'=>'subscribe_pdf','choices'=>Model_FundLp::getDocOptionChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('subscribe_doc'),'paramName'=>'subscribe_doc','choices'=>Model_FundLp::getCompleteChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('share_entrustment'),'paramName'=>'share_entrustment','choices'=>Model_FundLp::getHaveNotChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('is_exit'),'paramName'=>'is_exit','choices'=>Model_FundLp::getIsExitChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('side_letter'),'paramName'=>'side_letter','choices'=>Model_FundLp::getHaveNotChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('lpac'),'paramName'=>'lpac','choices'=>Model_FundLp::getHaveNotChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('kyc_file'),'paramName'=>'kyc_file','choices'=>Model_FundLp::getDocOptionChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('investor_type'),'paramName'=>'investor_type','choices'=>Model_FundLp::getInvestorTypeChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('compliance_check'),'paramName'=>'compliance_check','choices'=>Model_FundLp::getComplianceCheckChoices(),'class'=>'']),
            new Page_Admin_ChoiceFilter(['name'=>Form_FundLp::getFieldViewName('filling_list_check'),'paramName'=>'filling_list_check','choices'=>Model_FundLp::getFillingListCheckChoices(),'class'=>'']),
        );
    }

    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        WinRequest::mergeModel(array(
            'controllerText'=>"LP认购表",
            '_preview' => true,
            //'tableWrap' => '8192px',
        ));

        $this->model=new Model_FundLp();
        if (!Model_AdminGroup::isCurrentAdminRoot()) {
            $persIds = Model_EntityPermission::getAdminPerm();
            if (!isset($persIds['all'])) {
                $this->model->addWhereRaw('(entity_id IN ('.implode(',', $persIds['entity']).') OR id IN ('.implode(',', $persIds['lp']).'))');
            }
        }
    }

    private function _initFullAction() {
        $this->_initForm();
        $this->_initListDisplay();
        $this->_initSingleActions();
        $this->_initMultiActions();
        $this->_initListFilter();

        WinRequest::mergeModel(array(
            'tableWrap' => '7000px',
        ));

        $this->multi_actions[] = ['label'=>'常用字段','required'=>false,'action'=>trim('/admin/fundLp?'.$_SERVER['QUERY_STRING'],'?')];
    }

    public function fullAction() {
        $this->_initFullAction();
        return parent::indexAction();
    }

    private function _initIndexAction() {
        $this->_initForm();
        $this->_initListDisplay();
        $this->_initSingleActions();
        $this->_initMultiActions();
        $this->_initListFilter();

        WinRequest::mergeModel(array(
            'tableWrap' => '3072px',
        ));

        $briefFields = [
            Form_FundLp::getFieldViewName('id') => [],
            Form_FundLp::getFieldViewName('entity_id') => [],
            Form_FundLp::getFieldViewName('_entity_cate') => [],
            Form_FundLp::getFieldViewName('_entity_currency') => [],
            Form_FundLp::getFieldViewName('subscriber') => [],
            Form_FundLp::getFieldViewName('subscribe_amount') => [],
            Form_FundLp::getFieldViewName('_current_subscribe_amount') => [],
            Form_FundLp::getFieldViewName('investor_type') => [],
            Form_FundLp::getFieldViewName('subscriber_code') => [],
            Form_FundLp::getFieldViewName('subscriber_controller') => [],
            Form_FundLp::getFieldViewName('_contact_info') => [],
            Form_FundLp::getFieldViewName('sign_lpa_date') => [],
            Form_FundLp::getFieldViewName('subscriber_delivery_date') => [],
            Form_FundLp::getFieldViewName('subscribe_pdf') => [],
            Form_FundLp::getFieldViewName('subscribe_doc') => [],
        ];

        $list_display = $this->list_display;
        $this->list_display = [];
        for($i = 0; $i < count($list_display); $i++) {
            if (isset($briefFields[$list_display[$i]['label']])) {
                $briefFields[$list_display[$i]['label']] = $list_display[$i];
            }
        }
        $this->list_display = array_values($briefFields);

        $this->multi_actions[] = array('label'=>'全部字段','required'=>false,'action'=>trim('/admin/fundLp/full?'.$_SERVER['QUERY_STRING'],'?'));
    }

    public function indexAction() {
        $this->_initIndexAction();
        return parent::indexAction();
    }

    /* 
     * 自动保存
     */
    public function autoSaveAction() {
        if ($_REQUEST['action'] == 'create'
            || $_REQUEST['action'] == 'update') {
            $this->indexAction();
        }
        return ['json:', ['json'=>['id'=>$this->model->mId, 'stamp'=>date('H:i:s')]]];
    }

    /*
     * 重载ExportActions.initData方法
     */
    public function initData() {
        $this->_initFullAction();
    }

    protected function _initSelect() {
        $this->_initListDisplay();
        $this->list_filter = [];
        $this->search_fields = [];
        $reqModel = WinRequest::getModel();
        unset($reqModel['tableWrap']);
        WinRequest::setModel($reqModel);
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>'认购人ID','paramName'=>'id','fusion'=>false]),
            new Page_Admin_TextForeignFilter(['name'=>Form_FundLp::getFieldViewName('entity_id'),'paramName'=>'name|entity_id','foreignTable'=>'Model_Entity','fusion'=>true]),
        );
        foreach($this->list_display as $i => $field) {
            if (in_array($field['name'], ['id', 'subscriber', 'subscriber_code', 'entity_id', '_entity_cate','_entity_currency'])) {
                $list_display[] = $field;
            }
        }
        $this->list_display = $list_display;
    }

    public function select() {
        $this->_initSelect();
        return parent::select();
    }

    public function select_search(){
        $this->_initSelect();
        return parent::select_search();
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/check.html', $this->_assigned];
    }

    public function clone() {
        $ret = parent::clone();
        // 支持部分字段不复制
        if ($_GET['ex']) {
            $ex = explode(',', trim($_GET['ex']));
            $fields = $this->form->getConfig();
            for($i = 0; $i < count($fields); $i++) {
                if (in_array($fields[$i]->name(), $ex)) {
                    $fields[$i]->clone_clear();
                }
            }
        }
        return $ret;
    }

    public function captableAction() {
        $reqModel = WinRequest::getModel();
        $reqModel['controllerText'] = '基金主体 > 认购情况汇总';
        WinRequest::setModel($reqModel);

        $object = New Model_Entity;
        $object->addWhere('id', $_GET['entity_id']);
        $objectList = $object->find();
        $this->assign('objectDataList', $objectList);
        
        $lp = new Model_FundLp;
        $lp->addWhere('entity_id', $_GET['entity_id']);
        $dataList = $lp->find();

        $captableList = [];
        $summary = [
            'subscriber' => '合计',
            'subscriber_controller' => '',
            'subscriber_delivery_date' => '',
            'subscribe_amount' => [],
            'share_transfer_amount' => [],
            'capital_reduce_amount' => [],
            'current_amount' => [],
            'paid_amount' => [],
        ];
        foreach($dataList as $i => $dataItem) {
            if ($dataItem->getData('subscriber')) {
                $data = $dataItem->getData();
                if(!isset($captableList[$data['subscriber']])) {
                    $captableList[$data['subscriber']] = [
                        'subscriber' => $data['subscriber'],
                        'subscriber_controller' => $data['subscriber_controller'],
                        'subscriber_delivery_date' => $data['subscriber_delivery_date'],
                        'subscribe_amount' => [],
                        'share_transfer_amount' => [],
                        'capital_reduce_amount' => [],
                        'current_amount' => [],
                        'paid_amount' => [],
                    ];
                }
                foreach(['subscribe' => 1,'share_transfer' => -1,'capital_reduce' => -1] as $fk => $fa) {
                    $amount = ($fa * $data[$fk.'_amount']);
                    $captableList[$data['subscriber']][$fk.'_amount'][$data[$fk.'_currency']] += $amount;
                    $captableList[$data['subscriber']]['current_amount'][$data[$fk.'_currency']] += $amount;
                    $summary[$fk.'_amount'][$data[$fk.'_currency']] += $amount;
                    $summary['current_amount'][$data[$fk.'_currency']] += $amount;
                }
                $captableList[$data['subscriber']]['paid_amount'][$data['paid_currency']] += $data['paid_amount'];
                $summary['paid_amount'][$data['paid_currency']] += $data['paid_amount'];
            }
        }
        $captableList['_total'] = &$summary;
        $this->assign('captableDataList', $captableList);

        $this->captable_display = [
            ['label' => '认购人', 'field' => function($model) {
                return $model['subscriber'];
            }],
            ['label' => '实际控制人', 'field' => function($model) {
                if ($model['subscriber_controller']) {
                    $actual = new Model_ControllerActual;
                    $actual->addWhere('id', $model['subscriber_controller']);
                    $actual->select();
                    return $actual->mName;
                }
            }],
            ['label' => '交割日期', 'field' => function($model) {
                if ($model['subscriber_delivery_date']) {
                    return date('Ymd', $model['subscriber_delivery_date']);
                }
            }],
            ['label' => '初始认缴金额', 'field' => function($model)use(&$summary) {
                $output = '';
                foreach($model['subscribe_amount'] as $currency => $amount) {
                    if (!$amount) continue;
                    $output .= "$currency " . number_format($amount) . "<br />";
                }
                return $output;
            }],
            ['label' => '转让金额', 'field' => function($model)use(&$summary) {
                $output = '';
                foreach($model['share_transfer_amount'] as $currency => $amount) {
                    if (!$amount) continue;
                    $output .= "$currency " . number_format(-$amount) . "<br />";
                }
                return $output;
            }],
            ['label' => '减资金额', 'field' => function($model)use(&$summary) {
                $output = '';
                foreach($model['capital_reduce_amount'] as $currency => $amount) {
                    if (!$amount) continue;
                    $output .= "$currency " . number_format(-$amount) . "<br />";
                }
                return $output;
            }],
            ['label' => '当前认缴金额', 'field' => function($model) {
                $currencys = array_unique($currencys);
                $output = '';
                foreach($model['current_amount'] as $currency => $amount) {
                    if (!$amount) continue;
                    $output .= "$currency " . number_format($amount) . "<br />";
                }
                return $output;
            }],
            ['label' => '当前认缴比例', 'field' => function($model)use(&$summary) {
                $output = '';
                foreach($model['current_amount'] as $currency => $amount) {
                    if (!$amount) continue;
                    $output .= "$currency " . sprintf("%.2f%%", $amount/$summary['current_amount'][$currency] * 100) . "<br />";
                }
                return $output;
            }],
            ['label' => '实缴金额', 'field' => function($model) {
                $currencys = array_unique($currencys);
                $output = '';
                foreach($model['paid_amount'] as $currency => $amount) {
                    if (!$amount) continue;
                    $output .= "$currency " . number_format($amount) . "<br />";
                }
                return $output;
            }],
            ['label' => '实缴比例', 'field' => function($model)use(&$summary) {
                $output = '';
                foreach($model['paid_amount'] as $currency => $amount) {
                    if (!$amount) continue;
                    $output .= "$currency " . sprintf("%.2f%%", $amount/$summary['paid_amount'][$currency] * 100) . "<br />";
                }
                return $output;
            }],
        ];

        $this->assign('pageAdmin',$this);

        return ['admin/fund_lp/captable.html', $this->_assigned];
    }
}


