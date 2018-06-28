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
                return '/admin/project?action=clone&ex=kickoff_date,close_date,loan_cb,loan_currency,loan_type,loan_entity_id,loan_amount,loan_sign_date,loan_end_date,loan_process,loan_memo&id='.$model->mId;
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
        );
    }

    private function _initListFilter() {
        $this->list_filter=array(
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('_company_short'),'paramName'=>'short|company_id','foreignTable'=>'Model_Company','fusion'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'hidden'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('entity_id'),'paramName'=>'entity_id','fusion'=>false,'hidden'=>true,'class'=>'keep-all']),
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('exit_entity_id'),'paramName'=>'exit_entity_id','fusion'=>false,'hidden'=>true,'class'=>'keep-all']),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('entity_id'),'paramName'=>'name|entity_id','foreignTable'=>'Model_Entity','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('exit_entity_id'),'paramName'=>'name|exit_entity_id','foreignTable'=>'Model_Entity','fusion'=>true]),
            new Page_Admin_TimeRangeFilter(['name'=>Form_Project::getFieldViewName('decision_date'),'paramName'=>'decision_date']),
            new Page_Admin_TimeRangeFilter(['name'=>Form_Project::getFieldViewName('close_date'),'paramName'=>'close_date']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('item_status'),'paramName'=>'item_status','choices'=>Model_Project::getItemStatusChoices(),'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('proj_status'),'paramName'=>'proj_status','choices'=>Model_Project::getProjStatusChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('deal_type'),'paramName'=>'deal_type','choices'=>Model_Project::getDealTypeChoices(),'class'=>'keep-all']),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('new_follow'),'paramName'=>'new_follow','choices'=>Model_Project::getNewFollowChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('enter_exit_type'),'paramName'=>'enter_exit_type','choices'=>Model_Project::getEnterExitTypeChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('loan_type'),'paramName'=>'loan_type','choices'=>Model_Project::getLoanTypeChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('has_exit'),'paramName'=>'has_exit','choices'=>Model_Project::getStandardOptionChoices()]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('_current_legal_person'),'paramName'=>'legal_person|company_id','foreignTable'=>'Model_Company','fusion'=>true]),
            new Page_Admin_ChoiceFilter(['name'=>'财务校对','paramName'=>'finance_check_sign','choices'=>[['未校对','未校对', 'and (`finance_check_sign` = "" or `finance_check_sign` is NULL)'],['已校对','已校对','and `finance_check_sign` is not null and `finance_check_sign` != ""']]]),
            new Page_Admin_ChoiceFilter(['name'=>'法务校对','paramName'=>'legal_check_sign','choices'=>[['未校对','未校对', 'and (`legal_check_sign` = "" or `legal_check_sign` is NULL)'],['已校对','已校对','and `legal_check_sign` is not null and `legal_check_sign` != ""']]]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('pending'),'paramName'=>'pending','choices'=>Model_Project::getPendingChoices(),'class'=>'keep-all']),
        );
    }

    public function __construct(){
        parent::__construct();

        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        $this->model=new Model_Project();
        $this->model->orderBy('id', 'DESC');

        WinRequest::mergeModel(array(
            'controllerText' => '交易记录',
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
            Form_Project::getFieldViewName('kickoff_date') => [],
            Form_Project::getFieldViewName('close_date') => [],
            Form_Project::getFieldViewName('deal_type') => [],
            Form_Project::getFieldViewName('turn_sub') => [],
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
            Form_Project::getFieldViewName('work_memo') => [],
        ];

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
            $this->model->addWhere('id', $_REQUEST['id'])->update(['status'=>['"valid"', DBTable::NO_ESCAPE]]);
            $this->model->select();
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
        $this->model->addWhere('id', $_REQUEST['id'])->update(['status'=>['"invalid"', DBTable::NO_ESCAPE]]);
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
                    $fields[$i]->clear();
                }
            }
        }
        return $ret;
    }

    public function captableAction() {
        $reqModel = WinRequest::getModel();
        $reqModel['controllerText'] = '企业投退Captable';
        WinRequest::setModel($reqModel);

        $object = New Model_Company;
        $object->addWhere('id', $_GET['company_id']);
        $objectList = $object->find();
        $this->assign('objectDataList', $objectList);
        
        $project = new Model_Project;
        $project->addWhere('company_id', $_GET['company_id']);
        $project->addWhere('status', 'valid');
        $project->orderBy('close_date', 'DESC');
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
                $entity = new Model_Entity;
                $entity->mId = $entityId;
                $entity->select();
                return $entity->getData('name');
            }],
            ['label' => '股份类型', 'field' => function($model) {
                return $model->getData('new_old_stock');
            }],
            ['label' => '交割日期', 'field' => function($model) {
                return date('Ymd', $model->getData('close_date'));
            }],
            ['label' => '企业轮次', 'field' => function($model) {
                return $model->getData('turn_sub');
            }],
            ['label' => '股权轮次', 'field' => function($model) {
                return $model->getData('invest_turn');
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
            ['label' => '股价', 'field' => function($model) {
                $currency = $model->getData('invest_currency');
                $amount = $model->getData('our_amount');
                $stockNum = $model->getData('stocknum_get');
                $amount = $amount ? $amount : 0;
                if ($stockNum) {
                    return "$currency " . number_format($amount/$stockNum, 2); 
                }
            }],
            ['label' => 'Post估值', 'field' => function($model) {
                return $model->getData('value_currency') . ' ' . number_format($model->getData('post_money'), 2);
            }],
            ['label' => '退出金额', 'field' => function($model)use(&$exitDataList) {
                $turn = $model->getData('invest_turn');
                $amounts = [];
                foreach($exitDataList as $i => $turnData) {
                    if ($turnData->getData('exit_turn') == $turn) {
                        $amounts[$turnData->getData('exit_currency')] += $turnData->getData('exit_amount');
                    }
                }
                $output = '';
                foreach($amounts as $currency => $amount) {
                    $output .= "$currency " . number_format($amount, 2) . '<br />';
                }
                return $output;
            }],
            ['label' => '退出股数', 'field' => function($model)use(&$exitDataList) {
                $turn = $model->getData('invest_turn');
                $amount = 0;
                foreach($exitDataList as $i => $turnData) {
                    if ($turnData->getData('exit_turn') == $turn) {
                        $amount += $turnData->getData('exit_stock_number');
                    }
                }
                return number_format($amount);
            }],
            ['label' => '投时股比', 'field' => function($model)use(&$latestDeal,&$exitDataList) {
                if ($model->getData('stocknum_all')) {
                    return sprintf('%.2f%%',$model->getData('stocknum_get')/$model->getData('stocknum_all')*100);
                }
            }],
            ['label' => '最新股比', 'field' => function($model)use(&$latestDeal,&$exitDataList) {
                $turn = $model->getData('invest_turn');
                $exitStockNum = 0;
                foreach($exitDataList as $i => $turnData) {
                    if ($turnData->getData('exit_turn') == $turn) {
                        $exitStockNum += $turnData->getData('exit_stock_number');
                    }
                }
                return sprintf('%.2f%%',($model->getData('stocknum_get') - $exitStockNum)/$latestDeal->getData('stocknum_all')*100);
            }],
        ];

        $object_display = Form_Company::getFieldsMap();
        $briefFields = [
            Form_Company::getFieldViewName('id') => [],
            Form_Company::getFieldViewName('name') => [],
            Form_Company::getFieldViewName('short') => [],
            Form_Company::getFieldViewName('manager') => [],
            Form_Company::getFieldViewName('legal_person') => [],
            Form_Company::getFieldViewName('_stocknum_all') => [],
            Form_Company::getFieldViewName('_latest_post_moeny') => [],
            ['label' => '最新企业每股单价', 'field' => function($model)use($dataList) {
                if ($dataList[0]->getData('stocknum_all')) {
                    return $dataList[0]->getData('value_currency') . ' ' . number_format($dataList[0]->getData('post_money')/$dataList[0]->getData('stocknum_all'), 2);
                }
            }],
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
            ['label' => '最新持有股数', 'field' => function($model)use($dataList, &$holdStocks){
                if (!$model->getData('id')) {
                    return number_format($holdStocks);
                }
                $stockNum = 0;
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')) {
                        $stockNum += $dataItem->getData('stocknum_get');
                    }
                    if ($dataItem->getData('exit_entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')) {
                        $stockNum -= $dataItem->getData('exit_stock_number');
                    }
                }
                $holdStocks += $stockNum;
                return number_format($stockNum);
            }],
            ['label' => '最新持股比例', 'field' => function($model)use($dataList, &$holdStocks){
                if (!$model->getData('id')) {
                    return sprintf('%.2f%%', $holdStocks/$dataList[0]->getData('stocknum_all')*100);
                }
                $stockNum = 0;
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')) {
                        $stockNum += $dataItem->getData('stocknum_get');
                    }
                    if ($dataItem->getData('exit_entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')) {
                        $stockNum -= $dataItem->getData('exit_stock_number');
                    }
                }
                return sprintf('%.2f%%', $stockNum/$dataList[0]->getData('stocknum_all')*100);
            }],
            ['label' => '最新账面价值', 'field' => function($model)use($dataList, &$holdStocks){
                if (!$model->getData('id')) {
                    return $dataList[0]->getData('value_currency') . ' ' . number_format($holdStocks/$dataList[0]->getData('stocknum_all')*$dataList[0]->getData('post_money'), 2);
                }
                $stockNum = 0;
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')) {
                        $stockNum += $dataItem->getData('stocknum_get');
                    }
                    if ($dataItem->getData('exit_entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')) {
                        $stockNum -= $dataItem->getData('exit_stock_number');
                    }
                }
                return $dataList[0]->getData('value_currency') . ' ' . number_format($stockNum/$dataList[0]->getData('stocknum_all')*$dataList[0]->getData('post_money'), 2);
            }],
            ['label' => '投资金额', 'field' => function($model)use($dataList, &$investValues){
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
                    if ($dataItem->getData('entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')) {
                        $amounts[$dataItem->getData('invest_currency')] += $dataItem->getData('our_amount');
                    }
                }
                if (!$amounts) {
                    return 0;
                }
                foreach($amounts as $currency => $amount) {
                    $investValues[$currency] += $amount;
                    echo "$currency " . number_format($amount, 2) . '<br />';
                }
            }],
            ['label' => '投资股数', 'field' => function($model)use($dataList, &$investStocks){
                if (!$model->getData('id')) {
                    return number_format($investStocks);
                }
                $stockNum = 0;
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')) {
                        $stockNum += $dataItem->getData('stocknum_get');
                    }
                }
                $investStocks += $stockNum;
                return number_format($stockNum);
            }],
            ['label' => '退出金额', 'field' => function($model)use($dataList, &$exitValues){
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
                    }
                }
                if (!$amounts) {
                    return 0;
                }
                foreach($amounts as $currency => $amount) {
                    $exitValues[$currency] += $amount;
                    echo "$currency " . number_format($amount, 2) . '<br />';
                }
            }],
            ['label' => '退出股数', 'field' => function($model)use($dataList, &$exitStocks){
                if (!$model->getData('id')) {
                    return number_format($exitStocks);
                }
                $stockNum = 0;
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('exit_entity_id') == $model->getData('id') 
                        && $dataItem->getData('close_date')) {
                        $stockNum += $dataItem->getData('exit_stock_number');
                    }
                }
                $exitStocks += $stockNum;
                return number_format($stockNum);
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
        $latestDeal = $dataList[0];
        $this->assign("dealDataList",$dealDataList);
        $project->setAutoClear(true);
        $this->assign("allDealCount",count($dealDataList));

        return ['admin/project/captable.html', $this->_assigned];
    }

    public function checkAction() {
        $_REQUEST['action'] = 'read';
        $this->indexAction();
        return ['admin/project/check.html', $this->_assigned];
    }
}


