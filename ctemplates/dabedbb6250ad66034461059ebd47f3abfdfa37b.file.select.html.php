<?php /* Smarty version Smarty-3.1.19, created on 2018-03-03 23:51:13
         compiled from "/usr/local/apps/aimei/aimei_backend/winphp/template/admin/base/select.html" */ ?>
<?php /*%%SmartyHeaderCode:13595493125a9ac471245484-04347804%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dabedbb6250ad66034461059ebd47f3abfdfa37b' => 
    array (
      0 => '/usr/local/apps/aimei/aimei_backend/winphp/template/admin/base/select.html',
      1 => 1410333663,
      2 => 'file',
    ),
    'd6a11e963a9651959c1cb310b0c58010a86689f9' => 
    array (
      0 => '/usr/local/apps/aimei/aimei_backend/template/admin/framework.tpl',
      1 => 1411205819,
      2 => 'file',
    ),
    'a50ab364d80c0477722e2e51591604b45392420e' => 
    array (
      0 => '/usr/local/apps/aimei/aimei_backend/winphp/template/admin/framework.tpl',
      1 => 1416713348,
      2 => 'file',
    ),
    'a0b7eb2046c15b976308b711122df122e5aac8a7' => 
    array (
      0 => '/usr/local/apps/aimei/aimei_backend/template/sidebar.tpl',
      1 => 1420554432,
      2 => 'file',
    ),
    '353ca23eecd10ff1f0d7e639085a017c17d54346' => 
    array (
      0 => '/usr/local/apps/aimei/aimei_backend/winphp/template/admin/base/_list.html',
      1 => 1420554432,
      2 => 'file',
    ),
    '4072443b61eff152536efd03ba6a8b878c701394' => 
    array (
      0 => '/usr/local/apps/aimei/aimei_backend/winphp/template/admin/base/_filter_js.html',
      1 => 1407914395,
      2 => 'file',
    ),
    '9588b24071890d7ec4cd847396b5b966d729c7aa' => 
    array (
      0 => '/usr/local/apps/aimei/aimei_backend/winphp/template/admin/base/_multi_actions_js.html',
      1 => 1416713348,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13595493125a9ac471245484-04347804',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user' => 0,
    '__controller' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5a9ac4712c58d7_71880949',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a9ac4712c58d7_71880949')) {function content_5a9ac4712c58d7_71880949($_smarty_tpl) {?><!DOCTYPE html>
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
    
	<title>淘世界运营平台</title>
    
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
    
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="/winphp/metronic/media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="/winphp/metronic/media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
	<link href="/winphp/metronic/media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="/winphp/metronic/media/css/style-metro.css" rel="stylesheet" type="text/css"/>
	<link href="/winphp/metronic/media/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="/winphp/metronic/media/css/style-responsive.css" rel="stylesheet" type="text/css"/>
	<link href="/winphp/metronic/media/css/default.css" rel="stylesheet" type="text/css" id="style_color"/>
	<link href="/winphp/metronic/media/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES --> 
	<link href="/winphp/metronic/media/css/jquery.gritter.css" rel="stylesheet" type="text/css"/>
	<link href="/winphp/metronic/media/css/daterangepicker.css" rel="stylesheet" type="text/css" />
	<link href="/winphp/metronic/media/css/fullcalendar.css" rel="stylesheet" type="text/css"/>
	<link href="/winphp/metronic/media/css/jqvmap.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="/winphp/metronic/media/css/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
	<!-- END PAGE LEVEL STYLES -->
	<link rel="shortcut icon" href="/winphp/metronic/media/image/favicon.ico" />
    
<style>
.navbar-fixed-top, .navbar-fixed-bottom{position:absolute}
</style>

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">

<div class="row-fluid">
    <div class="span12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box light-grey">
            <div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i><?php echo $_smarty_tpl->tpl_vars['executeInfo']->value['controllerName'];?>
</div>
            </div>
            <div class="portlet-body">
                <div class="clearfix">
                    <div class="btn-group">
                        <a target="_blank" href="?action=read" id="sample_editable_1_new" class="btn green">
                            新建 <i class="icon-plus"></i>
                        </a>
                        <a style="margin-left:20px;" href="javascript:;" onclick="location.reload();return false;" class="btn green">
                            刷新 
                        </a>
                    </div>
                </div>

                <?php /*  Call merged included template "admin/base/_list.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("admin/base/_list.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0, '13595493125a9ac471245484-04347804');
content_5a9ac47128a9b3_16431686($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); 
/*  End of included template "admin/base/_list.html" */?>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>

	<!-- END FOOTER -->
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->

	<script src="/winphp/metronic/media/js/jquery-1.10.1.min.js" type="text/javascript"></script>
	<!--<script src="/winphp/metronic/media/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>-->
	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<!-- <script src="/winphp/metronic/media/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>     --> 
	<script src="/winphp/metronic/media/js/bootstrap.min.js" type="text/javascript"></script>
	<!--[if lt IE 9]>
	<script src="/winphp/metronic/media/js/excanvas.min.js"></script>
	<script src="/winphp/metronic/media/js/respond.min.js"></script>  
	<![endif]-->   
	<script src="/winphp/metronic/media/js/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/jquery.blockui.min.js" type="text/javascript"></script>  
	<script src="/winphp/metronic/media/js/jquery.cookie.min.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/jquery.uniform.min.js" type="text/javascript" ></script>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script src="/winphp/metronic/media/js/jquery.vmap.js" type="text/javascript"></script>   
	<script src="/winphp/metronic/media/js/jquery.vmap.russia.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/jquery.vmap.world.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/jquery.vmap.europe.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/jquery.vmap.germany.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/jquery.vmap.usa.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/jquery.vmap.sampledata.js" type="text/javascript"></script>  
	<script src="/winphp/metronic/media/js/jquery.flot.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/jquery.flot.resize.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/jquery.pulsate.min.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/date.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/daterangepicker.js" type="text/javascript"></script>     
	<script src="/winphp/metronic/media/js/jquery.gritter.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/fullcalendar.min.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/jquery.easy-pie-chart.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/jquery.sparkline.min.js" type="text/javascript"></script>  
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="/winphp/metronic/media/js/app.js" type="text/javascript"></script>
	<!-- END PAGE LEVEL SCRIPTS -->  
	<script>
		jQuery(document).ready(function() {    
		   App.init(); // initlayout and core plugins
/*
		   Index.init();
		   Index.initJQVMAP(); // init index page's custom scripts
		   Index.initCalendar(); // init index page's custom scripts
		   Index.initCharts(); // init index page's custom scripts
		   Index.initChat();
		   Index.initMiniCharts();
		   Index.initDashboardDaterange();
		   Index.initIntro();
*/
		});
	</script>

<script>
    (function(){
        var itemDatas=<?php echo json_encode(array_map(create_function('$item','return $item->getData();'),$_smarty_tpl->tpl_vars['modelDataList']->value));?>
;
        $("tbody>tr").dblclick(function(){
            var index=$(this).index();
            parent.window.choosemodel('<?php echo $_smarty_tpl->tpl_vars['executeInfo']->value['controllerName'];?>
','<?php echo $_GET['field'];?>
',itemDatas[index]['id']);
            parent.window.choosemodelPopup.close();
        });
        $("form[name='search']").submit(function(){
            $(this).find('[name="action"]').val('select_search');
        });
    })();
</script>

<?php /*  Call merged included template "admin/base/_filter_js.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("admin/base/_filter_js.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0, '13595493125a9ac471245484-04347804');
content_5a9ac4712c0459_79816912($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); 
/*  End of included template "admin/base/_filter_js.html" */?>
<?php /*  Call merged included template "admin/base/_multi_actions_js.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("admin/base/_multi_actions_js.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0, '13595493125a9ac471245484-04347804');
content_5a9ac4712c4914_06193104($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); 
/*  End of included template "admin/base/_multi_actions_js.html" */?>

	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
<?php }} ?>
<?php /* Smarty version Smarty-3.1.19, created on 2018-03-03 23:51:13
         compiled from "/usr/local/apps/aimei/aimei_backend/template/sidebar.tpl" */ ?>
<?php if ($_valid && !is_callable('content_5a9ac4712578a2_73026850')) {function content_5a9ac4712578a2_73026850($_smarty_tpl) {?>

		<div class="page-sidebar nav-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->        
			<ul class="page-sidebar-menu">
				<li>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler hidden-phone"></div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				<li>
					<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                    <!--
					<form class="sidebar-search">
						<div class="input-box">
							<a href="javascript:;" class="remove"></a>
							<input type="text" placeholder="Search..." />
							<input type="button" class="submit" value=" " />
						</div>
					</form>
                    -->
					<!-- END RESPONSIVE QUICK SEARCH FORM -->
				</li>
				<!--li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Index') {?> active <?php }?>">
					<a href="/admin/index">
					<i class="icon-home"></i> 
                    <span class="title">首页</span>
					<span class="selected"></span>
					</a>
				</li-->
				<!--li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='BuyerVerify') {?> active <?php }?>">
					<a href="/cadmin/buyerVerify">
					<i class="icon-home"></i> 
                    <span class="title">买手审核</span>
					<span class="selected"></span>
					</a>
				</li-->
                <?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['c']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['controllers']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['c']->_loop = true;
?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='IndexNew') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='IndexNew') {?> active <?php }?>">
					<a href="/admin/indexNew">
					<i class="icon-home"></i> 
                    <span class="title">首页推荐</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='BuyerRank') {?>
                <li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='BuyerRank') {?> active <?php }?>">
                    <a href="/admin/buyerRank">
                        <i class="icon-home"></i>
                        <span class="title">推荐买手</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='StockBook') {?>
                <li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='StockBook') {?> active <?php }?>">
                    <a href="/admin/stockBook">
                        <i class="icon-home"></i>
                        <span class="title">图墙推荐</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Admin') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Admin') {?> active <?php }?>">
					<a href="/admin/admin">
					<i class="icon-home"></i> 
                    <span class="title">系统用户</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Buyer') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Buyer') {?> active <?php }?>">
					<a href="/admin/buyer">
					<i class="icon-home"></i> 
                    <span class="title">买手管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Live') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Live') {?> active <?php }?>">
					<a href="/admin/live">
					<i class="icon-home"></i> 
                    <span class="title">直播管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='LiveFlow') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='LiveFlow') {?> active <?php }?>">
					<a href="/admin/liveFlow">
					<i class="icon-home"></i> 
                    <span class="title">直播流</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='LiveStock') {?>
                <li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='LiveStock') {?> active <?php }?>">
                    <a href="/admin/LiveStock">
                        <i class="icon-home"></i>
                        <span class="title">直播商品审核</span>
                        <span class="selected"></span>
                    </a>
                </li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Stock') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Stock') {?> active <?php }?>">
					<a href="/admin/stock">
					<i class="icon-home"></i> 
                    <span class="title">商品管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Order') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Order') {?> active <?php }?>">
					<a href="/admin/order">
					<i class="icon-home"></i> 
                    <span class="title">订单管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Payment') {?>
                <li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Payment') {?> active <?php }?>">
					<a href="/admin/payment">
					<i class="icon-home"></i> 
                    <span class="title">支付管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Pack') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Pack') {?> active <?php }?>">
					<a href="/admin/pack">
					<i class="icon-home"></i> 
                    <span class="title">包裹管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Storage') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Storage') {?> active <?php }?>">
					<a href="/admin/storage">
					<i class="icon-home"></i> 
                    <span class="title">库存管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='StoragePurchasePending') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='StoragePurchasePending') {?> active <?php }?>">
					<a href="/admin/storagePurchasePending">
					<i class="icon-home"></i> 
                    <span class="title">问题件处理（买手）</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='StorageCsPending') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='StorageCsPending') {?> active <?php }?>">
					<a href="/admin/storageCsPending">
					<i class="icon-home"></i> 
                    <span class="title">问题件处理（买家）</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='DeliveryAbroad') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='DeliveryAbroad') {?> active <?php }?>">
					<a href="/admin/deliveryAbroad">
					<i class="icon-home"></i> 
                    <span class="title">买手结算管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Logistic') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Logistic') {?> active <?php }?>">
					<a href="/admin/logistic">
					<i class="icon-home"></i> 
                    <span class="title">国内物流单</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='ExpressPrint') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='ExpressPrint') {?> active <?php }?>">
					<a href="/admin/expressPrint">
					<i class="icon-home"></i> 
                    <span class="title">发货打印记录</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='UserRefund') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='UserRefund') {?> active <?php }?>">
					<a href="/admin/userRefund">
					<i class="icon-home"></i> 
                    <span class="title">退款管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='User') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='User') {?> active <?php }?>">
					<a href="/admin/user">
					<i class="icon-home"></i> 
                    <span class="title">买家管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Coupon') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Coupon') {?> active <?php }?>">
					<a href="/admin/coupon">
					<i class="icon-home"></i> 
                    <span class="title">代金券管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='buyerWithdraw') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='buyerWithdraw') {?> active <?php }?>">
					<a href="/admin/buyerWithdraw">
					<i class="icon-home"></i> 
                    <span class="title">买手提款</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='TaskPush') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='TaskPush') {?> active <?php }?>">
					<a href="/admin/TaskPush">
					<i class="icon-home"></i> 
                    <span class="title">定时消息推送</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='UserReminder') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='UserReminder') {?> active <?php }?>">
					<a href="/admin/UserReminder">
					<i class="icon-home"></i> 
                    <span class="title">用户提醒</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='SystemLog') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='SystemLog') {?> active <?php }?>">
					<a href="/admin/SystemLog">
					<i class="icon-home"></i> 
                    <span class="title">系统日志</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='OrderLog') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='OrderLog') {?> active <?php }?>">
					<a href="/admin/OrderLog">
					<i class="icon-home"></i> 
                    <span class="title">订单日志</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='EasemobMsg') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='EasemobMsg') {?> active <?php }?>">
					<a href="/admin/EasemobMsg">
					<i class="icon-home"></i> 
                    <span class="title">聊天消息记录</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Cs') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Cs') {?> active <?php }?>">
					<a href="/admin/cs">
					<i class="icon-home"></i> 
                    <span class="title">客服账号管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Permission') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Permission') {?> active <?php }?>">
					<a href="/admin/Permission">
					<i class="icon-home"></i> 
                    <span class="title">权限管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='RolePermission') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='RolePermission') {?> active <?php }?>">
					<a href="/admin/RolePermission">
					<i class="icon-home"></i> 
                    <span class="title">角色权限管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Group') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Group') {?> active <?php }?>">
					<a href="/admin/Group">
					<i class="icon-home"></i> 
                    <span class="title">角色管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='AdminGroup') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='AdminGroup') {?> active <?php }?>">
					<a href="/admin/AdminGroup">
					<i class="icon-home"></i> 
                    <span class="title">用户角色管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['c']->value=='Action') {?>
				<li class="start <?php if ($_smarty_tpl->tpl_vars['executeInfo']->value['controllerName']=='Action') {?> active <?php }?>">
					<a href="/admin/Action">
					<i class="icon-home"></i> 
                    <span class="title">访问权限管理</span>
					<span class="selected"></span>
					</a>
				</li>
                <?php }?>
                <?php } ?>


			</ul>

			<!-- END SIDEBAR MENU -->
		</div>
<?php }} ?>
<?php /* Smarty version Smarty-3.1.19, created on 2018-03-03 23:51:13
         compiled from "/usr/local/apps/aimei/aimei_backend/winphp/template/admin/base/_list.html" */ ?>
<?php if ($_valid && !is_callable('content_5a9ac47128a9b3_16431686')) {function content_5a9ac47128a9b3_16431686($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include '/usr/local/apps/aimei/aimei_backend/lib/Smarty/plugins/function.cycle.php';
?><div id="sample_1_wrapper" class="dataTables_wrapper form-inline" role="grid">
    <?php if ($_smarty_tpl->tpl_vars['pageAdmin']->value->list_filter) {?>
    <form name='__filter'>
        <?php  $_smarty_tpl->tpl_vars['filter'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['filter']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pageAdmin']->value->list_filter; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['filter']->key => $_smarty_tpl->tpl_vars['filter']->value) {
$_smarty_tpl->tpl_vars['filter']->_loop = true;
?>
                <?php echo $_smarty_tpl->tpl_vars['filter']->value->toHtml();?>

        <?php } ?>
        <button type="submit" class="btn blue"><i class="icon-ok"></i>筛选</button>
    </form>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['pageAdmin']->value->search_fields) {?>
    <div class="row-fluid">
        <div class="span6">
            <!--div id="sample_1_length" class="dataTables_length">
                <label>每页显示
                    <select size="1" name="sample_1_length" aria-controls="sample_1" class="m-wrap small">
                        <option value="5" selected="selected">
                        5</option>
                        <option value="15">
                        15</option>
                        <option value="20">
                        20</option>
                        <option value="-1">
                        All</option>
                    </select>
                    条结果</label>
            </div-->
        </div>
        <div class="span6">
            <div class="dataTables_filter" id="sample_1_filter">
                <label>
                    <form style="margin:0;" name="search">
                        <input type="hidden" name="action" value="search">
                        搜索: <input name="search" value="<?php echo htmlspecialchars($_GET['search'], ENT_QUOTES, 'ISO-8859-1', true);?>
" type="text" aria-controls="sample_1" class="m-wrap medium">
                        <?php if ($_GET['field']) {?>
                        <input name="field" value="<?php echo htmlspecialchars($_GET['field'], ENT_QUOTES, 'ISO-8859-1', true);?>
" type="hidden">
                        <?php }?>
                        <button type="submit" class="btn blue"><i class="icon-ok"></i>提交</button>
                    </form>
                </label>
            </div>
        </div>
    </div>
    <?php }?>
    <table class="table table-striped table-bordered table-hover dataTable" id="sample_1" aria-describedby="sample_1_info">

        <thead>
            <tr role="row">
                <th><input type="checkbox" id="selectAll"/></th>
                <?php  $_smarty_tpl->tpl_vars['list_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['list_item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pageAdmin']->value->list_display; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['list_item']->key => $_smarty_tpl->tpl_vars['list_item']->value) {
$_smarty_tpl->tpl_vars['list_item']->_loop = true;
?>
                <th>
                    <?php if (is_string($_smarty_tpl->tpl_vars['list_item']->value)) {?>
                    <?php echo $_smarty_tpl->tpl_vars['list_item']->value;?>

                    <?php } elseif (isset($_smarty_tpl->tpl_vars['list_item']->value['label'])) {?>
                    <?php echo $_smarty_tpl->tpl_vars['list_item']->value['label'];?>

                    <?php } else { ?>
                    <?php echo strval($_smarty_tpl->tpl_vars['list_item']->value);?>

                    <?php }?>
                </th>
                <?php } ?>
                <th>操作</th>
            </tr>
        </thead>


        <tbody>
            <?php  $_smarty_tpl->tpl_vars['modelData'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['modelData']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['modelDataList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['modelData']->key => $_smarty_tpl->tpl_vars['modelData']->value) {
$_smarty_tpl->tpl_vars['modelData']->_loop = true;
?>
            <tr class="gradeX <?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
">
                <td><input type="checkbox" name="__item" value="<?php echo $_smarty_tpl->tpl_vars['modelData']->value->mId;?>
" /></td>
                <?php  $_smarty_tpl->tpl_vars['list_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['list_item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pageAdmin']->value->list_display; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['list_item']->key => $_smarty_tpl->tpl_vars['list_item']->value) {
$_smarty_tpl->tpl_vars['list_item']->_loop = true;
?>
                <td>

                    <?php if (is_array($_smarty_tpl->tpl_vars['list_item']->value)&&isset($_smarty_tpl->tpl_vars['list_item']->value['label'])) {?>
                    <?php $_smarty_tpl->tpl_vars['list_item'] = new Smarty_variable($_smarty_tpl->tpl_vars['list_item']->value['field'], null, 0);?>
                    <?php }?>

                    <?php if (is_string($_smarty_tpl->tpl_vars['list_item']->value)) {?>
                    <?php echo $_smarty_tpl->tpl_vars['modelData']->value->getData($_smarty_tpl->tpl_vars['list_item']->value);?>

                    <?php } elseif (is_callable($_smarty_tpl->tpl_vars['list_item']->value)) {?>
                    <?php echo call_user_func($_smarty_tpl->tpl_vars['list_item']->value,$_smarty_tpl->tpl_vars['modelData']->value,$_smarty_tpl->tpl_vars['pageAdmin']->value,$_smarty_tpl->tpl_vars['modelDataList']->value);?>

                    <?php } else { ?>
                    <?php echo strval($_smarty_tpl->tpl_vars['list_item']->value);?>

                    <?php }?>


                </td>
                <?php } ?>
                <td>
                    <?php if (!$_smarty_tpl->tpl_vars['pageAdmin']->value->single_actions_default||$_smarty_tpl->tpl_vars['pageAdmin']->value->single_actions_default['edit']) {?><a href="?action=read&id=<?php echo $_smarty_tpl->tpl_vars['modelData']->value->mId;?>
">编辑</a><?php }?>
                    <?php if (!$_smarty_tpl->tpl_vars['pageAdmin']->value->single_actions_default||$_smarty_tpl->tpl_vars['pageAdmin']->value->single_actions_default['delete']) {?><a confirm="你确定要删除么？" href="?action=delete&id=<?php echo $_smarty_tpl->tpl_vars['modelData']->value->mId;?>
">删除</a><?php }?>
                    <?php  $_smarty_tpl->tpl_vars['action'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['action']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pageAdmin']->value->single_actions; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['action']->key => $_smarty_tpl->tpl_vars['action']->value) {
$_smarty_tpl->tpl_vars['action']->_loop = true;
?>
                    <?php if (!$_smarty_tpl->tpl_vars['action']->value['enable']||(is_callable($_smarty_tpl->tpl_vars['action']->value['enable'])&&call_user_func($_smarty_tpl->tpl_vars['action']->value['enable'],$_smarty_tpl->tpl_vars['modelData']->value,$_smarty_tpl->tpl_vars['pageAdmin']->value))) {?>
                    <a <?php if ($_smarty_tpl->tpl_vars['action']->value['target']) {?>target="<?php echo $_smarty_tpl->tpl_vars['action']->value['target'];?>
"<?php }?> <?php if ($_smarty_tpl->tpl_vars['action']->value['confirm']) {?>confirm="<?php echo $_smarty_tpl->tpl_vars['action']->value['confirm'];?>
"<?php }?> href="<?php if (is_string($_smarty_tpl->tpl_vars['action']->value['action'])) {?><?php echo $_smarty_tpl->tpl_vars['action']->value['action'];?>
<?php } elseif (is_callable($_smarty_tpl->tpl_vars['action']->value['action'])) {?><?php echo call_user_func($_smarty_tpl->tpl_vars['action']->value['action'],$_smarty_tpl->tpl_vars['modelData']->value,$_smarty_tpl->tpl_vars['pageAdmin']->value);?>
<?php } else { ?><?php echo strval($_smarty_tpl->tpl_vars['action']->value['action']);?>
<?php }?>"><?php echo $_smarty_tpl->tpl_vars['action']->value['label'];?>
</a>
                    <?php }?>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="row-fluid">
        <ul id="multi_actions">
            <?php  $_smarty_tpl->tpl_vars['action'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['action']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pageAdmin']->value->multi_actions; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['action']->key => $_smarty_tpl->tpl_vars['action']->value) {
$_smarty_tpl->tpl_vars['action']->_loop = true;
?>
            <li class="span1" style="width: 100px;"><a href="javascript:;" <?php if ($_smarty_tpl->tpl_vars['action']->value['required']===false) {?>require='false'<?php }?> action="<?php echo $_smarty_tpl->tpl_vars['action']->value['action'];?>
" target="<?php echo $_smarty_tpl->tpl_vars['action']->value['target'];?>
"><?php echo $_smarty_tpl->tpl_vars['action']->value['label'];?>
</a></li>
            <?php } ?>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="span4">
            <div class="dataTables_info" id="sample_1_info">
                 本页显示第 <?php echo $_smarty_tpl->tpl_vars['_startIndex']->value+1;?>
 到 <?php echo $_smarty_tpl->tpl_vars['_startIndex']->value+count($_smarty_tpl->tpl_vars['modelDataList']->value);?>
 结果，共 <?php echo $_smarty_tpl->tpl_vars['_allCount']->value;?>
 结果</div>
        </div>
        <div class="span8">
            <div class="dataTables_paginate paging_bootstrap pagination">
                <?php $_smarty_tpl->tpl_vars['_pageAll'] = new Smarty_variable(ceil($_smarty_tpl->tpl_vars['_allCount']->value/$_smarty_tpl->tpl_vars['_pageSize']->value), null, 0);?>
                <?php if ($_smarty_tpl->tpl_vars['_pageAll']->value>1) {?> 
                <ul>
                    <?php if ($_smarty_tpl->tpl_vars['_page']->value>0) {?>
                    <li>
                        <a href="<?php echo DefaultViewSetting::build_url(array('page'=>$_smarty_tpl->tpl_vars['_page']->value-1),$_smarty_tpl);?>
">
                        ← <span class="hidden-480">上一页
                        </a>
                    </li>
                    <?php }?>

                    <?php $_smarty_tpl->tpl_vars['startPage'] = new Smarty_variable(max(0,$_smarty_tpl->tpl_vars['_page']->value-3), null, 0);?>
                    <?php $_smarty_tpl->tpl_vars['endPage'] = new Smarty_variable(min($_smarty_tpl->tpl_vars['_pageAll']->value-1,$_smarty_tpl->tpl_vars['_page']->value+4), null, 0);?>
                    <?php if ($_smarty_tpl->tpl_vars['startPage']->value>0) {?>
                    <li><a href="<?php echo DefaultViewSetting::build_url(array('page'=>0),$_smarty_tpl);?>
">1</a></li>
                    <li><a>...</a></li>
                    <?php }?>
                    
                    <?php  $_smarty_tpl->tpl_vars['page'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['page']->_loop = false;
 $_from = range($_smarty_tpl->tpl_vars['startPage']->value,$_smarty_tpl->tpl_vars['endPage']->value); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['page']->key => $_smarty_tpl->tpl_vars['page']->value) {
$_smarty_tpl->tpl_vars['page']->_loop = true;
?>
                    <li<?php if ($_smarty_tpl->tpl_vars['_page']->value==$_smarty_tpl->tpl_vars['page']->value) {?> class="active"<?php }?>><a href="<?php echo DefaultViewSetting::build_url(array('page'=>$_smarty_tpl->tpl_vars['page']->value),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['page']->value+1;?>
</a></li>
                    <?php } ?>
                    
                    <?php if ($_smarty_tpl->tpl_vars['endPage']->value<$_smarty_tpl->tpl_vars['_pageAll']->value-1) {?>
                    <li><a>...</a></li>
                    <li><a href="<?php echo DefaultViewSetting::build_url(array('page'=>$_smarty_tpl->tpl_vars['_pageAll']->value-1),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['_pageAll']->value;?>
</a></li>
                    <?php }?>

                    <?php if ($_smarty_tpl->tpl_vars['_page']->value<$_smarty_tpl->tpl_vars['_pageAll']->value-1) {?>
                    <li>
                        <a href="<?php echo DefaultViewSetting::build_url(array('page'=>$_smarty_tpl->tpl_vars['_page']->value+1),$_smarty_tpl);?>
">
                            <span class="hidden-480">
                                下一页</span> → 
                        </a>
                    </li>
                    <?php }?>
                </ul>
                <?php }?>
            </div>
        </div>

    </div>


</div>


<?php }} ?>
<?php /* Smarty version Smarty-3.1.19, created on 2018-03-03 23:51:13
         compiled from "/usr/local/apps/aimei/aimei_backend/winphp/template/admin/base/_filter_js.html" */ ?>
<?php if ($_valid && !is_callable('content_5a9ac4712c0459_79816912')) {function content_5a9ac4712c0459_79816912($_smarty_tpl) {?><script>
    (function(){
        if($(".datetimepicker")[0]){
            $("head").append('<link rel="stylesheet" type="text/css" href="/winphp/metronic/media/css/datetimepicker.css" />');
            $.ajax({
                cache:true,
                url:"/winphp/metronic/media/js/bootstrap-datetimepicker.js",
                success:function(){
                    $(".datetimepicker").datetimepicker({
                        rtl : App.isRTL()
                    });
                }
            })
        }
        $("form[name='__filter']").submit(function(){
            //action 和field两个参数是为了支持choosemodel
            location.href=location.pathname+"?__filter="+encodeURIComponent($(this).serialize())<?php if ($_GET['action']) {?>+"&action=<?php echo rawurlencode($_GET['action']);?>
"<?php }?><?php if ($_GET['field']) {?>+"&field=<?php echo rawurlencode($_GET['field']);?>
"<?php }?>;
            return false;
        });
        $("body").delegate("[confirm]",'click',function(){
            return confirm($(this).attr("confirm"));
        });
    })();
</script>
<?php }} ?>
<?php /* Smarty version Smarty-3.1.19, created on 2018-03-03 23:51:13
         compiled from "/usr/local/apps/aimei/aimei_backend/winphp/template/admin/base/_multi_actions_js.html" */ ?>
<?php if ($_valid && !is_callable('content_5a9ac4712c4914_06193104')) {function content_5a9ac4712c4914_06193104($_smarty_tpl) {?><script>
    (function(){
        $("#multi_actions").delegate("a","mousedown",function(){
            var checkboxes=$(".checker .checked input[name=\"__item\"]");
            var ids=checkboxes.map(function(){
                return $(this).val();
            });
            if(!ids||ids.length==0 && $(this).attr("require")!='false'){
                alert("未选中");
                return false;
            }
            ids=Array.prototype.slice.call(ids,0);
            var action=$(this).attr("action");
            $(this).attr("href",action.replace("__ids_json__",JSON.stringify(ids)).replace("__ids__",ids.join(",")));
        });
        $("#selectAll").click(function(){
            var checkboxes=$(".checker input[name=\"__item\"]");
            if($(this).prop("checked")){
                checkboxes.attr("checked",true).parent().addClass("checked");
            }else{
                checkboxes.attr("checked",false).parent().removeClass("checked");
            }
        });
    })();
</script>

<?php }} ?>
