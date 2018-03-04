{%extends file="buyer/framework.tpl"%}
{%block name="body"%}
<ul id="packs">
</ul>
{%/block%}

{%block name="foot"%}
<script src="/winphp/js/jquery.form.js"></script>
<script>
    var imgBaseUrl={%json_encode(FileUtil::getPublicImgUri())%};
    function success(packs){
        $.each(packs,function(i,pack){
            var container=$("<li>"+new String(pack.name).escapeHTML()+"<br>"+
                "状态:"+pack.status+"<br>"+
                "<a href='/buyer/pack/listOrder?id="+pack.id+"'>"+"包含的订单"+"</a> "+
                "<form method='post' action='/buyer/pack/unpack'>"+
                '<input type="hidden" name="id" value="'+pack.id+'">'+
                '<input type="submit" value="重新打包">'+
                "</form>"+


    '<div class="imgs"></div>'+
    '<form enctype="multipart/form-data" name="uploadImgs" action="/buyer/pack/uploadImgs" method="post">'+
        '<div><input type="file" name="imgs_file"></div>'+
        '<div><input type="submit" value="提交"></div>'+
    '</form>'+

                "<form method='post' name='send' action='/buyer/pack/send'>"+
                '<input type="hidden" name="id" value="'+pack.id+'">'+
                '<input type="hidden" name="imgs">'+
                '<input type="text" placeholder="快递公司" name="logistic_provider">'+
                '<input type="text" placeholder="快递单号"  name="logistic_no">'+
                '<input type="submit" value="发货">'+
                "</form>"+
                "</li>").appendTo("#packs");
            imgs=pack.imgs;
            for(var i=0;i<imgs.length;i++){
                container.find(".imgs").append("<div><img data-src='"+imgs[i]+"' src='"+imgBaseUrl+imgs[i]+"'><a class='del' href='javascript:;'>删除</a></div>");
            }
            var form=container.find("form[name='uploadImgs']");
            form.data('imgs',imgs);
        });
        
        
        $("form[name='uploadImgs']").submit(function(){
            var form=$(this);
            form.ajaxSubmit({
                dataType:"json",
                success:function(data){
                    var imgsContainer=form.parent().find(".imgs");
                    var imgs=form.data('imgs');
                    if(data.errno!=0){
                        imgsContainer.html("图片上传失败");
                    }
                    var _imgs=data.rst;
                    for(var i=0;i<_imgs.length;i++){
                        imgsContainer.append("<div><img src='"+imgBaseUrl+_imgs[i]+"'></div>");
                        imgs.push(_imgs[i]);
                    }
                }
            });
            return false;
        });
        $("form[name='send']").submit(function(){
            var form=$(this);
            var uploadImgsForm=form.parent().find("form[name='uploadImgs']");
            var imgs=uploadImgsForm.data('imgs');
            imgs=imgs?imgs:[];
            form.find('[name="imgs"]').val(JSON.stringify(imgs));
        });

    }
    $.ajax({
        url:'/buyer/pack/list',
        type:"get",
        dataType:'json',
        data:{'live_id':'{%$smarty.get.live_id%}'},
        success:function(data){
            success(data.rst.packs);
        }
    });
</script>
{%/block%}

