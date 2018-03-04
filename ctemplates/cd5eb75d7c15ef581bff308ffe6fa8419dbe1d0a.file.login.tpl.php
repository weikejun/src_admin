<?php /* Smarty version Smarty-3.1.19, created on 2018-03-04 08:03:41
         compiled from "/usr/local/apps/aimei/aimei_backend/template/admin/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1265670360540ffc03cc7058-08624596%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cd5eb75d7c15ef581bff308ffe6fa8419dbe1d0a' => 
    array (
      0 => '/usr/local/apps/aimei/aimei_backend/template/admin/login.tpl',
      1 => 1520121820,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1265670360540ffc03cc7058-08624596',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_540ffc03d01ee0_75239788',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_540ffc03d01ee0_75239788')) {function content_540ffc03d01ee0_75239788($_smarty_tpl) {?><!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>淘世界运营平台 | 登录页</title>
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
	<link href="/winphp/metronic/media/css/login.css" rel="stylesheet" type="text/css"/>
	<!-- END PAGE LEVEL STYLES -->
	<link rel="shortcut icon" href="/winphp/metronic/media/image/favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
	<!-- BEGIN LOGO -->
	<div class="logo">
        <!--
		<img src="/winphp/metronic/media/image/logo-big.png" alt="" /> 
        -->
	</div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
	<div class="content">
		<!-- BEGIN LOGIN FORM -->
		<form class="form-vertical login-form" method="post" action="/admin/index/login">
			<h3 class="form-title">淘世界运营平台</h3>
			<!--div class="alert alert-error hide">
				<button class="close" data-dismiss="alert"></button>
				<span>Enter any username and password.</span>
			</div-->
			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">用户名</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-user"></i>
						<input class="m-wrap placeholder-no-fix" type="text" placeholder="用户名" name="name"/>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label visible-ie8 visible-ie9">密码</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-lock"></i>
						<input class="m-wrap placeholder-no-fix" type="password" placeholder="密码" name="password"/>
					</div>
				</div>
			</div>
			<div class="form-actions">

				<button type="submit" class="btn green pull-right">
				登录 <i class="m-icon-swapright m-icon-white"></i>
				</button>            
				<input type="submit" class="hide" />
			</div>
		</form>
		<!-- END LOGIN FORM -->        
		<!-- END FORGOT PASSWORD FORM -->
	</div>
	<!-- END LOGIN -->
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
		&copy; &nbsp;2014 &nbsp;&nbsp;爱美主义 
	</div>
	<!-- END COPYRIGHT -->
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->
	<script src="/winphp/metronic/media/js/jquery-1.10.1.min.js" type="text/javascript"></script>
	<script src="/winphp/metronic/media/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="/winphp/metronic/media/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>      
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
	<script src="/winphp/metronic/media/js/jquery.validate.min.js" type="text/javascript"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="/winphp/metronic/media/js/login.js?v=1.0" type="text/javascript"></script>      
	<!-- END PAGE LEVEL SCRIPTS --> 
	<script>
		jQuery(document).ready(function() {     
		  Login.init();
		});
	</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
<?php }} ?>
