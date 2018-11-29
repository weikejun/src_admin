<?php

class Form_Entity extends Form {
    use Form_Traits;

    protected static $fieldsMap;

    public static function getFieldsMap() {
        if (!self::$fieldsMap) {
            self::$fieldsMap = [
                ['name'=>'id','label'=>'主体ID','type'=>'hidden','default'=>null,'required'=>false,],
                ['name'=>'name','label'=>'名称','type'=>'text','default'=>null,'required'=>true,'help'=>'填入准确全称','validator'=>new Form_UniqueValidator(new Model_Entity, 'name')],
                ['name'=>'description','label'=>'描述','type'=>'text','default'=>null,'required'=>false,'help'=>'示例“人民币早期一期主基金”，“美元专项基金SPV”'],
                ['name'=>'_captable','label'=>'认购情况汇总','type'=>'rawText','default'=>null,'required'=>false,'field'=>function($model) {
                    return '<a href="/admin/fundLp/captable?entity_id='.$model->getData('id').'" target="_blank">查看</a>';
                }],
                ['name'=>'_lp_direct_num','label'=>'LP直接人数','type'=>'rawText','field'=>function($model)use(&$lps,&$lpsActive) {
                    $lps = new Model_FundLp;
                    $lps->addWhere('status', 'valid');
                    $lps->addWhere('entity_id', $model->getData('id'));
                    $lps = $lps->find();
                    $lpsActive = [];
                    $ids = [];
                    $lpCount = [];
                    foreach($lps as $lp) {
                        $amounts = [];
                        $data = $lp->getData();
                        foreach(['subscribe' => 1,'share_transfer' => -1,'capital_reduce' => -1] as $fk => $fa) {
                            $amount = ($fa * $data[$fk.'_amount']);
                            $amounts[$data[$fk.'_currency']] += $amount;
                        }
                        foreach($amounts as $currency => $amount) {
                            if ($amount > 0) {
                                $ids[] = $data['id'];
                                $lpCount[$data['subscriber']] = 1;
                                $lpsActive[] = $lp;
                                break;
                            }
                        }
                    }
                    return '<a target="_blank" href="/admin/fundLp?__filter='.urlencode('id='.implode(',', $ids)).'">'.count($lpCount).'</a>';
                }],
                ['name'=>'_lp_through_num','label'=>'LP穿透人数','type'=>'rawText','field'=>function($model)use(&$lpsActive) {
                    $num = 0;
                    $lpUni = [];
                    foreach($lpsActive as $lp) {
                        if (isset($lpUni[$lp->getData('subscriber')])) {
                            continue;
                        }
                        $lpUni[$lp->getData('subscriber')] = 1;
                        $num += $lp->getData('through_num');
                    }
                    return '<a target="_blank" href="/admin/fundLp?__filter='.urlencode('id='.implode(',',$ids)).'">'.$num.'</a>';
                }],
                ['name'=>'_subscribe_amount','label'=>'基金认缴规模','type'=>'rawText','field'=>function($model)use(&$lps) {
                    $amounts = [];
                    foreach($lps as $lp) {
                        if ($lp->getData('subscribe_currency') && $lp->getData('subscribe_amount'))
                            $amounts[$lp->getData('subscribe_currency')] += $lp->getData('subscribe_amount');
                        if ($lp->getData('capital_reduce_currency') && $lp->getData('capital_reduce_amount'))
                            $amounts[$lp->getData('capital_reduce_currency')] -= $lp->getData('capital_reduce_amount');
                    }
                    $output = '';
                    foreach($amounts as $currency => $amount) {
                        $output .= "$currency ".number_format($amount)."\n";
                    }
                    return $output;
                }],
                ['name'=>'_paid_amount','label'=>'基金实缴规模','type'=>'rawText','field'=>function($model)use(&$lps) {
                    $amounts = [];
                    foreach($lps as $lp) {
                        if ($lp->getData('paid_currency') && $lp->getData('paid_amount'))
                            $amounts[$lp->getData('paid_currency')] += $lp->getData('paid_amount');
                    }
                    $output = '';
                    foreach($amounts as $currency => $amount) {
                        $output .= "$currency ".number_format($amount)."\n";
                    }
                    return $output;
                }],
                ['name'=>'register_country','label'=>'注册国/省','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'register_address','label'=>'注册地址','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'cate','label'=>'类型','type'=>'selectInput','choices'=>Model_Entity::getCateChoices(),'required'=>false],
                ['name'=>'tp','label'=>'类别','type'=>'selectInput','choices'=>Model_Entity::getTpChoices(),'required'=>false,'help'=>'可问财务同事'],
                ['name'=>'org_type','label'=>'组织形式','type'=>'selectInput','choices'=>Model_Entity::getOrgTypeChoices(),'required'=>false],
                ['name'=>'co_investment','label'=>'co-investment','type'=>'choice','choices'=>Model_Entity::getCoInvestmentChoices(),'default'=>'否','required'=>false,'help'=>'主基金都不是，非主基金的和财务同事确认。'],
                ['name'=>'currency','label'=>'资金货币','type'=>'choice','choices'=>Model_Project::getCurrencyChoices(),'default'=>'USD','required'=>false,],
                ['name'=>'fund_name','label'=>'基金简称','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'fund_code','label'=>'基金代码','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'association_cate','label'=>'协会分类','type'=>'selectInput','choices'=>Model_Entity::getAssociationCateChoices(),'default'=>null,'required'=>false],
                ['name'=>'fund_manager_entity','label'=>'基金管理人','type'=>'choosemodel','model'=>'Model_Entity','default'=>null,'required'=>false,'field'=>function($model) {
                    $en = new Model_Entity;
                    $en->addWhere('id', $model->getData('fund_manager_entity'));
                    $en->select();
                    return $en->mName;
                }],
                ['name'=>'dm_material','label'=>'推介资料','type'=>'choice','choices'=>Model_Entity::getDmMaterialChoices(),'default'=>null,'required'=>false],
                ['name'=>'rank_material','label'=>'产品评级资料','type'=>'choice','choices'=>Model_Entity::getRankMaterialChoices(),'default'=>null,'required'=>false],
                ['name'=>'init_delivery_date','label'=>'首次交割日','type'=>'date','default'=>null,'required'=>false,'field'=>function($model){
                    if ($model->getData('init_delivery_date'))
                        return date('Ymd', $model->getData('init_delivery_date'));
                }],
                ['name'=>'final_delivery_date','label'=>'最终交割日','type'=>'date','default'=>null,'required'=>false,'field'=>function($model){
                    if ($model->getData('final_delivery_date'))
                        return date('Ymd', $model->getData('final_delivery_date'));
                }],
                ['name'=>'mfn','label'=>'MFN','type'=>'choice','choices'=>Model_Entity::getMfnChoices(),'default'=>null,'required'=>false],
                ['name'=>'fdpe_code','label'=>'Form D prefiling and EDGAR code','type'=>'choice','choices'=>Model_Entity::getFdpeCodeChoices(),'default'=>null,'required'=>false],
                ['name'=>'trusteeship','label'=>'托管情况','type'=>'choice','choices'=>Model_Entity::getTrusteeshipChoices(),'default'=>null,'required'=>false],
                ['name'=>'put_on_record','label'=>'备案情况','type'=>'choice','choices'=>Model_Entity::getPutOnRecordChoices(),'default'=>null,'required'=>false],
                ['name'=>'aic_change','label'=>'工商变更','type'=>'choice','choices'=>Model_Entity::getAicChangeChoices(),'default'=>null,'required'=>false],
                ['name'=>'aic_change_desc','label'=>'工商变更说明','type'=>'message','class'=>'with_date','default'=>null,'required'=>false],
                ['name'=>'disclosure','label'=>'信息披露','type'=>'message','class'=>'with_date','default'=>null,'required'=>false],
                ['name'=>'key_terms','label'=>'核心条款','type'=>'textarea','rows'=>15,'default'=>null,'required'=>false],
                ['name'=>'duration','label'=>'存续年限','type'=>'text','default'=>null,'required'=>false],
                ['name'=>'invest_deadline','label'=>'投资截止时间','type'=>'date','default'=>null,'required'=>false,'field'=>function($model){
                    if ($model->getData('invest_deadline'))
                        return date('Ymd', $model->getData('invest_deadline'));
                }],
                ['name'=>'duration_deadline','label'=>'存续截止时间','type'=>'date','default'=>null,'required'=>false,'field'=>function($model){
                    if ($model->getData('duration_deadline'))
                        return date('Ymd', $model->getData('duration_deadline'));
                }],
                ['name'=>'duration_delay_deadline','label'=>'存续延长截止时间','type'=>'date','default'=>null,'required'=>false,'field'=>function($model){
                    if ($model->getData('duration_delay_deadline'))
                        return date('Ymd', $model->getData('duration_delay_deadline'));
                }],
                ['name'=>'to_do','label'=>'Todo','type'=>'choice','choices'=>Model_Entity::getToDoChoices(),'default'=>null,'required'=>false],
                ['name'=>'to_do_detail','label'=>'Todo事项','type'=>'textarea','rows'=>10,'default'=>null,'required'=>false],
                ['name'=>'manager','label'=>'总负责人','type'=>'choosemodel','model'=>'Model_Member','default'=>null,'required'=>false,'field'=>function($model){
                    $members = Model_Member::listAll();
                    if ($person = $model->getData('manager')) {
                        return isset($members[$person]) ? $members[$person]->mName : $person;
                    }
                }],
                ['name'=>'ir_person','label'=>'IR负责人','type'=>'choosemodel','model'=>'Model_Member','default'=>null,'required'=>false,'field'=>function($model){
                    $members = Model_Member::listAll();
                    if ($person = $model->getData('ir_person')) {
                        return isset($members[$person]) ? $members[$person]->mName : $person;
                    }
                }],
                ['name'=>'finance_person','label'=>'财务负责人','type'=>'choosemodel','model'=>'Model_Member','default'=>null,'required'=>false,'field'=>function($model){
                    $members = Model_Member::listAll();
                    if ($person = $model->getData('finance_person')) {
                        return isset($members[$person]) ? $members[$person]->mName : $person;
                    }
                }],
                ['name'=>'legal_person','label'=>'法务负责人','type'=>'choosemodel','model'=>'Model_Member','default'=>null,'required'=>false,'field'=>function($model){
                    $members = Model_Member::listAll();
                    if ($person = $model->getData('legal_person')) {
                        return isset($members[$person]) ? $members[$person]->mName : $person;
                    }
                }],
                ['name'=>'compliance_person','label'=>'合规负责人','type'=>'choosemodel','model'=>'Model_Member','default'=>null,'required'=>false,'field'=>function($model){
                    $members = Model_Member::listAll();
                    if ($person = $model->getData('compliance_person')) {
                        return isset($members[$person]) ? $members[$person]->mName : $person;
                    }
                }],
                ['name'=>'compliance_list','label'=>'合规要求清单','type'=>'choosemodel','model'=>'Model_Checklist','default'=>null,'required'=>false,'show'=>'version','field'=>function($model) {
                    $cli = new Model_Checklist;
                    $cli->addWhere('id', $model->getData('compliance_list'));
                    $cli->select();
                    $list = json_decode($cli->getData('content'));
                    if ($list) {
                        $output = '';
                        foreach($list as $li) {
                            $output .= $li . "\n";
                        }
                    }
                    return $output;
                }],
                ['name'=>'filing_list','label'=>'filing所需清单','type'=>'choosemodel','model'=>'Model_Checklist','default'=>null,'required'=>false,'show'=>'version','field'=>function($model) {
                    $cli = new Model_Checklist;
                    $cli->addWhere('id', $model->getData('filing_list'));
                    $cli->select();
                    $list = json_decode($cli->getData('content'));
                    if ($list) {
                        $output = '';
                        foreach($list as $li) {
                            $output .= $li . "\n";
                        }
                    }
                    return $output;
                }],
                ['name'=>'memo','label'=>'备注','type'=>'message','class'=>'with_date','default'=>null,'required'=>false],
                ['name'=>'_hold_company','label'=>'持股企业','type'=>'rawText','field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('entity_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    $project->setCols('company_id');
                    $project->groupBy('company_id');
                    $deals = $project->find();
                    $companyIds = [];
                    foreach($deals as $i => $deal) {
                        $companyIds[] = $deal->getData('company_id');
                    }
                    return '<a target="_blank" href="/admin/company?__filter='.urlencode('id='.implode($companyIds, ',')).'">'.count($companyIds).'</a>';
                }],
                ['name'=>'_indrect_hold_company','label'=>'间接持股企业','type'=>'rawText','field'=>function($model) {
                    $subIds = Model_EntityRel::getAllSubs($model->getData('id'), 0);
                    $project = new Model_Project;
                    $project->addWhere('entity_id', $subIds, 'IN');
                    $project->addWhere('status', 'valid');
                    $project->setCols('company_id');
                    $project->groupBy('company_id');
                    $deals = $project->find();
                    $companyIds = [];
                    foreach($deals as $i => $deal) {
                        $companyIds[] = $deal->getData('company_id');
                    }
                    return '<a target="_blank" href="/admin/company?__filter='.urlencode('id='.implode($companyIds, ',')).'">'.count($companyIds).'</a>';
                }],
                ['name'=>'_invest_num','label'=>'投资记录','type'=>'rawText','field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('entity_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    return '<a href="/admin/project?__filter='.urlencode('entity_id='.$model->getData('id')).'">'.$project->count().'</a>';
                }],
                ['name'=>'_exit_num','label'=>'退出记录','type'=>'rawText','field'=>function($model) {
                    $project = new Model_Project;
                    $project->addWhere('exit_entity_id', $model->getData('id'));
                    $project->addWhere('status', 'valid');
                    return '<a href="/admin/project?__filter='.urlencode('exit_entity_id='.$model->getData('id')).'">'.$project->count().'</a>';
                }],
                ['name'=>'_parent_entity','label'=>'父主体','type'=>'rawText','field'=>function($model) {
                    $rels = Model_EntityRel::listAll();
                    $count = 0;
                    foreach($rels as $id => $rel) {
                        if ($rel->getData('sub_id') == $model->getData('id')) {
                            $count++;
                        }
                    }
                    return '<span class="data_item"><a href="/admin/entity?__filter='.urlencode('sub_id|id='.$model->getData('id')).'">'.$count.'</a><a class=item_op href="/admin/entityRel?action=read&sub_id='.$model->mId.'"> +新增 </a></span>';
                }],
                ['name'=>'_sub_entity','label'=>'子主体','type'=>'rawText','field'=>function($model) {
                    $rels = Model_EntityRel::listAll();
                    $count = 0;
                    foreach($rels as $id => $rel) {
                        if ($rel->getData('parent_id') == $model->getData('id')) {
                            $count++;
                        }
                    }
                    return '<span class="data_item"><a href="/admin/entity?__filter='.urlencode('parent_id|id='.$model->getData('id')).'">'.$count.'</a><a class=item_op href="/admin/entityRel?action=read&parent_id='.$model->mId.'"> +新增 </a></span>';
                }],
                ['name'=>'update_time','label'=>'更新时间','type'=>'datetime','readonly'=>'true','default'=>time(),'null'=>false,'auto_update'=>true,'field'=>function($model){
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
