<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 2.3.1
Version: 1.3
Author: KeenThemes
Website: http://www.keenthemes.com/preview/?theme=metronic
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
    {%block name="title"%}
	<title>源码投资管理平台</title>
    {%/block%}
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
<style>
.content {
    width: 600px;
    height: 1800px;
    border: 1px solid grey;
    padding: 5px;
}
.diff {
    position: absolute;
    top: 0px;
    left: 20px;
    font-size: 14px;
}
.diff .title {
    padding:0px;
    margin:0px;
    height: 30px;
}
.ver_1 {
    position:absolute;
    top:0px;
    left:0px;
}
.ver_1 .txt {
    top:20px;
}
.field {
    padding: 2px 0;
    border-bottom: 1px dashed grey;
}
</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body>
<div class="diff">
    <div class="ver_1">
        <div>当前版本：录入人<b>{%Model_Admin::getNameById($logs1->mOperatorId)%}</b> 录入时间<b>{%date('Ymd H:i:s', $logs1->mCreateTime)%}</b> 日志号<b>{%$logs1->mId%}</b> 动作<b>{%$logs1->mAction%}</b><br />上一版本：录入人<b>{%Model_Admin::getNameById($logs2->mOperatorId)%}</b> 录入时间<b>{%date('Ymd H:i:s', $logs2->mCreateTime)%}</b> 日志号<b>{%$logs2->mId%}</b> 动作<b>{%$logs2->mAction%}</b></div>
        <div class="content txt">
            {%foreach from=$kvs1 item=value key=index%}
            <div class="field">{%Model_Project::getFieldViewName($index)%}: <b>{%$value%}</b></div>
            {%/foreach%}
        </div>
    </div>
</div>
</body>
<!-- END BODY -->
</html>
