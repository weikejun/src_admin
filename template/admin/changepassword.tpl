{%extends "admin/framework.tpl"%}
{%block name="head" append%}
{%/block%}
{%block name="content"%}
<div class="container-fluid">
    <div class="row-fluid">
        <h3>
            修改密码
        </h3>
        {%if $msg%}
        <h5 style="color:red">
            {%$msg|escape%}
        </h5>
        {%/if%}
    </div>
    <form name="changepassword" method="post">
        <input type="hidden" name="url" value="{%$url|escape%}"/>
        <div class="row-fluid">
            <span class="span1">旧密码：</span><input class="span3" name="password" type="password" value="" />
        </div>
        <div class="row-fluid">
            <span class="span1">新密码：</span><input class="span3" name="new_password" type="password" value="" />
        </div>
        <div class="row-fluid">
            <span class="span1">再次输入新密码：</span><input class="span3" type="password" name="retype_new_password" value="" />
        </div>
        <div class="row-fluid">
            <input class="span1" type="submit" value="提交" />
        </div>
    </form>
</div>
{%/block%}
{%block name="footjs" append%}
<script>
    (function(){
        var form=document.forms['changepassword'];
        $(form).submit(function(){
            if(this['new_password'].value.length==0
            ||this['new_password'].value!=this['retype_new_password'].value){
                alert("两次输入的密码不一致");
                return false;
            }
        });
    })();
</script>
{%/block%}
