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
                    foreach($project as $i => $dataItem) {
                        if (!$dataItem->getData('close_date') 
                            && $dataItem->getData('count_captable') == '计入') {
                            $project[$i]->mCloseDate = Model_Project::DEFAULT_CLOSE_DATE;
                        }
                    }
                    return $model->getData('id');
                }],
                ['name'=>'name','label'=>'目标企业','type'=>'text','default'=>null,'required'=>true,'help'=>'填入企业融资平台准确全称','validator'=>new Form_UniqueValidator(new Model_Company, 'name')],
                ['name'=>'code','label'=>'存档编号','type'=>'text','default'=>null,'required'=>false,'validator'=>new Form_UniqueValidator(new Model_Company, 'name')],
                ['name'=>'short','label'=>'项目简称','type'=>'text','default'=>null,'required'=>true,'help'=>'填入项目唯一简称，后续变动可此处修改。','validator'=>new Form_UniqueValidator(new Model_Company, 'short')],
                ['name'=>'hold_status','label'=>'源码持有状态','type'=>'choice','choices'=>Model_Company::getHoldStatusChoices(),'default'=>'正常','required'=>true,],
                ['name'=>'project_type','label'=>'项目类别','type'=>'choice','choices'=>Model_Company::getProjectTypeChoices(),'required'=>true,],
                ['name'=>'management','label'=>'是否在管','type'=>'choice','choices'=>Model_Company::getManagementChoices(),'required'=>true,],
                ['name'=>'_deal_num','label'=>'交易记录数','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    return "<div class=data_item><a href='/admin/project?__filter=".urlencode("short|company_id=$model->mShort")."'> ".count($project)." </a><!--a class=item_op href='/admin/project?action=read&company_id=$model->mId'> +新增 </a--></div>";
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
                        if ($dataItem->getData('stocknum_all')) {
                            return number_format($dataItem->getData('stocknum_all'));
                        }
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
                ['name'=>'_first_close_date','label'=>'源码首次投资时间','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '源码投') !== false) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    ksort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            if( $dataItem->getData('close_date') == Model_Project::DEFAULT_CLOSE_DATE) {
                                return '暂未交割';
                            }
                            return date('Ymd', $dataItem->getData('close_date'));
                        }
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
                        if ($dataItem->getData('close_date') == Model_Project::DEFAULT_CLOSE_DATE) {
                            return '暂未交割';
                        } else {
                            return date('Ymd', $dataItem->getData('close_date'));
                        }
                    }
                }],
                ['name'=>'_first_post_moeny','label'=>'首次投时估值','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '源码投') !== false) {
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
                        if (!$dataItem->getData('post_money')) continue;
                        return $dataItem->getData('value_currency'). ' ' . number_format($dataItem->getData('post_money'), 2);
                    }
                }],
                ['name'=>'_value_increase','label'=>'企业估值倍数(vs初投)','type'=>'rawText','default'=>null,'required'=>false,'help'=>'倍数<1 downround；=1 持平；>1 上涨','field'=>function($model)use(&$project) {
                    // TODO:以源码首次融资为分母 
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    $firstDeal = null;
                    foreach($dataList as $i => $dataItem) {
                        if (strpos($dataItem->getData('deal_type'), '源码投') !== false) {
                            $firstDeal = $dataItem;
                        }
                    }
                    $dataList = array_values($dataList);
                    $num = count($dataList);
                    if ($num && $dataList[$num - 1]->getData('post_money') && $firstDeal) {
                        return sprintf('%.2f', ($dataList[0]->getData('post_money') / $dataList[0]->getData('stocknum_all') / ($firstDeal->getData('post_money') / $firstDeal->getData('stocknum_all')) - 0));
                    }
                }],
                ['name'=>'_first_invest_turn','label'=>'首次投时轮次归类','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '源码投') !== false) {
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
                ['name'=>'_latest_turn_sub','label'=>'最新所处轮次','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('turn_sub');
                    }
                }],
                ['name'=>'_financing_no','label'=>'源码投后融资轮次','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    // TODO: 轮次要去重
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date') 
                            && strpos($dataItem->getData('deal_type'), '企业融资') !== false) {
                            $dataList[$dataItem->getData('turn_sub')] = 1;
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
                            && strpos($dataItem->getData('deal_type'), '源码投') !== false) {
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
                        if ($dataItem->getData('close_date')) { 
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    ksort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('company_period');
                    }
                }],
                ['name'=>'_financing_amount_all','label'=>'企业融资总金额','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            if (strpos($dataItem->getData('deal_type'), '企业融资') !== false
                                && $dataItem->getData('financing_amount')) { 
                                $turn = $dataItem->getData('turn_sub');
                                $currency = $dataItem->getData('value_currency');
                                if (isset($dataList[$turn][$currency])) {
                                    continue;
                                }
                                $dataList[$turn][$currency] += $dataItem->getData('financing_amount');
                            } elseif (strpos($dataItem->getData('deal_type'), '独立CB') !== false
                                && $dataItem->getData('loan_amount')) { 
                                $turn = $dataItem->getData('turn_sub');
                                $currency = $dataItem->getData('loan_currency');
                                $dataList[$turn][$currency] += $dataItem->getData('loan_amount');
                            }
                        }
                    }
                    $amounts = [];
                    foreach($dataList as $turn => $turnAmount) {
                        foreach($turnAmount as $currency => $amount) {
                            $amounts[$currency] += $amount;
                        }
                    }
                    $output = '';
                    foreach($amounts as $currency => $amount) {
                        $output .= "$currency ".number_format($amount,2)."<br />";
                    }
                    return $output;
                }],
                ['name'=>'field-index-enterexit','label'=>'源码投退信息','type'=>'seperator'],
                ['name'=>'_captable','label'=>'投退CapTable','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    return '<a href="/admin/project/captable?company_id='.$model->getData('id').'" target="_blank">查看</a>';
                }],
                ['name'=>'_entity_list','label'=>'源码投资主体','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project,&$stockNums) {
                    $dataList = [];
                    $stockNums = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                            if ($dataItem->getData('deal_type') == '源码退出') {
                                $stockNums[$dataItem->getData('exit_entity_id')] -= $dataItem->getData('exit_stock_number');
                            } else {
                                $stockNums[$dataItem->getData('entity_id')] += $dataItem->getData('stocknum_get');
                            }
                        }
                    }
                    $entityIds = array_keys($stockNums);
                    if ($entityIds) {
                        $entity = new Model_Entity;
                        $entity->addWhere('id', $entityIds, 'IN');
                        $entitys = $entity->findMap('id');
                        $output = '';
                        foreach($stockNums as $id => $num) {
                            if (isset($entitys[$id])) {
                                $output .= $entitys[$id]->mName."<br />";
                                if ($num <= 0) {
                                    $output .= '[已退出]';
                                }
                            }
                        }
                        return $output;
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
                            } elseif (strpos($dataItem->getData('deal_type'),'源码投') !== false) {
                                $stockNum += $dataItem->getData('stocknum_get');
                            }
                        }
                    }
                    return number_format($stockNum);
                }],
                ['name'=>'_latest_shareholding_ratio_sum','label'=>'最新各主体合计股比','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project) {
                    // TODO 可以化简
                    $dataList = [];
                    $stockNum = 0;
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                            if ($dataItem->getData('deal_type') == '源码退出') {
                                $stockNum -= $dataItem->getData('exit_stock_number');
                            } elseif (strpos($dataItem->getData('deal_type'),'源码投') !== false) {
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
                            && strpos($dataItem->getData('deal_type'), '源码投') !== false) {
                            $dataList[$dataItem->getData('entity_id')] = $dataItem;
                        }
                    }
                    return count($dataList) > 1 ? '是' : '否';
                }],
                ['name'=>'_multi_entity_hold','label'=>'当前是否多主体持股','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project,&$stockNums) {
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
                            && strpos($dataItem->getData('deal_type'), '源码投') !== false) {
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
                ['name'=>'_entity_odi','label'=>'源码主体ODI','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project){
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    krsort($dataList);
                    foreach($dataList as $i => $dataItem) {
                        if ($dataItem->getData('entity_odi')) {
                            return $dataItem->getData('entity_odi');
                        }
                    }
                }],
                ['name'=>'field-index-govern','label'=>'企业治理','type'=>'seperator'],
                ['name'=>'main_founders','label'=>'最主要创始人','type'=>'text','default'=>null,'required'=>false,],
                ['name'=>'_director_turn','label'=>'董事委派轮次','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project){
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        $dataList[$dataItem->getData('id')] = $dataItem;
                    }
                    ksort($dataList);
                    foreach($dataList as $i => $dataItem) {
                        if ($dataItem->getData('our_board') == '有') {
                            return $dataItem->getData('turn_sub');
                        }
                    }
                }],
                ['name'=>'_has_director','label'=>'是否委派过董事','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project){
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        $dataList[$dataItem->getData('id')] = $dataItem;
                    }
                    ksort($dataList);
                    foreach($dataList as $i => $dataItem) {
                        if ($dataItem->getData('our_board') == '有') {
                            return '是';
                        }
                    }
                    return '否';
                }],
                ['name'=>'_director_name','label'=>'最新源码董事姓名','type'=>'rawText','default'=>'无董事席位','required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        $dataList[$dataItem->getData('id')] = $dataItem;
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('our_board_person');
                    }
                }],
                ['name'=>'_director_status','label'=>'最新源码董事状态','type'=>'rawText','required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        $dataList[$dataItem->getData('id')] = $dataItem;
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('our_board_status');
                    }
                }],
                ['name'=>'_observer','label'=>'最新源码观察员','type'=>'rawText','required'=>false,'field'=>function($model)use(&$project){
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        $dataList[$dataItem->getData('id')] = $dataItem;
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('observer');
                    }
                }],
                ['name'=>'_supervisor','label'=>'最新源码监事','type'=>'rawText','required'=>false,'field'=>function($model)use(&$project){
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        $dataList[$dataItem->getData('id')] = $dataItem;
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('supervisor');
                    }
                }],
                ['name'=>'_holder_veto','label'=>'最新股东会Veto','type'=>'rawText','required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        $dataList[$dataItem->getData('id')] = $dataItem;
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('holder_veto');
                    }
                }],
                ['name'=>'_board_veto','label'=>'最新董事会Veto','type'=>'rawText','required'=>false,'field'=>function($model)use(&$project) {
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        $dataList[$dataItem->getData('id')] = $dataItem;
                    }
                    krsort($dataList);
                    foreach($dataList as $date => $dataItem) {
                        return $dataItem->getData('board_veto');
                    }
                }],
                ['name'=>'field-index-return','label'=>'源码投资回报','type'=>'seperator'],
                ['name'=>'_invest_amount','label'=>'历史总投资金额','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project,&$investExitAmounts,&$investExitStocks,&$cbAmounts){
                    $dataList = [];
                    $investExitAmounts = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            if (strpos($dataItem->getData('deal_type'), '源码退出') !== false) {
                                $investExitAmounts['exit'][$dataItem->getData('exit_turn')][$dataItem->getData('exit_currency')] += $dataItem->getData('exit_amount');
                                $investExitStocks['exit'][$dataItem->getData('exit_turn')][$dataItem->getData('exit_currency')] += $dataItem->getData('exit_stock_number');
                            } elseif (strpos($dataItem->getData('deal_type'), '源码投') !== false) {
                                $investExitAmounts['invest'][$dataItem->getData('invest_turn')][$dataItem->getData('invest_currency')] += $dataItem->getData('our_amount');
                                $investExitStocks['invest'][$dataItem->getData('invest_turn')][$dataItem->getData('invest_currency')] += $dataItem->getData('stocknum_get');
                            } elseif (stripos($dataItem->getData('deal_type'), '源码独立CB') !== false
                                && $dataItem->getData('loan_process') == '待处理') {
                                $cbAmounts[$dataItem->getData('loan_currency')] += $dataItem->getData('loan_amount');
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
                ['name'=>'_exit_amount','label'=>'已退出合同金额','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project,&$investExitAmounts,&$exitAmount){
                    $amounts = [];
                    foreach($investExitAmounts['exit'] as $turn => $turnAmounts) {
                        foreach($turnAmounts as $currency => $amount) {
                            $amounts[$currency] += $amount;
                        }
                    }
                    $output = '';
                    $exitAmount = $amounts;
                    foreach($amounts as $currency => $amount) {
                        $output .= "$currency " . number_format($amount, 2) . '<br />';
                    }
                    return $output;
                }],
                ['name'=>'_exit_amount_cost','label'=>'已退出金额对应成本','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project,&$investExitAmounts,&$investExitStocks,&$exitAmountCost){
                    // TODO: 有bug
                    $amounts = $investExitAmounts;
                    $stocks = $investExitStocks;
                    $costs = [];
                    foreach($stocks['exit'] as $turn => $item) {
                        foreach($item as $currency => $stock) {
                            if (empty($currency)) continue;
                            $costs[$currency] += $stock/$stocks['invest'][$turn][$currency]*$amounts['invest'][$turn][$currency];
                        }
                    }

                    $output = '';
                    $exitAmountCost = $costs;
                    foreach($costs as $currency => $cost) {
                        $output .= "$currency " . number_format($cost, 2) . '<br />';
                    }
                    return $output;
                }],
                ['name'=>'_exit_return_rate','label'=>'已退出部分回报率','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$exitAmountCost,&$exitAmount){
                    foreach($exitAmount as $currency => $amount) {
                        if ($exitAmountCost[$currency]) {
                            $output .= "$currency " . sprintf('%.2f%%', ($amount/$exitAmountCost[$currency] - 1)*100) . '<br />';
                        }
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
                            } elseif(strpos($dataItem->getData('deal_type'),'源码投') !== false) {
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
                ['name'=>'_hold_return_rate','label'=>'在管投资回报倍数','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$investExitStocks,&$investExitAmounts,&$holdValue){
                    // TODO: 多币种情况下不计算,仅显示
                    $amounts = $investExitAmounts;
                    $stocks = $investExitStocks;
                    $holds = [];
                    foreach($stocks['invest'] as $turn => $item) {
                        foreach($item as $currency => $stock) {
                            $holds[$currency] += ($stock - $stocks['exit'][$turn][$currency])/$stock*$amounts['invest'][$turn][$currency];
                        }
                    }
                    $output = '';
                    foreach($holds as $currency => $amount) {
                        if (count($holds) == 1) {
                            if (isset($holdValue[$currency])) {
                                $output .= sprintf('%.2f', ($holdValue[$currency]/$amount-0)) . "\n";
                            } else {
                                $output .= $currency."投资 ".number_format($amount)."（与账面币种不同，需手动计算回报）\n";
                            }
                        } else {
                            if (isset($holdValue[$currency])) {
                                $output .= $currency."投资 ".sprintf('%.2f', ($holdValue[$currency]/$amount-0)) . "\n";
                            } else {
                                $output .= $currency."投资 ".number_format($amount)."（与账面币种不同，需手动计算回报）\n";
                            }
                        }
                    }
                    return $output;
                }],
                ['name'=>'_cb_amount','label'=>'未偿还CB金额','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$cbAmounts){
                    $output = '';
                    foreach($cbAmounts as $currency => $amount) {
                        $output .= "$currency ". number_format($amount, 2)  . '<br />';
                    }
                    return $output;
                }],
                ['name'=>'field-index-staff','label'=>'项目组成员','type'=>'seperator'],
                ['name'=>'partner','label'=>'主管合伙人','type'=>'choosemodel','model'=>'Model_Member','default'=>null,'required'=>false,'field'=>function($model){
                    $members = Model_Member::listAll();
                    if (isset($members[$model->getData('partner')])) {
                        return $members[$model->getData('partner')]->getData('name');
                    }
                    return '<i>'.$model->getData('partner').'</i>';
                }],
                ['name'=>'manager','label'=>'项目负责人','type'=>'choosemodel','model'=>'Model_Member','default'=>null,'required'=>false,'field'=>function($model){
                    $members = Model_Member::listAll();
                    if (isset($members[$model->getData('manager')])) {
                        return $members[$model->getData('manager')]->getData('name');
                    }
                    return '<i>'.$model->getData('manager').'</i>';
                }],
                ['name'=>'legal_person','label'=>'法务负责人','type'=>'choosemodel','model'=>'Model_Member','default'=>null,'required'=>false,'field'=>function($model){
                    $members = Model_Member::listAll();
                    if (isset($members[$model->getData('legal_person')])) {
                        return $members[$model->getData('legal_person')]->getData('name');
                    }
                    return '<i>'.$model->getData('legal_person').'</i>';
                }],
                ['name'=>'finance_person','label'=>'财务负责人','type'=>'choosemodel','model'=>'Model_Member','default'=>null,'required'=>false,'field'=>function($model){
                    $members = Model_Member::listAll();
                    if (isset($members[$model->getData('finance_person')])) {
                        return $members[$model->getData('finance_person')]->getData('name');
                    }
                    return '<i>'.$model->getData('finance_person').'</i>';
                }],
                ['name'=>'_first_partner','label'=>'初始主管合伙人','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project){
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    ksort($dataList);
                    foreach($dataList as $i => $dataItem) {
                        if ($dataItem->getData('partner')) {
                            $members = Model_Member::listAll();
                            if (isset($members[$dataItem->getData('partner')])) {
                                return $members[$dataItem->getData('partner')]->getData('name');
                            }
                            return '<i>'.$dataItem->getData('partner').'</i>';
                        }
                    }
                }],
                ['name'=>'_first_manager','label'=>'初始项目负责人','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model)use(&$project){
                    $dataList = [];
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('close_date')) {
                            $dataList[$dataItem->getData('close_date')] = $dataItem;
                        }
                    }
                    ksort($dataList);
                    foreach($dataList as $i => $dataItem) {
                        if ($dataItem->getData('manager')) {
                            $members = Model_Member::listAll();
                            if (isset($members[$dataItem->getData('manager')])) {
                                return $members[$dataItem->getData('manager')]->getData('name');
                            }
                            return '<i>'.$dataItem->getData('manager').'</i>';
                        }
                    }
                }],
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
                ['name'=>'filling_keeper','label'=>'文件Filing保管人','type'=>'choosemodel','model'=>'Model_Member','default'=>null,'required'=>false,'field'=>function($model){
                    $members = Model_Member::listAll();
                    if (isset($members[$model->getData('filling_keeper')])) {
                        return $members[$model->getData('filling_keeper')]->getData('name');
                    }
                    return '<i>'.$model->getData('filling_keeper').'</i>';
                }],
                ['name'=>'field-index-memo','label'=>'备注及未决事项','type'=>'seperator'],
                ['name'=>'_pending_detail','label'=>'未决事项说明','type'=>'rawText','required'=>false,'field'=>function($model)use(&$project){
                    $dataList = [];
                    $output = '';
                    foreach($project as $i => $dataItem) {
                        if ($dataItem->getData('pending') == '有') {
                            $output .= $dataItem->getData('pending_detail') . "\n";
                        }
                    }
                    return $output;
                }],
                ['name'=>'_memo','label'=>'备注','type'=>'rawText','required'=>false,'field'=>function($model){
                    $memo = new Model_CompanyMemo;
                    $memo->addWhere('company_id', $model->getData('id'));
                    $memo->orderBy('update_time', 'desc');
                    $memo->select();
                    $output = '';
                    if ($memo->getData('id')) {
                        $output .= date('Ymd H:i:s', $memo->getData('update_time'))." ".$memo->getData('operator')." ".$memo->getData('title')." ".$memo->getData('content').' <a target="_blank" href="/admin/companyMemo?__filter='.urlencode('short|company_id='.$model->getData('short')).'">列表 </a>';
                    }
                    return $output .= '<a target="_blank" href="/admin/companyMemo?action=read&company_id='.$model->getData('id').'">添加+</a>';
                }],
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
