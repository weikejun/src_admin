<?php

class Form_Company extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'企业ID','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project){
                    $project = new Model_Project;
                    $project->addWhere('company_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project = $project->find();
                    return $model->getData('id');
                }],
                ['name'=>'name','label'=>'目标企业','type'=>'text','default'=>null,'required'=>true,'help'=>'填入企业融资平台准确全称'],
                ['name'=>'short','label'=>'项目简称','type'=>'text','default'=>null,'required'=>true,'help'=>'填入项目唯一简称，后续变动可此处修改。'],
                ['name'=>'hold_status','label'=>'源码持有状态','type'=>'choice','choices'=>Model_Company::getHoldStatusChoices(),'default'=>'正常','required'=>true,],
                ['name'=>'project_type','label'=>'项目类别','type'=>'choice','choices'=>Model_Company::getProjectTypeChoices(),'required'=>true,],
                ['name'=>'management','label'=>'是否在管','type'=>'choice','choices'=>Model_Company::getManagementChoices(),'required'=>true,],
                ['name'=>'_deal_num','label'=>'交易记录数','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    return "<div class=data_item><a href='/admin/project?__filter=".urlencode("name|company_id=$model->mName")."'> ".count($project)." </a><!--a class=item_op href='/admin/project?action=read&company_id=$model->mId'> +新增 </a--></div>";
                }],
                ['name'=>'_stocknum_all','label'=>'最新企业总股数','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return number_format($dataItem->getData('stocknum_all'));
                    }
                }],
                ['name'=>'_company_character','label'=>'当前目标企业性质','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('company_character');
                    }
                }],
                ['name'=>'bussiness','label'=>'所属行业','type'=>'selectInput','choices'=>Model_Company::getBussinessChoices(),'required'=>true,],
                ['name'=>'bussiness_change','label'=>'主营行业变化','type'=>'selectInput','choices'=>[['未变化','未变化']],'required'=>false],
                ['name'=>'region','label'=>'主营地','type'=>'selectInput','choices'=>Model_Company::getRegionChoices(),'required'=>true],
                ['name'=>'register_region','label'=>'注册地','type'=>'selectInput','choices'=>Model_Company::getRegionChoices(),'required'=>true,'help'=>'精确到国家（国外）/省（国内）'],
                ['name'=>'field-index-financing','label'=>'融资信息','type'=>'seperator'],
                ['name'=>'_first_close_date','label'=>'首次投资时间','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '企业融资') !== false) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    ksort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return date('Ymd', $dataItem->getData('close_date'));
                    }
                }],
                ['name'=>'_latest_close_date','label'=>'最新一轮融资时间','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '企业融资') !== false) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return date('Ymd', $dataItem->getData('close_date'));
                    }
                }],
                ['name'=>'_first_post_moeny','label'=>'首次投时估值','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '企业融资') !== false) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    ksort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        if (!$dataItem->getData('post_money')) return;
                        return $dataItem->getData('value_currency'). ' ' . number_format($dataItem->getData('post_money'), 2);
                    }
                }],
                ['name'=>'_latest_post_moeny','label'=>'最新估值','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        if (!$dataItem->getData('post_money')) return;
                        return $dataItem->getData('value_currency'). ' ' . number_format($dataItem->getData('post_money'), 2);
                    }
                }],
                ['name'=>'_value_increase','label'=>'企业估值涨幅','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    $dataList = array_values($dataList);
                    $num = count($dataList);
                    if ($num && $dataList[$num - 1]->getData('post_money')) {
                        return sprintf('%.2f%%', ($dataList[0]->getData('post_money') / $dataList[$num - 1]->getData('post_money') - 1) * 100);
                    }
                }],
                ['name'=>'_first_invest_turn','label'=>'首次投时轮次归类','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '企业融资') !== false) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    ksort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('turn');
                    }
                }],
                ['name'=>'_latest_invest_turn','label'=>'最新轮次归类','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('turn');
                    }
                }],
                ['name'=>'_financing_no','label'=>'源码投后融资轮次','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '企业融资') !== false) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    if ($dataList) {
                        return count($dataList) - 1;
                    }
                }],
                ['name'=>'_first_company_period','label'=>'首次投时企业阶段','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '企业融资') !== false) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    ksort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('company_period');
                    }
                }],
                ['name'=>'_latest_company_period','label'=>'最新企业阶段','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '企业融资') !== false) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    ksort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('company_period');
                    }
                }],
                ['name'=>'field-index-enterexit','label'=>'源码投退信息','type'=>'seperator'],
                ['name'=>'_captable','label'=>'投退CapTable','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    return '<a href="/admin/project/captable?company_id='.$model->getData('id').'" target="_blank">查看</a>';
                }],
                ['name'=>'_first_close_date','label'=>'首次投资交割日期','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '企业融资') !== false) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    ksort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return date('Ymd', $dataItem->getData('close_date'));
                    }
                }],
                ['name'=>'_have_exit','label'=>'是否发生过退出','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '源码退出') !== false) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                            break;
                        }
                    }
                    return $dataList ? '是' : '否';
                }],
                ['name'=>'_latest_shareholding_sum','label'=>'最新各主体合计持股数','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    $stockNum = 0;
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                            if ($dataItem->getData('deal_type') == '源码退出') {
                                $stockNum -= $dataItem->getData('exit_stock_number');
                            } else {
                                $stockNum += $dataItem->getData('stocknum_get');
                            }
                        }
                    }
                    return number_format($stockNum);
                }],
                ['name'=>'_latest_shareholding_ratio_sum','label'=>'最新各主体合计股比','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    $stockNum = 0;
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                            if ($dataItem->getData('deal_type') == '源码退出') {
                                $stockNum -= $dataItem->getData('exit_stock_number');
                            } else {
                                $stockNum += $dataItem->getData('stocknum_get');
                            }
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        if ($dataItem->getData('stocknum_all')) {
                            return sprintf("%.2f%%", $stockNum / $dataItem->getData('stocknum_all') * 100);
                        }
                    }
                }],
                ['name'=>'_multi_entity_invest','label'=>'是否多主体投过','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '企业融资（源码投）') !== false) {
                            $dataList[$dataItem->getData('entity_id')] = $dataItem;
                        }
                    }
                    return count($dataList) > 1 ? '是' : '否';
                }],
                ['name'=>'_multi_entity_hold','label'=>'当前是否多主体持股','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    $stockNums = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                            if ($dataItem->getData('deal_type') == '源码退出') {
                                $stockNums[$dataItem->getData('entity_id')] -= $dataItem->getData('exit_stock_number');
                            } else {
                                $stockNums[$dataItem->getData('entity_id')] += $dataItem->getData('stocknum_get');
                            }
                        }
                    }
                    $count = 0;
                    foreach($stockNums as $i => $stockNum) {
                        if ($stockNum > 0) {
                            $count++;
                        }
                    }
                    return $count > 1 ? '是' : '否';
                }],
                ['name'=>'_multi_currency_entity_invest','label'=>'美元+人民币主体投过','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '企业融资（源码投）') !== false) {
                            $dataList[$dataItem->getData('entity_id')] = $dataItem;
                        }
                    }
                    $entityIds = array_keys($dataList);
                    $entity = new Model_Entity;
                    $entity->addWhere('id', $entityIds, 'IN', DBTable::ESCAPE);
                    $entity->groupBy('currency');
                    $entity = $entity->find();
                    return count($entity) > 1 ? '是' : '否';
                }],
                ['name'=>'_multi_currency_entity_hold','label'=>'当前美元+人民币主体投','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    $stockNums = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                            if ($dataItem->getData('deal_type') == '源码退出') {
                                $stockNums[$dataItem->getData('entity_id')] -= $dataItem->getData('exit_stock_number');
                            } else {
                                $stockNums[$dataItem->getData('entity_id')] += $dataItem->getData('stocknum_get');
                            }
                        }
                    }
                    $entityIds = [];
                    foreach($stockNums as $entityId => $stockNum) {
                        if ($stockNum > 0) {
                            $entityIds[] = $entityId;
                        }
                    }
                    $entity = new Model_Entity;
                    $entity->addWhere('id', $entityIds, 'IN', DBTable::ESCAPE);
                    $entity->groupBy('currency');
                    $entity = $entity->find();
                    return count($entity) > 1 ? '是' : '否';
                }],
                ['name'=>'_entity_odi','label'=>'源码主体ODI','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    return '算法不明确，待讨论';
                }],
                ['name'=>'field-index-govern','label'=>'企业治理','type'=>'seperator'],
                ['name'=>'_director_turn','label'=>'董事委派轮次','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model){
                    return '算法不明确，待讨论';
                }],
                ['name'=>'_director_name','label'=>'最新源码董事姓名','type'=>'rawText','default'=>'无董事席位','required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('our_board_person');
                    }
                }],
                ['name'=>'_director_status','label'=>'最新源码董事状态','type'=>'rawText','required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('our_board_status');
                    }
                }],
                ['name'=>'_observer','label'=>'最新源码观察员','type'=>'rawText','required'=>false,'field'=>function($model)use(&$project){
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('observer');
                    }
                }],
                ['name'=>'_holder_veto','label'=>'最新股东会Veto','type'=>'rawText','required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('holder_veto');
                    }
                }],
                ['name'=>'_board_veto','label'=>'最新董事会Veto','type'=>'rawText','required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('board_veto');
                    }
                }],
                ['name'=>'field-index-return','label'=>'源码投资回报','type'=>'seperator'],
                ['name'=>'_invest_amount','label'=>'历史总投资金额','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project,&$investExitAmounts){
                    $dataList = [];
                    $investExitAmounts = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            if (strpos($dataItem->getData('deal_type'), '源码退出') !== false) {
                                $investExitAmounts['exit'][$dataItem->getData('exit_turn')][$dataItem->getData('exit_currency')] += $dataItem->getData('exit_amount');
                            } elseif (strpos($dataItem->getData('deal_type'), '源码投') !== false) {
                                $investExitAmounts['invest'][$dataItem->getData('turn_sub')][$dataItem->getData('invest_currency')] += $dataItem->getData('our_amount');
                            }
                        }
                    }
                    $amounts = [];
                    foreach($investExitAmounts['invest'] as $turn => $turnAmount) {
                        foreach($turnAmount as $currency => $amount) {
                            $amounts[$currency] += $amount;
                        }
                    }
                    $output = '';
                    foreach($amounts as $currency => $amount) {
                        $output .= "$currency " . number_format($amount, 2) . '<br />';
                    }
                    return $output;
                }],
                ['name'=>'_hold_value','label'=>'当前持股账面价值','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project,&$holdValue) {
                    $dataList = [];
                    $stockNum = 0;
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                            if ($dataItem->getData('deal_type') == '源码退出') {
                                $stockNum -= $dataItem->getData('exit_stock_number');
                            } else {
                                $stockNum += $dataItem->getData('stocknum_get');
                            }
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        if ($dataItem->getData('stocknum_all')&& $stockNum > 0) {
                            $currency = $dataItem->getData('value_currency');
                            $holdValue[$currency] = $stockNum/$dataItem->getData('stocknum_all')*$dataItem->getData('post_money');
                            return "$currency " . number_format($holdValue[$currency], 2);
                        }
                    }
                }],
                ['name'=>'_hold_return_rate','label'=>'在管投资回报倍数','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project,&$investExitAmounts,&$holdValue){
                    $amounts = [];
                    foreach($investExitAmounts['invest'] as $turn => $turnAmounts) {
                        foreach($turnAmounts as $currency => $amount) {
                            $amounts[$currency] += $amount;
                        }
                    }
                    foreach($investExitAmounts['exit'] as $turn => $turnAmounts) {
                        foreach($turnAmounts as $currency => $amount) {
                            $amounts[$currency] -= $amount;
                        }
                    }
                    if (count($amounts) == count($holdValue)) {
                        $output = '';
                        foreach($holdValue as $currency => $amount) {
                            $output .= "$currency ".sprintf('%.2f%%', ($amount/$amounts[$currency]-1)*100) . '<br />';
                        }
                        return $output;
                    }
                }],
                ['name'=>'_exit_amount','label'=>'已退出合同金额','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project,&$investExitAmounts){
                    $amounts = [];
                    foreach($investExitAmounts['exit'] as $turn => $turnAmounts) {
                        foreach($turnAmounts as $currency => $amount) {
                            $amounts[$currency] += $amount;
                        }
                    }
                    $output = '';
                    foreach($amounts as $currency => $amount) {
                        $output .= "$currency " . number_format($amount, 2) . '<br />';
                    }
                    return $output;
                }],
                ['name'=>'_exit_amount_cost','label'=>'已退出金额对应成本','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project,&$investExitAmounts){
                    $amounts = $investExitAmounts;
                    $costs = [];
                    foreach($amounts['exit'] as $turn => $item) {
                        foreach($item as $currency => $amount) {
                            $costs[$currency] += $amounts['invest'][$turn][$currency];
                        }
                    }
                    $_exit_amount_cost = $amounts;

                    $output = '';
                    foreach($costs as $currency => $cost) {
                        $output .= "$currency " . number_format($cost, 2) . '<br />';
                    }
                    return $output;
                }],
                ['name'=>'_exit_return_rate','label'=>'已退出部分回报率','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$investExitAmounts){
                    $amounts = $investExitAmounts;
                    $costs = [
                        'exit' => [],
                        'invest' => []
                    ];
                    foreach($amounts['exit'] as $turn => $item) {
                        foreach($item as $currency => $amount) {
                            $costs['invest'][$currency] += $amounts['invest'][$turn][$currency];
                            $costs['exit'][$currency] += $amount;
                        }
                    }
                    $output = '';
                    foreach($costs['exit'] as $currency => $amount) {
                        if ($amount) {
                            $output .= "$currency " . sprintf('%.2f%%', $amount/$costs['invest'][$currency] - 1) . '<br />';
                        }
                    }
                    return $output;
                }],
                ['name'=>'field-index-staff','label'=>'当前项目组成员','type'=>'seperator'],
                ['name'=>'partner','label'=>'主管合伙人','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'manager','label'=>'项目负责人','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'legal_person','label'=>'法务负责人','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'finance_person','label'=>'财务负责人','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'field-index-filing','label'=>'工商及Filing','type'=>'seperator'],
                ['name'=>'_aic_status','label'=>'人民币项目工商','type'=>'rawText','required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                            if ($dataItem->getData('aic_registration') == '待办理') {
                                return '未完成';
                            }
                        }
                    }
                    return '已完成';
                }],
                ['name'=>'_filing_status','label'=>'Filing是否完整','type'=>'rawText','required'=>false,'field'=>function($model)use(&$project){
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                            if ($dataItem->getData('final_captable') == '待存档'
                                || $dataItem->getData('final_word') == '待存档'
                                || $dataItem->getData('closing_pdf') == '待存档'
                                || $dataItem->getData('closing_original') == '待存档'
                                || $dataItem->getData('overseas_stockcert') == '待存档'
                            ) {
                                return '存档不完整';
                            }
                        }
                    }
                    return '存档完整';
                }],
                ['name'=>'filling_keeper','label'=>'文件Filing保管人','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'field-index-memo','label'=>'备注及未决事项','type'=>'seperator'],
                ['name'=>'_pending_detail','label'=>'未决事项说明','type'=>'rawText','required'=>false,'field'=>function($model)use(&$project){
                    $dataList = [];
                    $output = '';
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('pending') == '有') {
                            $output .= $dataItem->getData('pending_detail') . '<br />';
                        }
                    }
                    return $output;
                }],
                ['name'=>'memo','label'=>'备注','type'=>'textarea','required'=>false],
                ['name'=>'update_time','label'=>'更新时间','type'=>'datetime','default'=>time(),'required'=>false,'auto_update'=>true,'readonly'=>true,'field'=>function($model){
                    return date('Ymd H:i:s', $model->getData('update_time'));
                }],
            ];
        }
        return self::$fieldsMap;
    }

    public function __construct() {
        parent::__construct(self::getFieldsMap());
    }
}
