{%extends file="buyer/framework.tpl"%}
{%block name="body"%}
<div>
    <a href="/buyer_front/live/create">新建直播</a>
</div>

<ul id="lives">
</ul>
{%/block%}

{%block name="foot"%}
<script>
    function success(lives){
        $.each(lives,function(i,live){
            $("#lives").append("<li>"+live.name.escapeHTML()+"<br>"+
                ts_to_date(live.start_time)+"<br>"+
                ts_to_date(live.end_time)+"<br>"+
                live.address.escapeHTML()+"<br>"+
                live.brands.join(",").escapeHTML()+"<br>"+
                "状态:"+live.status+"<br>"+
                "<a href='/buyer_front/stock/list?live_id="+live.id+"'>"+"商品（"+live.stock_count+"）"+"</a> "+
                "<b>"+"订单（"+live.order_count+"）"+"</b> "+
                "<a href='/buyer_front/order/buyList?live_id="+live.id+"'>"+"待采购"+"</a> "+
                "<a href='/buyer_front/order/waitpackList?live_id="+live.id+"'>"+"待打包"+"</a> "+
                "<a href='/buyer_front/pack/list?live_id="+live.id+"'>"+"待发送包裹"+"</a> "+
                "<a href='/buyer_front/live/update?id="+live.id+"'>"+"编辑"+"</a> "+
                "</li>");
        });
    }
    $.ajax({
        url:'/buyer/live/list',
        type:"get",
        dataType:'json',
        success:function(data){
            success(data.rst.lives);
        }
    });
</script>
{%/block%}
