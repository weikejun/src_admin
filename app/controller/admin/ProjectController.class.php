<?php
class ProjectController extends Page_Admin_Base {
    use ControllerPreproc;
    use ExportActions;

    private $_objectCache = [];

    private function _initForm() {
        $this->form=new Form_Project();
    }

    private function _initListDisplay() {
        $companyCache = new Model_Company;
        $this->list_display = [];
        foreach(Form_Project::getFieldsMap() as $field) {
            if ($field['type'] != 'seperator') {
                $this->list_display[] = [
                    'label' => $field['label'],
                    'field' => (isset($field['field']) ? $field['field'] : $field['name']),
                ];
            }
        }
    }

    private function _initSingleActions() {
        $this->single_actions=[
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
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('id'),'paramName'=>'id','fusion'=>false,'hidden'=>true]),
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('entity_id'),'paramName'=>'entity_id','fusion'=>false,'hidden'=>true]),
            new Page_Admin_TextFilter(['name'=>Form_Project::getFieldViewName('exit_entity_id'),'paramName'=>'exit_entity_id','fusion'=>false,'hidden'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('_company_short'),'paramName'=>'short|company_id','foreignTable'=>'Model_Company','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('company_id'),'paramName'=>'name|company_id','foreignTable'=>'Model_Company','fusion'=>true]),
            new Page_Admin_TextForeignFilter(['name'=>Form_Project::getFieldViewName('entity_id'),'paramName'=>'name|entity_id','foreignTable'=>'Model_Entity','fusion'=>true]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('item_status'),'paramName'=>'item_status','choices'=>Model_Project::getItemStatusChoices()]),
            new Page_Admin_ChoiceFilter(['name'=>Form_Project::getFieldViewName('turn'),'paramName'=>'turn','choices'=>Model_Project::getTurnChoices()]),
        );
    }

    public function __construct(){
        parent::__construct();

        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());

        $this->model=new Model_Project();
        $this->model->orderBy('kickoff_date', 'DESC');

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
            'tableWrap' => '2048px',
        ));

        $briefFields = [
            '交易ID' => [],
            '目标企业' => [],
            '项目简称' => [],
            '整理状态' => [],
            '项目新老类型' => [],
            '源码投资主体' => [],
            '决策日期' => [],
            '交易状态' => [],
            '签约日期' => [],
            '交割日期' => [],
            '本轮交易类型' => [],
            '企业所处轮次' => [],
            '企业轮次归类' => [],
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
            ['label' => '交割日期', 'field' => function($model) {
                return date('Ymd', $model->getData('close_date'));
            }],
            ['label' => '交易类型', 'field' => function($model) {
                return $model->getData('deal_type');
            }],
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
            ['label' => '交易所属轮次', 'field' => function($model) {
                if ($model->getData('deal_type') == '源码退出') {
                    $field = 'exit_turn'; 
                } else {
                    $field = 'turn_sub';
                }
                return $model->getData($field);
            }],
            ['label' => '交易股数', 'field' => function($model) {
                if ($model->getData('deal_type') == '源码退出') {
                    $field = 'exit_stock_number'; 
                } else {
                    $field = 'stocknum_get';
                }
                return number_format($model->getData($field));
            }],
            ['label' => '交易金额', 'field' => function($model) {
                if ($model->getData('deal_type') == '源码退出') {
                    $currency = $model->getData('exit_currency');
                    $amount = $model->getData('exit_amount');
                } else {
                    $currency = $model->getData('invest_currency');
                    $amount = $model->getData('our_amount');
                }
                $amount = $amount ? $amount : 0;
                return $currency . ' ' . number_format($amount, 2);
            }],
            ['label' => '交易价格', 'field' => function($model) {
                if ($model->getData('deal_type') == '源码退出') {
                    $currency = $model->getData('exit_currency');
                    $amount = $model->getData('exit_amount');
                    $stockNum = $model->getData('exit_stock_number');
                } else {
                    $currency = $model->getData('invest_currency');
                    $amount = $model->getData('our_amount');
                    $stockNum = $model->getData('stocknum_get');
                }
                $amount = $amount ? $amount : 0;
                if ($stockNum) {
                    return "$currency " . number_format($amount/$stockNum, 2); 
                }
            }],
            ['label' => '交易企业估值', 'field' => function($model) {
                return $model->getData('value_currency') . ' ' . number_format($model->getData('post_money'), 2);
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
        foreach($dataList as $i => $dataItem) {
            if ($dataItem->getData('close_date') > 0)
                $dealDataList[] = $dataItem;
        }
        $this->assign("dealDataList",$dealDataList);
        $project->setAutoClear(true);
        $this->assign("allDealCount",count($dealDataList));

        return ['admin/project/captable.html', $this->_assigned];
    }

    public function entityAction() {
        $reqModel = WinRequest::getModel();
        $reqModel['controllerText'] = '主体持股概况';
        WinRequest::setModel($reqModel);

        $project = new Model_Project;
        $project->addWhere('status', 'valid');
        $project->addWhereRaw(sprintf(' and (`entity_id` = %d or `exit_entity_id` = %d)', $_GET['entity_id'], $_GET['entity_id']));
        $project->orderBy('close_date', 'DESC');
        $project->setAutoClear(false);
        $dataList=$project->find();

        $object = new Model_Entity;
        $object->addWhere('id', $_GET['entity_id']);
        $objectList = $object->find();
        $this->assign('objectDataList', $objectList);

        // 取captable数据，以公司为目标
        $capIds = [];
        foreach($dataList as $i => $dataItem) {
            if ($dataItem->getData('company_id')) {
                $capIds[$dataItem->getData('company_id')] = 1;
            }
        }
        $captable = new Model_Company;
        $captable->addWhere('id', array_keys($capIds), 'IN', DBTable::ESCAPE);
        $captableList = $captable->find();
        $captableList[] = new Model_Company;
        $this->assign('captableDataList', $captableList);
        
        $this->deal_display = [
            ['label' => '交割日期', 'field' => function($model) {
                return date('Ymd', $model->getData('close_date'));
            }],
            ['label' => '交易类型', 'field' => function($model) {
                return $model->getData('deal_type');
            }],
            ['label' => '目标公司', 'field' => function($model) {
                $company = new Model_Company;
                $company->addWhere('id', $model->getData('company_id'));
                $company->select();
                return $company->getData('name');
            }],
            ['label' => '股份类型', 'field' => function($model) {
                return $model->getData('new_old_stock');
            }],
            ['label' => '交易所属轮次', 'field' => function($model) {
                $field = $model->getData('deal_type') == '源码退出' 
                    ? 'exit_turn' : 'turn_sub';
                return $model->getData($field);
            }],
            ['label' => '交易股数', 'field' => function($model) {
                $field = $model->getData('deal_type') == '源码退出' 
                    ? 'exit_stock_number' : 'stocknum_get'; 
                if ($model->getData($field)) {
                    return number_format($model->getData($field));
                }
                return 0;
            }],
            ['label' => '交易金额', 'field' => function($model) {
                if ($model->getData('deal_type') == '源码退出') {
                    $currency = $model->getData('exit_currency');
                    $amount = $model->getData('exit_amount');
                } else {
                    $currency = $model->getData('invest_currency');
                    $amount = $model->getData('our_amount');
                }
                $amount = $amount ? $amount : 0;
                return $currency . ' ' . number_format($amount, 2);
            }],
            ['label' => '交易价格', 'field' => function($model) {
                if ($model->getData('deal_type') == '源码退出') {
                    $currency = $model->getData('exit_currency');
                    $amount = $model->getData('exit_amount');
                    $stockNum = $model->getData('exit_stock_number');
                } else {
                    $currency = $model->getData('invest_currency');
                    $amount = $model->getData('our_amount');
                    $stockNum = $model->getData('stocknum_get');
                }
                if ($stockNum) {
                    return "$currency " . number_format($amount/$stockNum, 2); 
                }
            }],
            ['label' => '交易企业估值', 'field' => function($model) {
                if ($model->getData('post_money')) {
                    return $model->getData('value_currency') . ' ' . number_format($model->getData('post_money'), 2);
                }
            }],
        ];

        $object_display = Form_Entity::getFieldsMap();
        $this->object_display = [];
        for($i = 0; $i < count($object_display); $i++) {
            if (!in_array($object_display[$i]['name'], array('memo','update_time','_invest_num','_exit_num','_hold_company'))) {
                $this->object_display[] = $object_display[$i];
            }
        }
        foreach($this->object_display as $i => &$field) {
            if (!isset($field['field'])) {
                $field['field'] = $field['name'];
            }
        }

        $holdValues = [];
        $investValues = [];
        $exitValues = [];
        $this->captable_display = [
            ['label' => '目标企业', 'field' => function($model) {
                if ($model->getData('id')) {
                    return $model->getData('name');
                }

                return '合计';
            }],
            ['label' => '最新持有股数', 'field' => function($model)use($dataList){
                if (!$model->getData('id')) {
                    return '';
                }
                $stockNum = 0;
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('company_id') != $model->getData('id')) {
                        continue;
                    }
                    if ($dataItem->getData('close_date') 
                        && $dataItem->getData('deal_type') == '源码退出'
                        && $dataItem->getData('exit_entity_id') == $_GET['entity_id']) {
                        $stockNum -= $dataItem->getData('exit_stock_number');
                    } 
                    if ($dataItem->getData('close_date') 
                        && $dataItem->getData('entity_id') == $_GET['entity_id']) {
                        $stockNum += $dataItem->getData('stocknum_get');
                    } 
                }
                return number_format($stockNum);
            }],
            ['label' => '最新持股比例', 'field' => function($model)use($dataList){
                if (!$model->getData('id')) {
                    return '';
                }
                $stockNum = 0;
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('company_id') != $model->getData('id')) {
                        continue;
                    }
                    if ($dataItem->getData('close_date') 
                        && $dataItem->getData('deal_type') == '源码退出'
                        && $dataItem->getData('exit_entity_id') == $_GET['entity_id']) {
                        $stockNum -= $dataItem->getData('exit_stock_number');
                    } 
                    if ($dataItem->getData('close_date') 
                        && $dataItem->getData('entity_id') == $_GET['entity_id']) {
                        $stockNum += $dataItem->getData('stocknum_get');
                    } 
                }
                $project = new Model_Project;
                $project->addWhere('company_id', $model->getData('id'));
                $project->addWhere('status', 'valid');
                $project->addWhere('close_date', '0', '>');
                $project->orderBy('close_date', 'DESC');
                $project->select();
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('company_id') == $model->getData('id')) {
                        return sprintf('%.2f%%', $stockNum/$project->getData('stocknum_all')*100);
                    }
                }
            }],
            ['label' => '最新账面价值', 'field' => function($model)use($dataList, &$holdValues){
                if (!$model->getData('id')) {
                    if (!$holdValues) return 0;
                    foreach($holdValues as $currency => $amount) {
                        echo "$currency " . number_format($amount, 2);
                    }
                    return;
                }
                $stockNum = 0;
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('company_id') != $model->getData('id')) {
                        continue;
                    }
                    if ($dataItem->getData('close_date') 
                        && $dataItem->getData('deal_type') == '源码退出'
                        && $dataItem->getData('exit_entity_id') == $_GET['entity_id']) {
                        $stockNum -= $dataItem->getData('exit_stock_number');
                    } 
                    if ($dataItem->getData('close_date') 
                        && $dataItem->getData('entity_id') == $_GET['entity_id']) {
                        $stockNum += $dataItem->getData('stocknum_get');
                    } 
                }
                $project = new Model_Project;
                $project->addWhere('company_id', $model->getData('id'));
                $project->addWhere('status', 'valid');
                $project->addWhere('close_date', '0', '>');
                $project->orderBy('close_date', 'DESC');
                $project->select();
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('company_id') == $model->getData('id')) {
                        $currency = $dataItem->getData('value_currency');
                        $amount = $stockNum/$project->getData('stocknum_all')*$project->getData('post_money');
                        $holdValues[$currency] += $amount;
                        return "$currency " . number_format($amount, 2);
                    }
                }
            }],
            ['label' => '投资金额', 'field' => function($model)use($dataList, &$investValues){
                if (!$model->getData('id')) {
                    if (!$investValues) return 0;
                    foreach($investValues as $currency => $amount) {
                        echo "$currency " . number_format($amount, 2) . '<br />';
                    }
                    return;
                }
                $amounts = [];
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('company_id') != $model->getData('id')) {
                        continue;
                    }
                    if ($dataItem->getData('close_date') 
                        && $dataItem->getData('entity_id') == $_GET['entity_id']) {
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
            ['label' => '退出金额', 'field' => function($model)use($dataList, &$exitValues){
                if (!$model->getData('id')) {
                    if (!$exitValues) return 0;
                    foreach($exitValues as $currency => $amount) {
                        echo "$currency " . number_format($amount, 2);
                    }
                    return;
                }
                $amounts = [];
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('company_id') != $model->getData('id')) {
                        continue;
                    }
                    if ($dataItem->getData('close_date') 
                        && $dataItem->getData('deal_type') == '源码退出'
                        && $dataItem->getData('exit_entity_id') == $_GET['entity_id']) {
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
            ['label' => '投资股数', 'field' => function($model)use($dataList){
                if (!$model->getData('id')) {
                    return '';
                }
                $stockNum = 0;
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('company_id') != $model->getData('id')) {
                        continue;
                    }
                    if ($dataItem->getData('close_date') 
                        && $dataItem->getData('entity_id') == $_GET['entity_id']) {
                        $stockNum += $dataItem->getData('stocknum_get');
                    }
                }
                return number_format($stockNum);
            }],
            ['label' => '退出股数', 'field' => function($model)use($dataList){
                if (!$model->getData('id')) {
                    return '';
                }
                $stockNum = 0;
                foreach($dataList as $i => $dataItem) {
                    if ($dataItem->getData('company_id') != $model->getData('id')) {
                        continue;
                    }
                    if ($dataItem->getData('close_date') 
                        && $dataItem->getData('deal_type') == '源码退出'
                        && $dataItem->getData('exit_entity_id') == $_GET['entity_id']) {
                        $stockNum += $dataItem->getData('exit_stock_number');
                    }
                }
                return number_format($stockNum);
            }],
        ];

        $this->assign('pageAdmin',$this);

        $dealDataList = [];
        foreach($dataList as $i => $dataItem) {
            if ($dataItem->getData('close_date') > 0)
                $dealDataList[] = $dataItem;
        }
        $this->assign("dealDataList",$dealDataList);
        $project->setAutoClear(true);

        return ['admin/project/captable.html', $this->_assigned];
    }
}


