{%extends file="buyer/framework.tpl"%}
{%block name="body"%}
<div>
    <div id="imgs"></div>
    <form enctype="multipart/form-data" name="uploadImgs" action="/user/user/uploadHead" method="post">
        <div><input type="file" name="avatar"></div>
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
var form=$("form[name='update']");
function success(){
    //location.href="/buyer_front/stock/list?live_id="+form.find("[name='live_id']").val();
    location.href="http://www.baidu.com";
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
var amounts;
function getAmount(sku_value){
    for(var i=0;amounts&&i<amounts.length;i++){
        if(amounts[i].sku_value==sku_value){
            return amounts[i].amount;
        }
    }
    return 1;
}
function update_sku_meta(){
    $.ajax({
        success:function(data){
            var html="";
            $.each(data.rst,function(i,sku_value){
                html+="<span>"+sku_value.escapeHTML()+"</span><input type='text' value='"+getAmount(sku_value)+"' ><br>";
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
$("#update_sku_meta").click(function(){
        update_sku_meta();
});
form.submit(function(){
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
$.ajax({
success:function(data){
    var stock=data.rst.stock;
    amounts=data.rst.stockAmounts;
    var k;
    for(k in stock){
        if(stock.hasOwnProperty(k)){
            if(!stock[k]){
                continue;
            }
            if(typeof stock[k]=='string'){
                form.find("[name='"+k+"']").val(stock[k]);
            }else{
                form.find("[name='"+k+"']").val(JSON.stringify(stock[k]));
            }
        }
    }
    imgs=stock.imgs;
    for(var i=0;i<imgs.length;i++){
        $("#imgs").append("<div><img data-src='"+imgs[i]+"' src='"+imgBaseUrl+imgs[i]+"'><a class='del' href='javascript:;'>删除</a></div>");
    }
    update_sku_meta();
},
url:"/buyer/stock/show",
dataType:"json",
type:'get',
data:{id:{%$smarty.get.id%}}

});
$("#imgs").delegate(".del",'click',function(){
    var img=$(this).prev("img");
    var path=img.attr("data-src");
    $.ajax({
        success:function(){
            location.reload();
        },
        url:"/buyer/stock/delImg",
        dataType:"json",
        type:'post',
        data:{id:{%$smarty.get.id%},path:path}
    });
});
</script>
{%/block%}

