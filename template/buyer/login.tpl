{%extends file="buyer/framework.tpl"%}
{%block name="body"%}
<form action="/buyer/login/login" method="post">
<div>name:<input name="name"></div>
<div>password:<input name="password"></div>
<div><input type="submit" value="登录"></div>
</form>
{%/block%}
{%block name="foot"%}
<script>
function success(){
    location.href="/buyer_front/index/index";
}
$("form").submit(function(){
    var form=$(this);
    $.ajax({

        data:form.serialize(),
        url:form.attr("action"),
        type:form.attr("method"),
        dataType:'json',
        success:function(data){
            if(data.errno==0){
                success();
            }
        }
    });
    return false;
});
</script>
{%/block%}

