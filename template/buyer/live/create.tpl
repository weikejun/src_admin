{%extends file="buyer/framework.tpl"%}
{%block name="body"%}
<div>
    <div id="imgs"></div>
    <form enctype="multipart/form-data" name="uploadImgs" action="/buyer/live/uploadImgs" method="post">
        <div><input type="file" name="imgs_file"></div>
        <div><input type="submit" value="提交"></div>
    </form>

    <form name="create" action="/buyer/live/create" method="post">
        <input type="hidden" name="imgs">
        <div>name:<input name="name"></div>
        <div>country:<input name="country"></div>
        <div>city:<input name="city"></div>
        <div>address:<input name="address"></div>
        <div>brands:<input name="brands"></div>
        <div>start_time:<input name="start_time" value="{%time()%}"></div>
        <div>end_time:<input name="end_time" value="{%time()+46400%}"></div>
        <div>intro:<input name="intro"></div>
        <div><input type="submit" value="提交"></div>
    </form>
</div>

<ul id="lives">
</ul>
{%/block%}
{%block name="foot"%}
<script src="/winphp/js/jquery.form.js"></script>
<script>
var imgBaseUrl={%json_encode(FileUtil::getPublicImgUri())%};
function success(){
    location.href="/buyer_front/index/index";
}
var imgs=[];
$("form[name='uploadImgs']").submit(function(){
    $(this).ajaxSubmit({
        dataType:"json",
        success:function(data){
        if(data.errno!=0){
            $("#imgs").html("图片上传失败");
        }
        var _imgs=data.rst;
        for(var i=0;i<_imgs.length;i++){
            $("#imgs").append("<div><img src='"+imgBaseUrl+_imgs[i]+"'></div>");
            imgs.push(_imgs[i]);
        }
    }});
    return false;
});
$("form[name='create']").submit(function(){
    var form=$(this);
    form.find("input[name='imgs']").val(JSON.stringify(imgs));
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

