<?php /* Smarty version Smarty-3.1.19, created on 2018-03-03 22:56:49
         compiled from "/usr/local/apps/aimei/aimei_backend/template/admin/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:645314825540ffbf45b3c66-51615194%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ed58225eddef8bca717f38fcae37150bbe348433' => 
    array (
      0 => '/usr/local/apps/aimei/aimei_backend/template/admin/index.tpl',
      1 => 1407914395,
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
  ),
  'nocache_hash' => '645314825540ffbf45b3c66-51615194',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_540ffbf485c247_92696555',
  'variables' => 
  array (
    'user' => 0,
    '__controller' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_540ffbf485c247_92696555')) {function content_540ffbf485c247_92696555($_smarty_tpl) {?><!DOCTYPE html>
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

<link href="/winphp/metronic/media/css/jquery.nestable.css" rel="stylesheet" type="text/css" />

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">

	<!-- BEGIN HEADER -->
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="navbar-inner">
			<div class="container-fluid">
				<!-- BEGIN LOGO -->
				<a class="brand" href="index">
				<img src="/winphp/metronic/media/image/tsj_logo.png" width="24" alt="logo"/>
				</a>
				<!-- END LOGO -->
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
				<img src="/winphp/metronic/media/image/menu-toggler.png" alt="" />
				</a>          
				<!-- END RESPONSIVE MENU TOGGLER -->            
				<!-- BEGIN TOP NAVIGATION MENU -->              
				<ul class="nav pull-right">
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<li class="dropdown user">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img alt="" src="/winphp/metronic/media/image/avatar1_small.jpg" />
                        <span class="username"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['user']->value->mName, ENT_QUOTES, 'ISO-8859-1', true);?>
</span>
						<i class="icon-angle-down"></i>
						</a>
						<ul class="dropdown-menu">
                            <!--
							<li><a href="extra_profile.html"><i class="icon-user"></i> My Profile</a></li>
							<li><a href="page_calendar.html"><i class="icon-calendar"></i> My Calendar</a></li>
							<li><a href="inbox.html"><i class="icon-envelope"></i> My Inbox(3)</a></li>
							<li><a href="#"><i class="icon-tasks"></i> My Tasks</a></li>
							<li class="divider"></li>
							<li><a href="extra_lock.html"><i class="icon-lock"></i> Lock Screen</a></li>
                            -->
                            
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['__controller']->value->getUrlPrefix();?>
/index/logout"><i class="icon-key"></i>退出登录</a></li>
                            
<li><a href="<?php echo $_smarty_tpl->tpl_vars['__controller']->value->getUrlPrefix();?>
/index/changepassword?url=<?php echo rawurlencode($_SERVER['REQUEST_URI']);?>
"><i class="icon-key"></i>修改密码</a></li>

						</ul>
					</li>
					<!-- END USER LOGIN DROPDOWN -->
				</ul>
				<!-- END TOP NAVIGATION MENU --> 
			</div>
		</div>
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->
	<!-- BEGIN CONTAINER -->
	<div class="page-container">
		<!-- BEGIN SIDEBAR -->
<?php /*  Call merged included template "sidebar.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("sidebar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0, '645314825540ffbf45b3c66-51615194');
content_5a9ab7b1191743_72012365($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); 
/*  End of included template "sidebar.tpl" */?>

		<!-- END SIDEBAR -->
		<!-- BEGIN PAGE -->
		<div class="page-content">
            
<div class="container-fluid">
    <div class="row-fluid">
        <h3>
            文档链接
        </h3>
    </div>
    <div class="row-fluid">
        <ul>
            <li><a href="https://app.yinxiang.com/shard/s8/sh/a3d7f9b0-cec5-4255-8f1e-3f3bc2b7defd/cdaecad1168904e00d0eeac907b77b53">用户接口</a></li>
            <li><a href="https://app.yinxiang.com/shard/s8/sh/e4322a80-2c97-4114-907f-3d87bc5fd609/e088428fe1d710aa26c60641a98bdacd">买手接口</a></li>
<hr>
            <li><a href="https://app.yinxiang.com/shard/s8/sh/cd0d1933-464f-4e60-926b-05f70eddcff2/5b542e8f8e0fb64780818a6d5495e628">后台框架支持事务的方法</a></li>
        </ul>
    </div>
</div>

		</div>
		<!-- END PAGE -->
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->
	<div class="footer">
		<div class="footer-inner">
			&copy; 2013 &nbsp; 爱美主义 
		</div>
		<div class="footer-tools">
			<span class="go-top">
			<i class="icon-angle-up"></i>
			</span>
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


	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
<?php }} ?>
<?php /* Smarty version Smarty-3.1.19, created on 2018-03-03 22:56:49
         compiled from "/usr/local/apps/aimei/aimei_backend/template/sidebar.tpl" */ ?>
<?php if ($_valid && !is_callable('content_5a9ab7b1191743_72012365')) {function content_5a9ab7b1191743_72012365($_smarty_tpl) {?>

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
