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
	<title>源码法务管理平台</title>
    {%/block%}
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
    {%block name="head"%}
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="/winphp/metronic/media/css/bootstrap.min.css?v=201809261026" rel="stylesheet" type="text/css"/>
	<link href="/winphp/metronic/media/css/bootstrap-responsive.min.css?v=1" rel="stylesheet" type="text/css"/>
	<link href="/winphp/metronic/media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="/winphp/metronic/media/css/style-metro.css" rel="stylesheet" type="text/css"/>
	<link href="/winphp/metronic/media/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="/winphp/metronic/media/css/style-responsive.css?v=20180517" rel="stylesheet" type="text/css"/>
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
	<link rel="shortcut icon" href="http://www.sourcecodecap.com/favicon.ico" />
    {%/block%}
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">
{%block name="body"%}
	<!-- BEGIN HEADER -->
	<!-- END HEADER -->
	<!-- BEGIN CONTAINER -->
	<div class="page-container">
            {%block name="content"%}
            {%/block%}
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->
	<div class="footer">
		<div class="footer-inner">
			&copy; <script>document.write(new Date().getFullYear());</script> &nbsp; 源码资本 
		</div>
	</div>
{%/block%}
	<!-- END FOOTER -->
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->
{%block name="footjs"%}
	<script src="/winphp/metronic/media/js/jquery-1.10.1.min.js" type="text/javascript"></script>
	<!--<script src="/winphp/metronic/media/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>-->
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
	<script src="/winphp/js/jquery.dragscroll.js" type="text/javascript"></script>  
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
{%/block%}
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
