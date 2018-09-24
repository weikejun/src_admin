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
        $template = '
            <p>Team,</p>
            <p>Team, 本次交易情况摘要如下，法务部无意见了。请team及【老曹/云刚】确定可定稿及批注签署。本次交易计划于【手填入时间】完成签署，预计于【手填入时间】完成交割。</p>
            <p>1.交易ID：$id$</p>
            <p>2.$_company_short$,公司处于$turn_sub$轮</p>
            <p>3.本轮交易类型：$deal_type$；源码$enter_exit_type$；其他投资人$other_enter_exit_type$</p>
            <p>4.老股转让情况：$raw_stock_memo$</p>
            <p>5.企业估值：投前$pre_money$；本轮新股融资总额$financing_amount$，投后$post_money$，为上轮估值的$value_change$。本轮稀释$dilution_rate$。</p>
            <p>6.源码投资方案：</p>
            <!--invest plan-->
            <p>（1）源码本轮：$entity_id$，投资金额为$our_amount$，其中$our_amount$买$invest_turn$轮$new_old_stock$（post$_stock_ratio$）</p>
            <p>（2）源码投资款支付时间：</p>
            <!--invest plan-->
            <p>7.其他主要投资人金额与Post比例：$other_investor_summary$</p>
            <p>8.源码退出方案：</p>
            <!--exit plan-->
            <p>（1）$exit_entity_id$，退出金额为$exit_amount$，出售Post$_exit_stock_ratio$的$exit_turn$轮股权。本次退出回报倍数为$_exit_return_rate$。</p>
            <p>（2）源码退出款收取时间：</p>
            <p>（3）源码合计：本次交易交割后，源码各主体合计持股$_shareholding_ratio_turn_sum$。</p>
            <!--exit plan-->
            <p>9.本轮交割后源码各主体合计持股：$_shareholding_ratio_sum$</p>
            <p>10.公司治理</p>
            <p>（1）公司董事会席位$board_number$席，源码$our_board$董事会席位</p>
            <p>（2）源码董事会veto：$board_veto$</p>
            <p>（3）源码股东会veto：$holder_veto$</p>
            <p>11.源码主要义务与限制</p>
            <p>（1）投资限制：$invest_competitor_limit$限制投资人投资竞品。</p>
            <p>（2）股转限制：$stock_transfer_limit$限制投资人股权转让竞品。其他转让限制：$stock_transfer_limit_other$。</p>
            <p>（3）源码的其他责任：$limit_other$</p>
            <p>12.主要权利</p>
			<p>（1）本轮优先清算权：$liquidation_preference$，计算方法为$liquidation_preference_way$，具体情况为：$liquidation_preference_memo$</p>
			<p>（2）本轮优先分红权：$dividends_preference$</p>
			<p>（3）本轮回购权：$buyback_right$，回购义务人为$buyback_obligor$，回购金额计算标准为$buyback_standard$，具体情况为：$buyback_memo$</p>
			<p></p>
			<p>（4）本轮拖售权：$drag_along$，源码对拖售权$drag_along_veto$独立Veto,具体情况为：$drag_along_memo$</p>
			<p>（5）源码$warrant$Warrant，具体情况为：$warrant_memo$</p>
            <p>（6）其他投资人$others_warrant$Warrant，具体情况为：$others_warrant_memo$</p>
			<p>（7）源码$info_right$信息权；信息权门槛为$info_right_threshold$</p>
            <p>（8）源码前轮权利$right_changes$重要变化，情况为：$right_changes_memo$</p>
            <p>（9）本轮与上轮投资人权利不同：$latest_right_changes$</p>
            <p>13.其他重要条款</p>
            <p>（1）重要交割后义务：$delivery_duty$</p>
            <p>（2）其他重要条款/安排：</p>
            <p>14.其他</p>
            <p>（1）与TS比$ts_changes$重大变化，情况为$ts_changes_memo$</p>
            <p>（2）重大风险提示：$risk_tip$</p>
            <p>（3）风控保留意见：$risk_management_view$</p>';
        $model->select();
        if ($model->getData('id')) {
            foreach($this->list_display as $name => $field) {
                if (is_callable($field['field'])) {
                    $val = call_user_func($field['field'], $model);
                } else {
                    $val = $model->getData($name);
                }
                $template = str_replace('$'.$name.'$', "<span>$val</span>", $template);
            }
        }
        if (strpos($model->getData('deal_type'), '源码退') === false) {
            $finder = '<!--exit plan-->';
            $blkSt = strpos($template, $finder);
            $blkEd = strpos($template, $finder, $blkSt + strlen($finder)) + strlen($finder);
            $template = substr_replace($template, '', $blkSt, $blkEd - $blkSt);
        }
        if (strpos($model->getData('deal_type'), '源码投') === false) {
            $finder = '<!--invest plan-->';
            $blkSt = strpos($template, $finder);
            $blkEd = strpos($template, $finder, $blkSt + strlen($finder)) + strlen($finder);
            $template = substr_replace($template, '', $blkSt, $blkEd - $blkSt);
        }
        $this->assign('mailContent', $template);
        $this->_read();
        return $this->display("admin/mail_send/generate.html");
    }
}


