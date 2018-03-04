{%extends file="buyer/framework.tpl"%}
{%block name="body"%}
<div id="buyList">
</div>
{%/block%}

{%block name="foot"%}
<script>
    function success(data){
        $("#buyList").empty();
        $.each(data.stockInfos,function(i,stockInfo){
            $("#buyList").append("<div>"+stockInfo.name.escapeHTML()+" "+stockInfo.sku.escapeHTML()+" "+stockInfo.pricein+" "+stockInfo.pricein_unit+"</div>");
            var html="";
            $.each(stockInfo.orders,function(i,order){
                html+="<tr>";
                html+="<td><input type='checkbox' class='order_id' value='"+order.id+"' /></td>";
                html+="<td>"+order.id+"</td>";
                html+="<td>"+order.note+"</td>";
                html+="<td>"+order.num+"</td>";
                html+="</tr>";
            });
            $("#buyList").append(
                "<form action='/buyer/order/finishBuy'>"+
                "<input name='stock_amount_id' type='hidden' value='"+stockInfo.stock_amount_id+"'>"+
                "<table>"+html+"</table><input type=submit value='完成采购'></form>");
        });
    }
    $.ajax({
        url:'/buyer/order/buyList',
        type:"get",
        data:{live_id:{%$smarty.get.live_id%}},
        dataType:'json',
        success:function(data){
            if(!data||data.errno!=0){
                $("#buyList").html(data);
                return ;
            }
            success(data.rst);
        }
    });
    $("body").delegate('form','submit',function(e){
        e.preventDefault();
        e.stopPropagation();
        var form=$(this);
        var order_ids=$(this).find('.order_id:checked').map(function(){
            return $(this).val();
        });
        order_ids=Array.prototype.slice.call(order_ids);
        $.ajax({
            success:function(data){
                alert(JSON.stringify(data));
            },
            dataType:'json',
            type:'post',
            data:{
                stock_amount_id:form.find("[name='stock_amount_id']").val(),
                order_ids:JSON.stringify(order_ids)
            },
            'url':'/buyer/order/finishBuy'
        });
        return false;
    });
</script>
{%/block%}

