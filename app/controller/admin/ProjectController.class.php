<?php
class ProjectController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    private $_objectCache = [];

    private function _initForm() {
        $this->form=new Form_Project();
    }

    private function _initListDisplay() {
        $this->list_display = [];
        foreach(Form_Project::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator'
                && $field['type'] != 'seperator2') {
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
                return '/admin/project/check?id='.$model->mId;
            }],
            ['label'=>'复制','action'=>function($model){
                return '/admin/project?action=clone&ex=deal_type,decision_date,proj_status,longstop_date,kickoff_date,close_date,loan_cb,loan_currency,loan_type,loan_entity_id,loan_amount,loan_sign_date,loan_end_date,loan_process,loan_memo,new_old_stock,active_deal,loan_schedule,trade_file_schedule,expect_sign_date,expect_pay_schedule,trade_schedule_todo,deal_progress,close_notice,trade_schedule_memo,create_time,update_time,stocknum_all,committee_view,sequ&id='.$model->mId;
            }],
            ['label'=>'审阅','action'=>function($model){
                return '/admin/systemLog/diff?resource=project&res_id='.$model->mId;
            }],
            ['label'=>'失效','action'=>function($model){
                return '/admin/project?action=delete&id='.$model->mId;
            }],
        ];

        $this->single_actions_default = ['delete'=>false,'edit'=>true];
    }

    private function _initMultiActions() {
        $this->multi_actions=array(
            ['label'=>'回收站', 'required'=>false, 'action'=>'/admin/project/recovery'],
            ['label'=>'导出csv','required'=>false,'action'=>'/admin/project/exportToCsv?method=full&__filter='.urlencode($this->_GET("__filter"))],
            ['label'=>'导入csv','required'=>false,'action'=>'/admin/project/import?method=full&__filter='.urlencode($this->_GET("__filter"))],
            //['label'=>'导入csv','required'=>false,'action'=>'/admin/project/csvImport'],
        );
    }

    private function _initListFilter() {
        $this->list_filter=array(
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'class'=>'keep-all','in'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('_company_short'),'paramName'=>'short|company_id','foreignTable'=>'Model_Company','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('entity_id'),'paramName'=>'entity_id','fusion'=>false,'hidden'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('exit_entity_id'),'paramName'=>'exit_entity_id','fusion'=>false,'hidden'=>true,'class'=>'keep-all']),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('entity_id'),'paramName'=>'name|entity_id','foreignTable'=>'Model_Entity','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('exit_entity_id'),'paramName'=>'name|exit_entity_id','foreignTable'=>'Model_Entity','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>Form_Project::getFieldViewName('decision_date'),'paramName'=>'decision_date']),
            new Page_Admin_TimeRangeFilter(['name'=>Form_Project::getFieldViewName('close_date'),'paramName'=>'close_date']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('item_status'),'paramName'=>'item_status','choices'=>Model_Project::getItemStatusChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('proj_status'),'paramName'=>'proj_status','choices'=>Model_Project::getProjStatusChoices(),'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('deal_type'),'paramName'=>'deal_type','choices'=>Model_Project::getDealTypeChoices(),'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('turn'),'paramName'=>'turn','choices'=>Model_Project::getTurnChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('active_deal'),'paramName'=>'active_deal','choices'=>Model_Project::getStandardYesNoChoices(),'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('invest_currency'),'paramName'=>'invest_currency','choices'=>Model_Project::getInvestCurrencyChoices()]),
            new Page_Admin_RangeFilter(['name'=>Form_Project::getFieldViewName('our_amount'),'paramName'=>'our_amount']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('value_currency'),'paramName'=>'value_currency','choices'=>Model_Project::getValueCurrencyChoices()]),
            new Page_Admin_RangeFilter(['name'=>Form_Project::getFieldViewName('post_money'),'paramName'=>'post_money']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('new_follow'),'paramName'=>'new_follow','choices'=>Model_Project::getNewFollowChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('enter_exit_type'),'paramName'=>'enter_exit_type','choices'=>Model_Project::getEnterExitTypeChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('loan_type'),'paramName'=>'loan_type','choices'=>Model_Project::getLoanTypeChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('has_exit'),'paramName'=>'has_exit','choices'=>Model_Project::getStandardOptionChoices()]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('_current_partner'),'paramName'=>'partner|company_id','foreignTable'=>'Model_Company','fusion'=>false,'preSearch'=>function($val) {return Model_Member::getIdsByName($val);}]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('_current_legal_person'),'paramName'=>'legal_person|company_id','foreignTable'=>'Model_Company','fusion'=>false,'preSearch'=>function($val) {return Model_Member::getIdsByName($val);}]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('_current_finance_person'),'paramName'=>'finance_person|company_id','foreignTable'=>'Model_Company','fusion'=>false,'preSearch'=>function($val) {return Model_Member::getIdsByName($val);}]),
            new Page_Admin_ChoiceFilter(['name'=>'财务校对','paramName'=>'finance_check_sign','choices'=>[['未校对','未校对', 'and (`finance_check_sign` = "" or `finance_check_sign` is NULL)'],['已校对','已校对','and `finance_check_sign` is not null and `finance_check_sign` != ""']]]),
            new Page_Admin_ChoiceFilter(['name'=>'法务校对','paramName'=>'legal_check_sign','choices'=>[['未校对','未校对', 'and (`legal_check_sign` = "" or `legal_check_sign` is NULL)'],['已校对','已校对','and `legal_check_sign` is not null and `legal_check_sign` != ""']]]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('pending'),'paramName'=>'pending','choices'=>Model_Project::getPendingChoices(),'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('affiliate_transaction'),'paramName'=>'affiliate_transaction','choices'=>Model_Project::getStandardYesNoChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('exit_type'),'paramName'=>'exit_type','choices'=>Model_Project::getExitTypeChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>'交割情况','paramName'=>'_close_status','choices'=>[['未交割','未交割', 'and (`close_date` = 0 or `close_date` is NULL)']]]),
            new Page_Admin_ChoiceFilter(['name'=>'合同/支付金额','paramName'=>'_contract_pay_diff','choices'=>[['投资不一致','投资不一致', 'and `our_amount` != `pay_amount`'],['退出不一致','退出不一致', 'and `exit_amount` != `exit_receive_amount`']]]),
            new Page_Admin_ChoiceFilter(['name'=>'数据完整性检查','paramName'=>'_data_integrity','choices'=>[['date','决策/启动、交割、签约日期空缺', 'and (`decision_date` = "" or `decision_date` is null or `decision_date` = 0 or `close_date` = "" or `close_date` is null or `close_date` = 0 or `kickoff_date` is null or `kickoff_date` = "" or `kickoff_date` = 0)'],['member','项目成员空缺','and (`partner` = "" or `manager` = "" or `legal_person` = "" or `finance_person` = "" or `deal_manager` = "")'],['value_currency','估值计价货币','and (`value_currency` = "" or `value_currency` is null)'],['invest_currency','源码投资计价货币','and (`invest_currency` = "" or `invest_currency` is null)'],['exit_currency','源码退出计价货币','and (`exit_currency` = "" or `exit_currency` is null)'],['loan_currency','源码借款计价货币','and (`loan_currency` = "" or `loan_currency` is null)'],['our_board',Form_Project::getFieldViewName('our_board'),'and (`our_board` = "" or `our_board` is null)'],['stocknum_all',Form_Project::getFieldViewName('stocknum_all'),'and (`stocknum_all` = "" or `stocknum_all` is null)'],['entity_id',Form_Project::getFieldViewName('entity_id'),'and (`entity_id` = "" or `entity_id` is null)'],['exit_entity_id',Form_Project::getFieldViewName('exit_entity_id'),'and (`exit_entity_id` = "" or `exit_entity_id` is null)'],['invest_turn',Form_Project::getFieldViewName('invest_turn'),'and (`invest_turn` = "" or `invest_turn` is null)'],['exit_turn',Form_Project::getFieldViewName('exit_turn'),'and (`exit_turn` = "" or `exit_turn` is null)']]]),
        );
    }

    public function __construct(){
        parent::__construct();

        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        $this->model=new Model_Project();
        $this->model->on('after_insert', function($model) {
            if (!$model->getData('id')) 
                return;
            $adminId = Model_Admin::getCurrentAdmin()->mId;
            $finder = new Model_ItemPermission;
            $finder->addWhere('admin_id', $adminId);
            $finder->addWhere('company_id', $model->getData('company_id'));
            $finder->addWhere('project_id', '');
            if ($finder->count()) {
                return;
            }
            $itemPer = new Model_ItemPermission;
            $itemPer->setData([
                'admin_id' => $adminId,
                'project_id' => $model->getData('id'),
                'company_id' => '',
                'operator_id' => '',
                'create_time' => time(),
            ]);
            $itemPer->save();
            if(!$model->mSequ) {
                $model->mSequ = $model->mId;
                $model->save();
            }
        });
        $this->model->on('after_update', function($model) {
            $mails = json_decode($model->getData('committee_view'));
            // 清除之前的投决信息及邮件
            $decision = new Model_DealDecision;
            $decision->addWhere('project_id', $model->getData('id'));
            $decisions = $decision->find();
            foreach($decisions as $decision) {
                if (empty($decision->mDecision)) {
                    $mail = new Model_MailList;
                    $mail
                        ->addWhere('ref', 'DealDecision')
                        ->addWhere('ref_id', $decision->mId)
                        ->addWhere('status', '待发送')
                        ->delete();
                    $decision->delete();
                }
            }
            foreach($mails as $i => $mail) {
                $id = Model_Member::getIdByEmail($mail);
                if ($id) {
                    $decision = new Model_DealDecision;
                    $decision->addWhere('project_id', $model->getData('id'));
                    $decision->addWhere('partner', $id);
                    $decision->select();
                    if (!$decision->mId) {
                        $decision = new Model_DealDecision;
                        $decision->setData([
                            'project_id' => $model->getData('id'),
                            'partner' => $id,
                            'sign_key' => Model_DealDecision::signData(),
                            'update_time' => time(),
                            'create_time' => time(),
                        ]);
                        $decision->save();
                    }
                }
            }
        });
        $this->model->orderBy('sequ', 'DESC');
        if (!Model_AdminGroup::isCurrentAdminRoot()) {
            $persIds = Model_ItemPermission::getAdminItem();
            if (!isset($persIds['all'])) {
                $this->model->addWhereRaw('(company_id IN ('.implode(',', $persIds['company']).') OR id IN ('.implode(',', $persIds['project']).'))');
            }
        }

        WinRequest::mergeModel(array(
            'controllerText' => '交易记录',
            '_preview' => true,
        ));
    }

    private function _initFullAction() {
        $this->_initForm();
        $this->_initListDisplay();
        $this->_initSingleActions();
        $this->_initMultiActions();
        $this->_initListFilter();

        $this->model->addWhere('status', 'valid');
        WinRequest::mergeModel(array(
            'tableWrap' => '22000px',
        ));

        $this->multi_actions[] = ['label'=>'常用字段','required'=>false,'action'=>trim('/admin/project?'.$_SERVER['QUERY_STRING'],'?')];
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

        $this->model->addWhere('status', 'valid');
        WinRequest::mergeModel(array(
            'tableWrap' => '6000px',
        ));

        $briefFields = [
            Form_Project::getFieldViewName('id') => [],
            /*
            ['label'=>'操作','field'=>function($model){
                return '<a href="/admin/project?action=clone&ex=kickoff_date,close_date,loan_cb,loan_currency,loan_type,loan_entity_id,loan_amount,loan_sign_date,loan_end_date,loan_process,loan_memo&id='.$model->mId.'">复制</a>'.
                '<a href="/admin/systemLog/diff?resource=project&res_id='.$model->mId.'">审阅</a>'.
                '<a href="/admin/project?action=delete&id='.$model->mId.'删除</a>';
            }],*/
            Form_Project::getFieldViewName('_company_short') => [],
            Form_Project::getFieldViewName('_company_id') => [],
            Form_Project::getFieldViewName('turn_sub') => [],
            Form_Project::getFieldViewName('deal_type') => [],
            Form_Project::getFieldViewName('decision_date') => [],
            Form_Project::getFieldViewName('kickoff_date') => [],
            Form_Project::getFieldViewName('close_date') => [],
            Form_Project::getFieldViewName('financing_amount') => [],
            Form_Project::getFieldViewName('post_money') => [],
            Form_Project::getFieldViewName('stocknum_all') => [],
            Form_Project::getFieldViewName('_stock_price') => [],
            Form_Project::getFieldViewName('entity_id') => [],
            Form_Project::getFieldViewName('enter_exit_type') => [],
            Form_Project::getFieldViewName('our_amount') => [],
            Form_Project::getFieldViewName('pay_amount') => [],
            Form_Project::getFieldViewName('_invest_stock_price') => [],
            Form_Project::getFieldViewName('invest_turn') => [],
            Form_Project::getFieldViewName('stocknum_get') => [],
            Form_Project::getFieldViewName('_stock_ratio') => [],
            Form_Project::getFieldViewName('stock_property') => [],
            Form_Project::getFieldViewName('_stocknum_new') => [],
            Form_Project::getFieldViewName('_shareholding_ratio') => [],
            Form_Project::getFieldViewName('other_investor_summary') => [],
            Form_Project::getFieldViewName('loan_cb') => [],
            Form_Project::getFieldViewName('exit_type') => [],
            Form_Project::getFieldViewName('exit_turn') => [],
            Form_Project::getFieldViewName('_exit_stock_ratio') => [],
            Form_Project::getFieldViewName('exit_amount') => [],
            Form_Project::getFieldViewName('exit_receive_amount') => [],
            Form_Project::getFieldViewName('_exit_return_rate') => [],
            Form_Project::getFieldViewName('risk_tip') => [],
            Form_Project::getFieldViewName('pending_detail') => [],
            Form_Project::getFieldViewName('partner') => [],
            Form_Project::getFieldViewName('manager') => [],
            Form_Project::getFieldViewName('finance_person') => [],
            Form_Project::getFieldViewName('legal_person') => [],
            Form_Project::getFieldViewName('_memo') => [],
        ];

        if ($_GET['fields']) { // 选取字段，用于公式检查
            $fields = explode(',', $_GET['fields']);
            $briefFields = [];
            foreach($fields as $field) {
                $briefFields[Form_Project::getFieldViewName($field)] = [];
            }
            $reqModel = WinRequest::getModel();
            $reqModel['tableWrap'] = (count($fields)*120).'px';
            WinRequest::setModel($reqModel);
        }

        $list_display = $this->list_display;
        $this->list_display = [];
        for($i = 0; $i < count($list_display); $i++) {
            if (isset($briefFields[$list_display[$i]['label']])) {
                $briefFields[$list_display[$i]['label']] = $list_display[$i];
            }
        }
        $this->list_display = array_values($briefFields);

        $this->multi_actions[] = array('label'=>'全部字段','required'=>false,'action'=>trim('/admin/project/full?'.$_SERVER['QUERY_STRING'],'?'));
    }

    public function indexAction() {
        $this->_initIndexAction();
        return parent::indexAction();
    }

    public function recoveryAction() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $this->preMethod('index');
            $this->model
                ->addWhere('id', $_REQUEST['id'])
                ->setCols(['id','status'])
                ->select()
                ->setDataMerge(['status'=>'valid'])
                ->save();
            return ['redirect: ' . dirname($_SERVER['SCRIPT_NAME'])];
        }
        $this->_initListDisplay();
        $this->model->addWhere('status', 'invalid');
        $this->hide_action_new = true;
        $this->single_actions_default = ['edit'=>false,'delete'=>false];
        $this->single_actions=[
            ['label'=>'恢复','action'=>function($model){
                return '/admin/project/recovery?id='.$model->mId;
            }],
        ];
        WinRequest::mergeModel(array(
            'tableWrap' => '22000px',
        ));
        $reqModel = WinRequest::getModel();
        $reqModel['controllerText'] = '交易记录 回收站';
        WinRequest::setModel($reqModel);
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
     * 重载_delete()方法
     */
    public function _delete() {
        $this->model
            ->addWhere('id', $_REQUEST['id'])
            ->setCols(['id','status'])
            ->select()
            ->setDataMerge(['status'=>'invalid'])
            ->save();
    }

    /*
     * 重载ExportActions.initData方法
     */
    public function initData() {
        $this->_initFullAction();
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
        $reqModel['controllerText'] = '目标企业 > 投退Captable';
        WinRequest::setModel($reqModel);

        $object = New Model_Company;
        $object->addWhere('id', $_GET['company_id']);
        $objectList = $object->find();
        $this->assign('objectDataList', $objectList);
        
        $project = new Model_Project;
        $project->addWhere('company_id', $_GET['company_id']);
        $project->addWhere('status', 'valid');
        $project->orderBy('sequ', 'DESC');
        $project->setAutoClear(false);
        $dataList=$project->find();

        $captableIds = [];
        foreach($dataList as $i => $dataItem) {
            if ($dataItem->getData('entity_id')) {
                $captableIds[$dataItem->getData('entity_id')] = 1;
            }
            if ($dataItem->getData('exit_entity_id')) {
                $captableIds[$dataItem->getData('exit_entity_id')] = 1;
            }
            if ($dataItem->getData('loan_entity_id')
                && $dataItem->getData('deal_type') == '源码独立CB') {
                $captableIds[$dataItem->getData('loan_entity_id')] = 1;
            }
            if ($dataItem->getData('count_captable') == '计入'
                && !$dataItem->getData('close_date')) {
                $dataList[$i]->setDataMerge(['close_date'=>Model_Project::DEFAULT_CLOSE_DATE]);
            }
        }
        $captable = new Model_Entity;
        $captable->addWhere('id', array_keys($captableIds), 'IN', DBTable::ESCAPE);
        $captableList = $captable->find();
        $captableList[] = new Model_Entity;
        $this->assign('captableDataList', $captableList);

        $this->deal_display = [
            ['label' => '交易主体', 'field' => function($model) {
                if ($model->getData('deal_type') == '源码退出') {
                    $entityId = $model->getData('exit_entity_id');
                } else {
                    $entityId = $model->getData('entity_id');
                }
                if ($entityId) {
                    $entity = new Model_Entity;
                    $entity->mId = $entityId;
                    $entity->select();
                    return $entity->getData('name');
                }
            }],
            ['label' => '股份类型', 'field' => function($model) {
                return $model->getData('new_old_stock');
            }],
            ['label' => '交割日期', 'field' => function($model) {
                $dateStr = date('Ymd', $model->getData('close_date'));
                if ($model->getData('close_date') == Model_Project::DEFAULT_CLOSE_DATE) {
                    $dateStr = '暂未交割';
                }
                return sprintf("<a target='_blank' href='/admin/project?__filter=id%%3D%s'>%s</a>", 
                    $model->getData('id'),
                    $dateStr
                    
                );
            }],
            ['label' => '企业轮次', 'field' => function($model) {
                return sprintf("<a target='_blank' href='/admin/project?__filter=id%%3D%s'>%s</a>", 
                    $model->getData('id'),
                    $model->getData('turn_sub')
                );
            }],
            ['label' => '股权轮次', 'field' => function($model) {
                return sprintf("<a target='_blank' href='/admin/project?__filter=id%%3D%s'>%s</a>", 
                    $model->getData('id'),
                    $model->getData('invest_turn')
                );
            }],
            ['label' => '投资金额', 'field' => function($model) {
                $currency = $model->getData('invest_currency');
                $amount = $model->getData('our_amount');
                $amount = $amount ? $amount : 0;
                return $currency . ' ' . number_format($amount, 2);
            }],
            ['label' => '投时股数', 'field' => function($model) {
                return number_format($model->getData('stocknum_get'));
            }],
            ['label' => '源码股价', 'field' => function($model) {
                $currency = $model->getData('invest_currency');
                $amount = $model->getData('our_amount');
                $stockNum = $model->getData('stocknum_get');
                $amount = $amount ? $amount : 0;
                $output = '';
                if ($stockNum) {
                    $output = "$currency " . number_format($amount/$stockNum, 2); 
                }
                return $output;
            }],
            ['label' => '企业股价', 'field' => function($model) {
                $currency = $model->getData('value_currency');
                $amount = $model->getData('post_money');
                $stockNum = $model->getData('stocknum_all');
                $amount = $amount ? $amount : 0;
                $output = '';
                if ($stockNum) {
                    $output = "$currency " . number_format($amount/$stockNum, 2); 
                }
                return $output;
            }],
            ['label' => 'Post估值', 'field' => function($model) {
                return $model->getData('value_currency') . ' ' . number_format($model->getData('post_money'), 2);
            }],
            ['label' => '退出金额', 'field' => function($model)use(&$exitDataList,&$exitAmounts) {
                $turn = $model->getData('invest_turn');
                $amounts = [];
                foreach($exitDataList as $i => $turnData) {
                    if ($turnData->getData('exit_turn') == $turn
                        && $turnData->getData('exit_entity_id') == $model->getData('entity_id')) {
                        $amounts[$turnData->getData('exit_currency')] += $turnData->getData('exit_amount');
                    }
                }
                $exitAmounts = $amounts;
                $output = '';
                foreach($amounts as $currency => $amount) {
                    $output .= "$currency " . number_format($amount, 2) . '<br />';
                }
                return $output ? $output : 0;
            }],
            ['label' => '退出股数', 'field' => function($model)use(&$exitDataList, &$exitStocks) {
                $turn = $model->getData('invest_turn');
                $amount = 0;
                foreach($exitDataList as $i => $turnData) {
                    if ($turnData->getData('exit_turn') == $turn
                        && $turnData->getData('exit_entity_id') == $model->getData('entity_id')) {
                        $amount += $turnData->getData('exit_stock_number');
                    }
                }
                $exitStocks = $amount;
                return number_format($amount);
            }],
            ['label' => '退出股价', 'field' => function($model)use(&$exitAmounts, &$exitStocks) {
                if (count($exitAmounts) > 1) {
                    return '多币种';
                }
                foreach($exitAmounts as $currency => $amount) {
                    return "$currency " . number_format($amount/$exitStocks, 2);
                }
                return '0';
            }],
            ['label' => '投时股比', 'field' => function($model)use(&$latestDeal,&$exitDataList) {
                if ($model->getData('stocknum_all')) {
                    return sprintf('%.2f%%',$model->getData('stocknum_get')/$model->getData('stocknum_all')*100);
                }
            }],
            ['label' => '<a title="当本主体购股轮次=源码售股轮次时且源码投资主体=源码退出主体时，(∑投时持本轮股数-∑源码退出的股数)/最新一轮本轮企业总股数">最新股比</a>', 'field' => function($model)use(&$latestDeal,&$exitDataList) {
                $formula = ['id' => [$latestDeal->getData('id'),$model->getData('id')]];
                $turn = $model->getData('invest_turn');
                $exitStockNum = 0;
                foreach($exitDataList as $i => $turnData) {
                    if ($turnData->getData('exit_turn') == $turn
                        && $turnData->getData('exit_entity_id') == $model->getData('entity_id')) {
                        $exitStockNum += $turnData->getData('exit_stock_number');
                        $formula['id'][] = $turnData->getData('id');
                    }
                }
                $output = sprintf('%.2f%%',($model->getData('stocknum_get') - $exitStockNum)/$latestDeal->getData('stocknum_all')*100);
                return sprintf('<a target=_blank href="/admin/Project?fields=id,deal_type,stocknum_get,exit_stock_number,invest_turn,exit_turn,stocknum_all,_company_short,turn_sub,close_date,entity_id,exit_entity_id&__filter=%s">%s</a>', urlencode('id='.implode(',',$formula['id'])),$output);
            }],
        ];

        $object_display = Form_Company::getFieldsMap();
        $briefFields = [
            Form_Company::getFieldViewName('id') => [],
            Form_Company::getFieldViewName('name') => [],
            Form_Company::getFieldViewName('short') => [],
            Form_Company::getFieldViewName('manager') => [],
            Form_Company::getFieldViewName('legal_person') => [],
            Form_Company::getFieldViewName('finance_person') => [],
            Form_Company::getFieldViewName('_latest_turn_sub') => [],
            Form_Company::getFieldViewName('_stocknum_all') => [],
            Form_Company::getFieldViewName('_latest_post_moeny') => [],
            ['label' => '最新企业每股单价', 'field' => function($model)use($dataList) {
                if ($dataList[0]->getData('stocknum_all')) {
                    return $dataList[0]->getData('value_currency') . ' ' . number_format($dataList[0]->getData('post_money')/$dataList[0]->getData('stocknum_all'), 2);
                }
            }],
            Form_Company::getFieldViewName('_financing_amount_all') => [],
        ];
        $this->object_display = [];
        for($i = 0; $i < count($object_display); $i++) {
            if (isset($briefFields[$object_display[$i]['label']])) {
                $briefFields[$object_display[$i]['label']] = $object_display[$i];
            }
        }
        $this->object_display = array_values($briefFields);
        foreach($this->object_display as $i => &$field) {
            if (!isset($field['field'])) {
                $field['field'] = $field['name'];
            }
        }

        $investValues = [];
        $exitValues = [];
        $holdStocks = 0;
        $investStocks = 0;
        $exitStocks = 0;
        $this->captable_display = [
            ['label' => '源码投资主体', 'field' => function($model) {
                if ($model->getData('id')) {
                    return $model->getData('name');
                }

                return '合计';
            }],
            ['label' => '<a title="当交割日期存在或者计入captable时，∑投时持本轮股数-∑源码退出股数">最新持有股数</a>', 'field' => function($model)use($dataList, &$holdStocks){
                if (!$model->getData('id')) {
                    return number_format($holdStocks);
                }
                $formula = [];
                $stockNum = 0;
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')) {
                        $stockNum += $dataItem->getData('stocknum_get');
                        $formula['id'][] = $dataItem->getData('id');
                    }
                    if ($dataItem->getData('exit_entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')) {
                        $stockNum -= $dataItem->getData('exit_stock_number');
                        $formula['id'][] = $dataItem->getData('id');
                    }
                }
                $formula['field'] = ['close_date','entity_id','stocknum_get','exit_stock_number'];
                $holdStocks += $stockNum;
                return '<a target="_blank" href="/admin/Project?fields=id,stocknum_get,exit_stock_number,_company_short,turn_sub,deal_type,close_date,entity_id,exit_entity_id&__filter='.urlencode('id='.implode(',',$formula['id'])).'">'.number_format($stockNum).'</a>';
            }],
            ['label' => '<a title="当交割日期存在或者计入captable时，(∑投时持本轮股数-∑源码退出股数)/最新一轮本轮企业总股数">最新持股比例</a>', 'field' => function($model)use($dataList, &$holdStocks){
                $formula = [];
                if (!$model->getData('id')) {
                    $stockNum = $holdStocks;
                } else {
                    $stockNum = 0;
                    foreach($dataList as $i => $dataItem) {
                        if ($dataItem->getData('entity_id') == $model->getData('id') 
                            && $dataItem->getData('close_date')) {
                            $stockNum += $dataItem->getData('stocknum_get');
                            $formula['id'][] = $dataItem->getData('id');
                        }
                        if ($dataItem->getData('exit_entity_id') == $model->getData('id') 
                            && $dataItem->getData('close_date')) {
                            $stockNum -= $dataItem->getData('exit_stock_number');
                        }
                    }
                }
                foreach($dataList as $i => $dataItem) {
                    //if (strpos($dataItem->getData('deal_type'), '企业融资') !== false) {
                    if ($dataItem->getData('stocknum_all')) {
                        $formula['id'][] = $dataItem->getData('id');
                        if (!$model->getData('id')) {
                            return sprintf('%.2f%%', $stockNum/$dataItem->getData('stocknum_all')*100);
                        }
                        return sprintf('<a target=_blank href="/admin/Project?fields=id,stocknum_get,exit_stock_number,stocknum_all,_company_short,turn_sub,deal_type,close_date,entity_id,exit_entity_id&__filter=%s">%.2f%%</a>', urlencode('id='.implode(',',$formula['id'])),$stockNum/$dataItem->getData('stocknum_all')*100);
                    }
                    //}
                }
                return '0.00%';
            }],
            ['label' => '<a title="当交割日期存在或者计入captable时，(∑投时持本轮股数-∑源码退出股数)/最新一轮本轮企业总股数x企业投后估值">最新账面价值</a>', 'field' => function($model)use($dataList, &$holdStocks, &$holdValue){
                $formula = [];
                if (!$model->getData('id')) {
                    $stockNum = $holdStocks;
                } else {
                    $stockNum = 0;
                    foreach($dataList as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            if ($dataItem->getData('entity_id') == $model->getData('id') 
                                && strpos($dataItem->getData('deal_type'), '源码投') !== false) {
                                $stockNum += $dataItem->getData('stocknum_get');
                                $formula['id'][] = $dataItem->getData('id');
                            }
                            if ($dataItem->getData('exit_entity_id') == $model->getData('id') 
                                && strpos($dataItem->getData('deal_type'), '源码退') !== false) {
                                $stockNum -= $dataItem->getData('exit_stock_number');
                                $formula['id'][] = $dataItem->getData('id');
                            }
                        }
                    }
                }
                foreach($dataList as $i => $dataItem) {
                    //if (strpos($dataItem->getData('deal_type'), '企业融资') !== false) {
                    if ($dataItem->getData('close_date')) {
                        $holdValue[$dataItem->getData('value_currency')] = $stockNum/$dataItem->getData('stocknum_all')*$dataItem->getData('post_money');
                        $bookValue = $dataItem->getData('value_currency') . ' ' . number_format($holdValue[$dataItem->getData('value_currency')], 2);
                        if (!$model->getData('id')) {
                            return $bookValue;
                        }
                        $formula['id'][] = $dataItem->getData('id');
                        return sprintf('<a target=_blank href="/admin/Project?fields=id,stocknum_get,exit_stock_number,stocknum_all,post_money,_company_short,turn_sub,deal_type,close_date,entity_id,exit_entity_id&__filter=%s">%s</a>', urlencode('id='.implode(',',$formula['id'])),$bookValue);
                    }
                }
                return 0;
            }],
            ['label' => '<a title="当本轮交易类型=企业融资（源码投）时，∑源码合同投资金额">历史投资金额</a>', 'field' => function($model)use($dataList, &$investValues, &$curInvestValues){
                $formula = [];
                if (!$model->getData('id')) {
                    if (!$investValues) {
                        return 0;
                    }
                    foreach($investValues as $currency => $amount) {
                        echo "$currency " . number_format($amount, 2) . '<br />';
                    }
                    return;
                }
                $amounts = [];
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('close_date')) {
                        if ($dataItem->getData('entity_id') == $model->getData('id') 
                            && strpos($dataItem->getData('deal_type'), '源码投') !== false) {
                            $amounts[$dataItem->getData('invest_currency')] += $dataItem->getData('our_amount');
                            $formula['id'][] = $dataItem->getData('id');
                        }
                    }
                }
                $curInvestValues = $amounts;
                if (!$amounts) {
                    return 0;
                }
                $output = '';
                foreach($amounts as $currency => $amount) {
                    $investValues[$currency] += $amount;
                    $output .= "$currency " . number_format($amount, 2) . '<br />';
                }
                return sprintf('<a target=_blank href="/admin/Project?fields=id,our_amount,_company_short,turn_sub,deal_type,close_date,entity_id&__filter=%s">%s</a>', urlencode('id='.implode(',',$formula['id'])),$output);
            }],
            ['label' => '<a title="当本轮交易类型=企业融资（源码投）时，∑投时持本轮股数">历史投资股数</a>', 'field' => function($model)use($dataList, &$investStocks){
                $formula = [];
                if (!$model->getData('id')) {
                    return number_format($investStocks);
                }
                $stockNum = 0;
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')
                        && $dataItem->getData('stocknum_get')) {
                        $stockNum += $dataItem->getData('stocknum_get');
                        $formula['id'][] = $dataItem->getData('id');
                    }
                }
                $investStocks += $stockNum;
                $output = number_format($stockNum);
                return sprintf('<a target=_blank href="/admin/Project?fields=id,stocknum_get,_company_short,turn_sub,deal_type,close_date,entity_id&__filter=%s">%s</a>', urlencode('id='.implode(',',$formula['id'])),$output);
            }],
            ['label' => '<a title="当本轮交易类型=源码独立CB且借款处理=待处理时，∑源码借款合同金额">独立CB金额</a>', 'field' => function($model)use($dataList, &$cbValues){
                $formula = [];
                if (!$model->getData('id')) {
                    if (!$cbValues) {
                        return 0;
                    }
                    foreach($cbValues as $currency => $amount) {
                        echo "$currency " . number_format($amount, 2) . '<br />';
                    }
                    return;
                }
                $amounts = [];
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('close_date')) {
                        if ($dataItem->getData('loan_entity_id') == $model->getData('id') 
                            && stripos($dataItem->getData('deal_type'), '源码独立CB') !== false
                            && $dataItem->getData('loan_process') == '待处理') {
                            $amounts[$dataItem->getData('loan_currency')] += $dataItem->getData('loan_amount');
                            $formula['id'][] = $dataItem->getData('id');
                        }
                    }
                }
                if (!$amounts) {
                    return 0;
                }
                $output = '';
                foreach($amounts as $currency => $amount) {
                    $cbValues[$currency] += $amount;
                    $output .= "$currency " . number_format($amount, 2) . '<br />';
                }
                return sprintf('<a target=_blank href="/admin/Project?fields=id,loan_amount,loan_process,_company_short,turn_sub,deal_type,close_date,loan_entity_id&__filter=%s">%s</a>', urlencode('id='.implode(',',$formula['id'])),$output);
            }],
            ['label' => '<a title="∑源码本次退出合同金额">退出金额</a>', 'field' => function($model)use($dataList, &$exitValues, &$curExitValues){
                $formula = [];
                if (!$model->getData('id')) {
                    if (!$exitValues) {
                        return 0;
                    }
                    foreach($exitValues as $currency => $amount) {
                        echo "$currency " . number_format($amount, 2) . '<br />';
                    }
                    return;
                }
                $amounts = [];
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('exit_entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')) {
                        $amounts[$dataItem->getData('exit_currency')] += $dataItem->getData('exit_amount');
                        $formula['id'][] = $dataItem->getData('id');
                    }
                }
                $curExitValues = $amounts;
                if (!$amounts) {
                    return 0;
                }
                $output = '';
                foreach($amounts as $currency => $amount) {
                    $exitValues[$currency] += $amount;
                    $output .= "$currency " . number_format($amount, 2) . '<br />';
                }
                return sprintf('<a target=_blank href="/admin/Project?fields=id,exit_amount,_company_short,turn_sub,deal_type,close_date,exit_entity_id&__filter=%s">%s</a>', urlencode('id='.implode(',',$formula['id'])),$output);
            }],
            ['label' => '<a title="∑源码退出的股数">退出股数</a>', 'field' => function($model)use($dataList, &$totalExitStocks){
                $formula = [];
                if (!$model->getData('id')) {
                    return number_format($totalExitStocks);
                }
                $stockNum = 0;
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('exit_entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')) {
                        $stockNum += $dataItem->getData('exit_stock_number');
                        $formula['id'][] = $dataItem->getData('id');
                    }
                }
                if (!$stockNum) {
                    return 0;
                }
                $totalExitStocks += $stockNum;
                $output = number_format($stockNum);
                return sprintf('<a target=_blank href="/admin/Project?fields=id,exit_stock_number,_company_short,turn_sub,deal_type,close_date,exit_entity_id&__filter=%s">%s</a>', urlencode('id='.implode(',',$formula['id'])),$output);
            }],
            ['label' => '<a title="(最新账面价值+退出金额)/历史投资金额">回报倍数</a>', 'field' => function($model)use(&$holdValue, &$curInvestValues, &$curExitValues, &$investValues, &$exitValues) {
                if (!$model->getData('id')) {
                    $curInvestValues = $investValues;
                    $curExitValues = $exitValues;
                }
                $currencys = array_unique(array_merge(
                    array_keys($holdValue), 
                    array_keys($curInvestValues),
                    array_keys($curExitValues)
                ));
                $output = '';
                foreach($currencys as $currency) {
                    if (isset($holdValue[$currency]) 
                        && isset($curInvestValues[$currency])
                        && (count($curExitValues) === 0 || isset($curExitValues[$currency]))) {
                        $output .= "$currency 投资：".number_format(($holdValue[$currency] + $curExitValues[$currency])/$curInvestValues[$currency], 2) . "\n";
                    } else {
                        $output = "多币种项目";
                        break;
                    }
                }
                return $output;
            }],
        ];

        $this->assign('pageAdmin',$this);

        $dealDataList = [];
        $exitDataList = [];
        foreach($dataList as $i => $dataItem) {
            if ($dataItem->getData('close_date') > 0) {
                if (strpos($dataItem->getData('deal_type'),'源码投') !== false) {
                    $dealDataList[] = $dataItem;
                } 
                if (strpos($dataItem->getData('deal_type'),'源码退出') !== false) {
                    $exitDataList[] = $dataItem;
                }
            }
        }
        foreach($dataList as $i => $dataItem) {
            if ($dataItem->getData('close_date') > 0) {
                $latestDeal = $dataItem;
                break;
            }
        }
        $this->assign("dealDataList",$dealDataList);
        $project->setAutoClear(true);
        $this->assign("allDealCount",count($dealDataList));

        return ['admin/project/captable.html', $this->_assigned];
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/check.html', $this->_assigned];
    }

    protected function _initSelect() {
        $this->list_filter = [
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'class'=>'keep-all']),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('_company_short'),'paramName'=>'short|company_id','foreignTable'=>'Model_Company','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('entity_id'),'paramName'=>'name|entity_id','foreignTable'=>'Model_Entity','fusion'=>true,'class'=>'keep-all']),
        ];
        $reqModel = WinRequest::getModel();
        unset($reqModel['tableWrap']);
        WinRequest::setModel($reqModel);
        $list_display = [];
        foreach($this->list_display as $i => $field) {
            if (in_array($field['name'], ['id', '_company_short', 'turn_sub', 'close_date', 'deal_type', 'invest_turn', 'decision_date', 'entity_id'])) {
                $list_display[] = $field;
            }
        }
        $this->list_display = $list_display;
    }

    public function select() {
        $this->_initSelect();
        $this->_index();
        $this->display("admin/base/select.html");
    }

    public function importAction() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' 
            && isset($_FILES['content'])
            && $_FILES['content']['error'] == 0) {
            $csv = fopen($_FILES['content']['tmp_name'], 'r');
            $csvLines = [];
            $csvHeader = [];
            while(!feof($csv)) { // 转置
                $csvLine = trim(fgets($csv));
                if (!$csvLine) continue;
                $utfEnc = mb_detect_encoding($csvLine, 'UTF-8', true);
                if (!$utfEnc) { // 只支持GB编码导入
                    $csvLine = mb_convert_encoding($csvLine, 'UTF-8', 'gbk');
                }
                $colums = str_getcsv($csvLine);
                if (empty($colums[0])) continue;
                $csvHeader[] = $colums[0];
                for($i = 1; $i < count($colums); $i++) {
                    $csvLines[$i][] = $colums[$i];
                }
            }
            /*$csvHeader = fgets($csv);
            $utfEnc = mb_detect_encoding($csvHeader, 'UTF-8', true);
            if (!$utfEnc) { // 只支持GB编码导入
                $csvHeader = mb_convert_encoding($csvHeader, 'UTF-8', 'gbk');
            }
            $csvHeader = str_getcsv($csvHeader);*/
            $this->preMethod('index');

            // 生成字段映射
            $fieldMap = Form_Project::getFieldsMap();
            $dbMap = [];
            foreach($fieldMap as $i => $field) {
                $dbMap[$field['label']] = $field['name'];
            }

            // 生成数据库属性映射
            foreach($csvHeader as $i => $fieldText) {
                $csvHeader[$i] = $dbMap[$fieldText];
            }

            $adminId = Model_Admin::getCurrentAdmin()->mId;
            //while(!feof($csv)) {
            foreach($csvLines as $i => $csvLine) {
                /*
                $csvLine = fgets($csv);
                if (!$utfEnc) {
                    $csvLine = mb_convert_encoding($csvLine, 'UTF-8', 'gbk');
                }
                $csvLine = str_getcsv($csvLine);
                 */
                if (count($csvLine) == count($csvHeader)) { // 表头对应正常
                    $saveData = [];
                    foreach($csvHeader as $i => $fieldName) {
                        // 计算字段不入库
                        if (strpos($fieldName, '_') === 0 /*|| empty($csvLine[$i])*/) {
                            continue;
                        }
                        if (strpos($fieldName, 'company_id') !== false) {
                            $company = new Model_Company;
                            $company->addWhere('name', $csvLine[$i]);
                            $company->select();
                            $csvLine[$i] = $company->mId;
                        } elseif ((strpos($fieldName, '_date') !== false
                            && $csvLine[$i] != 0 
                            && is_numeric($csvLine[$i])) 
                            || $fieldName == 'update_time'
                            || $fieldName == 'create_time') {
                            $csvLine[$i] = strtotime($csvLine[$i]);
                        } elseif (strpos($fieldName, 'entity_id') !== false) {
                            $entity = new Model_Entity;
                            $entity->addWhere('name', $csvLine[$i]);
                            $entity->select();
                            $csvLine[$i] = $entity->mId;
                        } elseif (in_array($fieldName, ['partner','manager','finance_person','legal_person','deal_manager'])) {
                            $mId = Model_Member::getIdsByName($csvLine[$i]);
                            $csvLine[$i] = $mId[0];
                        }
                        $saveData[$fieldName] = $csvLine[$i];
                    }
                    if (empty($saveData['id'])) {
                        unset($saveData['id']);
                    }
                    if (isset($saveData['id'])
                        && !Model_AdminGroup::isCurrentAdminRoot()
                        && !Model_ItemPermission::isDealAuth($adminId, $saveData['id'])) {
                        continue;
                    } 
                    if (!empty($saveData)) {
                        if (!isset($saveData['id'])) {
                            $saveData['create_time'] = time();
                        }
                        $saveData['status'] = 'valid';
                        $saveData['update_time'] = time();
                        $this->model->clear();
                        $this->model->setData($saveData);
                        $this->model->save();
                    }
                } else {
                }
            }

            fclose($csv);
            return ["redirect: /admin/project"];
        }
        $this->form=new Form([
            ['name'=>'id','label'=>'交易记录csv','type'=>'file'],
        ]);
        return ["admin/project/import.html"];
    }
}


