{%extends "file:[winphp]admin/framework.tpl"%}
{%block name="right_top_nav" append%}
<li><a href="{%$__controller->getUrlPrefix()%}/index/changepassword?url={%$smarty.server.REQUEST_URI|escape:url%}"><i class="icon-key"></i>修改密码</a></li>
{%/block%}
{%block name="head" append%}
<style>
.navbar-fixed-top, .navbar-fixed-bottom{position:absolute}
</style>
{%/block%}
