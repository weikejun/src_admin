<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>源码投资管理平台 | 登录页</title>
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
			<h3 class="form-title">源码投资管理平台</h3>
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
						<input autocomplete="off" class="m-wrap placeholder-no-fix" type="text" placeholder="用户名" name="name"/>
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
            <div class="control-group">
                <div id="your-dom-id" class="nc-container"></div>
                <input type="hidden" id="nc_token" name="nc_token" />
                <input type="hidden" id="csessionid" name="csessionid" />
                <input type="hidden" id="sig" name="sig" />
                <input type="hidden" name="scene" value="nc_login" />
            </div>
			<div class="form-actions">
{%*
				<label class="checkbox">
				<input type="checkbox" name="remember" value="1"/> Remember me
				</label>
*%}
				<button type="submit" class="btn green pull-right" style="display:none;" id="submit">
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
		&copy; &nbsp;<script>cpPeriod=new Date();document.write(cpPeriod.getFullYear());</script> &nbsp;&nbsp;源码资本 
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
    <script type="text/javascript" charset="utf-8" src="//g.alicdn.com/sd/ncpc/nc.js?t=2015052012"></script>
	<!-- END PAGE LEVEL SCRIPTS --> 
	<script>
		jQuery(document).ready(function() {     
		  Login.init();
		});
var nc_token = ["{%$captchaKey%}", (new Date()).getTime(), Math.random()].join(':');
var NC_Opt = 
{
renderTo: "#your-dom-id",
          appkey: "{%$captchaKey%}",
          scene: "nc_login",
          token: nc_token,
          customWidth: 291,
          trans:{"key1":"code0"},
          elementID: ["usernameID"],
          is_Opt: 0,
          language: "cn",
          isEnabled: true,
          timeout: 3000,
          times:5,
          apimap: {
              // 'analyze': '//a.com/nocaptcha/analyze.jsonp',
              // 'get_captcha': '//b.com/get_captcha/ver3',
              // 'get_captcha': '//pin3.aliyun.com/get_captcha/ver3'
              // 'get_img': '//c.com/get_img',
              // 'checkcode': '//d.com/captcha/checkcode.jsonp',
              // 'umid_Url': '//e.com/security/umscript/3.2.1/um.js',
              // 'uab_Url': '//aeu.alicdn.com/js/uac/909.js',
              // 'umid_serUrl': 'https://g.com/service/um.json'
          },   
callback: function (data) { 
              $('#nc_token').val(nc_token);
              $('#csessionid').val(data.csessionid);
              $('#sig').val(data.sig);
              $('#submit').show();
          }
}
var nc = new noCaptcha(NC_Opt)
    nc.upLang('cn', {
_startTEXT: "请按住滑块，拖动到最右边",
_yesTEXT: "验证通过",
_error300: "哎呀，出错了，点击<a href=\"javascript:__nc.reset()\">刷新</a>再来一次",
_errorNetwork: "网络不给力，请<a href=\"javascript:__nc.reset()\">点击刷新</a>",
})
</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
