{%extends file="buyer/framework.tpl"%}
{%block name="body"%}
<div>
    <div>
        <a href="/buyer_front/stock/create?live_id={%intval($smarty.get.live_id)%}">创建商品</a>
    </div>
    <div id="live_name"></div>
    <ul id="stocks">
        
    </ul>
</div>

{%/block%}
{%block name="foot"%}
<script src="/winphp/js/jquery.form.js"></script>
<script>
    function success(data){
        var live=data.live;
        var stocks=data.stocks;
        $("#live_name").html(live.name);

        $.each(stocks,function(i,stock){
            $("#stocks").append("<li>"+stock.name.escapeHTML()+"<br>"+
                stock.pricein+stock.pricein_unit+"<br>"+
                new String(stock.note).escapeHTML()+"<br>"+
                "审核状态："+new String(stock.status).escapeHTML()+"<br>"+
                "上架状态："+new String(stock.onshelf).escapeHTML()+"<br>"+
                "<a href='javascript:;'>"+"订单（"+stock.order_count+"）"+"</a> "+
                "<a href='javascript:;'>"+"评论（"+stock.comment_count+"）"+"</a> "+
                "<a href='/buyer_front/stock/update?id="+stock.id+"'>编辑</a> "+
                "<a href='/buyer/stock/delete?id="+stock.id+"'>删除</a> "+
                "</li>");
        });
    }
    $.ajax({
        url:'/buyer/stock/list?live_id='+{%intval($smarty.get.live_id)%},
        type:"get",
        dataType:'json',
        success:function(data){
            if(data.errno!=0){
                alert(JSON.stringify(data));
                return false;
            }
            success(data.rst);
        }
    });
</script>
{%/block%}


