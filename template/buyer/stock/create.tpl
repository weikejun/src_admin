{%extends file="buyer/framework.tpl"%}
{%block name="body"%}
<div>
    <div id="imgs"></div>
    <form enctype="multipart/form-data" name="uploadImgs" action="/buyer/stock/uploadImgs" method="post">
        <div><input type="file" name="imgs_file"></div>
        <div><input type="submit" value="提交"></div>
    </form>

    <form name="create" action="/buyer/stock/create" method="post">
        <input type="hidden" name="imgs">
        <input type="hidden" name="sku">
        <input type="hidden" name="live_id" value="{%intval($smarty.get.live_id)%}">
        <div>name:<input name="name"></div>
        <div>brand:<input name="brand"></div>
        <div>pricein:<input name="pricein"></div>
        <div>pricein_unit:<input name="pricein_unit"></div>
        <div>sku_meta:<input name="sku_meta" value='{"颜色":["红","黄","蓝"],"尺寸":["大","中","小"]}'><input type="button" id="update_sku_meta" value="更新"></div>
        <div>note:<input name="note"></div>
        <div><input type="submit" value="提交"></div>
    </form>
</div>

<ul id="stocks">
</ul>
{%/block%}
{%block name="foot"%}
<script src="/winphp/js/jquery.form.js"></script>
<script>
var imgBaseUrl={%json_encode(FileUtil::getPublicImgUri())%};
function success(){
    location.href="/buyer_front/stock/list?live_id={%intval($smarty.get.live_id)%}";
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
function update_sku_meta(){
    $.ajax({
        success:function(data){
            var html="";
            $.each(data.rst,function(i,sku_value){
                html+="<span>"+sku_value.escapeHTML()+"</span><input type='text' value='1' ><br>";
            });
            $("#update_sku_meta").nextAll().remove().end()
            .after("<div style='margin-left:20px;'>"+html+"</div>");
        },
        data:{sku_meta:$("input[name='sku_meta']").val()},
        type:'get',
        dataType:'json',
        url:"/buyer/stock/combineValues"
    });
}
update_sku_meta();
$("#update_sku_meta").click(function(){
        update_sku_meta();
});
$("form[name='create']").submit(function(){
    var form=$(this);
    form.find("input[name='imgs']").val(JSON.stringify(imgs));

    var sku={};
    $("#update_sku_meta").next().find("span").each(function(i,e){
        sku[$(e).text()]=$(e).next("input").val();
    });
    
    form.find("input[name='sku']").val(JSON.stringify(sku));
    
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
