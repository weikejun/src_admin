<?php
class DataStatController extends Page_Admin_Base {
    use ExportActions;
    public function __construct(){
        parent::__construct();
        $this->addInterceptor(new AdminLoginInterceptor());
        $this->addInterceptor(new AdminAuthInterceptor());
        WinRequest::mergeModel(array(
            'controllerText'=>"数据统计",
            'tableWrap' => '2048px',
        ));

        $this->hide_action_new = true;
        $this->hide_item_op = true;
        $this->list_filter = [
            new Page_Admin_TimeRangeFilter(['name'=>'统计日期','paramName'=>'select_date','class'=>'keep-all']),
        ];
    }

    protected function _initIndex() {
        $this->multi_actions=array(
            ['label'=>'导出csv','required'=>false,'action'=>'/admin/dataStat/exportToCsv?__filter='.urlencode($this->_GET("__filter"))],
        );
        $deal = new Model_Project;
        $deal->addWhere('status', 'valid');
        $deals = $deal->find();
        $this->model = [];
        $project = new Model_Project;
        $project->mWorkMemo = '交割';
        $this->model[] = $project;
        $project = new Model_Project;
        $project->mWorkMemo = '决策';
        $this->model[] = $project;
        $modelDataList=$this->model;
        $this->assign("modelDataList",$modelDataList);
        $this->list_display=array(
            ['label'=>'统计口径','field'=>function($model)use(&$timeField,&$selectDate){
                $timeField = 'close_date';
                if ($model->getData('work_memo') == '决策') {
                    $timeField = 'decision_date';
                }
                list($selectDate['start'], $selectDate['end']) = explode('&', $_GET['__filter']);
                list($temp, $selectDate['start']) = explode('=', $selectDate['start']);
                list($temp, $selectDate['end']) = explode('=', $selectDate['end']);
                if (!$selectDate['end']) {
                    $selectDate['end'] = date('Ymd');
                }
                if (!$selectDate['start']) {
                    $selectDate['start'] = date('Ymd', strtotime('20100101'));
                }
                return $model->getData('work_memo'); 
            }],
            ['label'=>'统计日期','field'=>function($model)use(&$timeField,&$selectDate){
                return '<a target=__blank href="/admin/Project?__filter=' . urlencode($timeField."__start=".$selectDate['start'].'&'.$timeField.'__end='.$selectDate['end']) . '">' . $selectDate['start'] . ' - ' . $selectDate['end'] . '</a>';
            }],
            ['label'=>'投资企业数','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $company = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])
                        && (strpos($deal->getData('deal_type'),'源码投') !== false 
                        || stripos($deal->getData('deal_type'),'源码独立CB') !== false)) {
                        $company[$deal->getData('company_id')]++;
                    }
                }
                return count($company);
            }],
            ['label'=>'新老项目分布','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $company = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])
                        && (strpos($deal->getData('deal_type'),'源码投') !== false 
                        || stripos($deal->getData('deal_type'),'源码独立CB') !== false)) {
                        $company[$deal->getData('company_id')][$deal->getData('new_follow')]++;
                    }
                }
                $newOldType = [];
                foreach($company as $companyId => $type) {
                    if (isset($type['新项目'])) {
                        $newOldType['新项目']++;
                        continue;
                    } elseif (isset($type['其他'])) {
                        $newOldType['其他']++;
                        continue;
                    }
                    $newOldType['老项目']++;
                }
                ksort($newOldType);
                $output = '';
                foreach($newOldType as $type => $no) {
                    $output .= "$type - $no <br />";
                }
                return $output;
            }],
            ['label'=>'参与投资轮次','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $company = [];
                $dealNum = 0;
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])
                        && (strpos($deal->getData('deal_type'),'源码投') !== false 
                        || stripos($deal->getData('deal_type'),'源码独立CB') !== false)) {
                        $company[$deal->getData('company_id')][$deal->getData('turn_sub')]++;
                        $dealNum++;
                    }
                }
                $turnNo = 0;
                foreach($company as $id => $turn) {
                    $turnNo += count($turn);
                }
                return $turnNo;
            }],
            ['label'=>'参与交易次数','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $company = [];
                $dealNum = 0;
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])
                        && (strpos($deal->getData('deal_type'),'源码投') !== false 
                        || stripos($deal->getData('deal_type'),'源码独立CB') !== false)) {
                        $company[$deal->getData('company_id')][$deal->getData('turn_sub')]++;
                        $dealNum++;
                    }
                }
                $turnNo = 0;
                foreach($company as $id => $turn) {
                    $turnNo += count($turn);
                }
                return '<a target=_blank href="/admin/Project?__filter=' . urlencode($timeField."__start=".$selectDate['start'].'&'.$timeField.'__end='.$selectDate['end'].'&deal_type=企业融资（源码投）') . "\">$dealNum</a>";
            }],
            ['label'=>'投时企业轮次分布','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $turns = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])
                        && (strpos($deal->getData('deal_type'),'源码投') !== false 
                        || stripos($deal->getData('deal_type'),'源码独立CB') !== false)) {
                        $companyId = $deal->getData('company_id');
                        $closeDate = $deal->getData($timeField);
                        $turns[$companyId][$closeDate] = $deal->getData('turn');
                    }
                }
                $turnNo = [];
                foreach($turns as $companyId => $turnData) {
                    foreach($turnData as $closeDate => $turn) {
                        $turnNo[$turn]++;
                        break;
                    }
                }
                $output = '';
                foreach($turnNo as $turn => $no) {
                    $output .= "$turn - $no<br />";
                }
                return $output;
            }],
            ['label'=>'最新企业轮次分布','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $turns = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])) {
                        $companyId = $deal->getData('company_id');
                        $closeDate = $deal->getData($timeField);
                        $turns[$companyId][$closeDate] = $deal->getData('turn');
                    }
                }
                $turnNo = [];
                foreach($turns as $companyId => $turnData) {
                    krsort($turnData);
                    foreach($turnData as $closeDate => $turn) {
                        $turnNo[$turn]++;
                        break;
                    }
                }
                $output = '';
                foreach($turnNo as $turn => $no) {
                    $output .= "$turn - $no<br />";
                }
                return $output;
            }],
            ['label'=>'投时企业阶段分布','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $turns = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])
                        && (strpos($deal->getData('deal_type'),'源码投') !== false 
                        || stripos($deal->getData('deal_type'),'源码独立CB') !== false)) {
                        $companyId = $deal->getData('company_id');
                        $closeDate = $deal->getData($timeField);
                        $turns[$companyId][$closeDate] = $deal->getData('company_period');
                    }
                }
                $turnNo = [];
                foreach($turns as $companyId => $turnData) {
                    foreach($turnData as $closeDate => $turn) {
                        $turnNo[$turn]++;
                        break;
                    }
                }
                $output = '';
                foreach($turnNo as $turn => $no) {
                    $output .= "$turn - $no<br />";
                }
                return $output;
            }],
            ['label'=>'最新企业阶段分布','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $turns = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])) {
                        $companyId = $deal->getData('company_id');
                        $closeDate = $deal->getData($timeField);
                        $turns[$companyId][$closeDate] = $deal->getData('company_period');
                    }
                }
                $turnNo = [];
                foreach($turns as $companyId => $turnData) {
                    krsort($turnData);
                    foreach($turnData as $closeDate => $turn) {
                        $turnNo[$turn]++;
                        break;
                    }
                }
                $output = '';
                foreach($turnNo as $turn => $no) {
                    $output .= "$turn - $no<br />";
                }
                return $output;
            }],
            ['label'=>'退出企业数','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $company = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])
                        && strpos($deal->getData('deal_type'),'源码退') !== false) {
                        $company[$deal->getData('company_id')]++;
                    }
                }
                return count($company);
            }],
            ['label'=>'退出次数','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $exit;
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])
                        && strpos($deal->getData('deal_type'),'源码退') !== false) {
                        $exit++;
                    }
                }
                return '<a target=_blank href="/admin/Project?__filter=' . urlencode($timeField."__start=".$selectDate['start'].'&'.$timeField.'__end='.$selectDate['end'].'&deal_type=源码退出') . "\">$exit</a>";
            }],
            ['label'=> '被投企业融资总额','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $amounts = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])
                        && strpos($deal->getData('deal_type'),'企业融资') !== false) {
                        $turn = $deal->getData('turn_sub');
                        $currency = $deal->getData('value_currency');
                        $companyId = $deal->getData('company_id');
                        if (isset($amounts[$companyId][$turn][$currency])) {
                            continue;
                        }
                        if ($deal->getData('financing_amount')) {
                            $amounts[$companyId][$turn][$currency] = $deal->getData('financing_amount');
                        }
                    }
                }
                $sumAmounts = [];
                foreach($amounts as $cid => $company) {
                    foreach($company as $turn => $turnAmount) {
                        foreach($turnAmount as $currency => $amount) {
                            $sumAmounts[$currency] += $amount;
                        }
                    }
                }
                $amounts = $sumAmounts;
                ksort($amounts);
                $output = '';
                foreach($amounts as $currency => $amount) {
                    $amount = number_format($amount, 2);
                    $output .= "$currency $amount <br />";
                }
                return $output;
            }],
            ['label'=>'源码投资总额','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $amounts = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])
                        && strpos($deal->getData('deal_type'),'源码投') !== false) {
                        $amounts[$deal->getData('invest_currency')] += $deal->getData('our_amount');
                    }
                }
                ksort($amounts);
                $output = '';
                foreach($amounts as $currency => $amount) {
                    $amount = number_format($amount, 2);
                    $output .= "$currency $amount <br />";
                }
                return $output;
            }],
            ['label'=>'源码退出总额','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $amounts = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])
                        && strpos($deal->getData('deal_type'),'源码退') !== false) {
                        $amounts[$deal->getData('exit_currency')] += $deal->getData('exit_amount');
                    }
                }
                ksort($amounts);
                $output = '';
                foreach($amounts as $currency => $amount) {
                    $amount = number_format($amount, 2);
                    $output .= "$currency $amount <br />";
                }
                return $output;
            }],
            ['label'=>'源码退出金额成本','field'=>function($model)use(&$deals, &$timeField, &$selectDate, &$exitStocks, &$investStocks){
                $exitStocks = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])
                        && strpos($deal->getData('deal_type'),'源码退') !== false) {
                        $turn = $deal->getData('exit_turn');
                        $companyId = $deal->getData('company_id');
                        if ($deal->getData('exit_stock_number')) {
                            $exitStocks[$companyId][$turn]['stock'] += $deal->getData('exit_stock_number');
                        }
                    }
                }
                $investStocks = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField)
                        && strpos($deal->getData('deal_type'),'源码投') !== false) {
                        $turn = $deal->getData('invest_turn');
                        $companyId = $deal->getData('company_id');
                        if ($deal->getData('stocknum_get')) {
                            $investStocks[$companyId][$turn]['stock'] += $deal->getData('stocknum_get');
                            $investStocks[$companyId][$turn]['amount'] += $deal->getData('our_amount');
                            $investStocks[$companyId][$turn]['currency'] = $deal->getData('invest_currency');
                        }
                    }
                }
                $amounts = [];
                foreach($exitStocks as $companyId => $turnData) {
                    foreach($turnData as $turn => $data) {
                        if (isset($investStocks[$companyId][$turn])) {
                            $invest = $investStocks[$companyId][$turn];
                            $amounts[$invest['currency']] += $data['stock']/$invest['stock']*$invest['amount'];
                        }
                    }
                }
                ksort($amounts);
                $output = '';
                foreach($amounts as $currency => $amount) {
                    $amount = number_format($amount, 2);
                    $output .= "$currency $amount <br />";
                }
                return $output;
            }],
            ['label'=>'源码持股价值','field'=>function($model)use(&$deals, &$selectDate, &$exitStocks, &$investStocks){
                $dataList = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData('close_date')) {
                        $companyId = $deal->getData('company_id');
                        if (!isset($dataList[$companyId]) || ($deal->getData('close_date') > $dataList[$companyId]['close_date'] && $deal->getData('close_date') <= strtotime($selectDate['end']) && $deal->getData('close_date') >= strtotime($selectDate['start']))) {
                            if ($deal->getData('stocknum_all') && $deal->getData('post_money')) {
                                $dataList[$deal->getData('company_id')] = [
                                    'close_date' => $deal->getData('close_date'),
                                    'stock_price' => $deal->getData('post_money')/$deal->getData('stocknum_all'),
                                    'currency' => $deal->getData('value_currency'),
                                ]; 
                            }
                        }
                    }
                }
                $amounts = [];
                foreach($investStocks as $companyId => $turnData) {
                    foreach($turnData as $turn => $data) {
                        $exit = ['stock' => 0];
                        if (isset($exitStocks[$companyId][$turn])) {
                            $exit = $exitStocks[$companyId][$turn];
                        }
                        $amounts[$dataList[$companyId]['currency']] += ($data['stock'] - $exit['stock']) * $dataList[$companyId]['stock_price'];
                    }
                }
                ksort($amounts);
                $output = '';
                foreach($amounts as $currency => $amount) {
                    $amount = number_format($amount, 2);
                    $output .= "$currency $amount <br />";
                }
                return $output;
            }],
            ['label'=>'源码未清偿CB总额','field'=>function($model)use(&$deals, &$timeField, &$selectDate){
                $amounts = [];
                foreach($deals as $i => $deal) {
                    if ($deal->getData($timeField) >= strtotime($selectDate['start'])
                        && $deal->getData($timeField) <= strtotime($selectDate['end'])
                        && stripos($deal->getData('deal_type'),'源码独立CB') !== false
                        && strpos($deal->getData('loan_process'), '待处理') !== false) {
                        $amounts[$deal->getData('loan_currency')] += $deal->getData('loan_amount');
                    }
                }
                ksort($amounts);
                $output = '';
                foreach($amounts as $currency => $amount) {
                    $amount = number_format($amount, 2);
                    $output .= "$currency $amount <br />";
                }
                return $output ? $output : 0;
            }],
        );
    }

    public function index() {
        $this->_initIndex();
        $this->display("admin/base/index.html");
    }

    public function exportToCsvAction(){
        $this->_initIndex();
        $exeInfo = WinRequest::getModel('executeInfo');
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-Disposition:filename=".$exeInfo['controllerName']."_".date('YmdHis').".csv");

        $row=[];
        foreach ($this->list_display as $list_item){
            if(is_string($list_item)){
                $row[]=$list_item;
            }elseif(isset($list_item['label'])){
                $row[]=$list_item['label'];
            }else{
                $row=strval($list_item);
            }
        }
        self::printCsvRow($row);

        foreach ($this->model as $i => $modelData) {
            $row=[];
            foreach ($this->list_display as $list_item){
                if(is_array($list_item)&&isset($list_item['label'])){
                    $list_item=$list_item['field'];
                }
                if(is_string($list_item)){
                    $val=$modelData->getData($list_item);
                } elseif(is_callable($list_item)){
                    $val=call_user_func($list_item,$modelData,$this,$csv_data);
                }else{
                    $val=strval($list_item);
                }
                $row[]=trim(strip_tags($val));
            }
            self::printCsvRow($row);
        }

        exit;
    }
}


