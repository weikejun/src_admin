{%extends file="buyer/framework.tpl"%}
{%block name="body"%}
<div id="payedOrders">
</div>
<div id="waitPayOrders">
</div>
{%/block%}

{%block name="foot"%}
<script>
    function draw(id,data){
        var container=$("#"+id);
        container.empty();
        
        var html="";
        $.each(data,function(i,order){
            html+="<tr>";
            html+="<td><input type='checkbox' class='order_id' value='"+order.id+"' /></td>";
            html+="<td>"+order.id+"</td>";
            html+="<td>"+order.name+" "+order.sku_value+"</td>";
            html+="<td>"+order.num+"</td>";
            html+="</tr>";
        });
        container.append(
            "<form id='"+id+"_form'>"+
            "<table>"+html+"</table></form>");
    }
    $.ajax({
        url:'/buyer/order/packList',
        type:"get",
        data:{live_id:{%$smarty.get.live_id%}},
        dataType:'json',
        success:function(data){
            if(!data||data.errno!=0){
                $("#payedOrders").html(data);
                return ;
            }
            draw("payedOrders",data.rst.payedOrders);
            $("form:last").prepend("<div>已补款订单</div>").append("<input name='name' type='text' placeholder='包裹名称'><br><input type=submit value='打包'>"),
            draw("waitPayOrders",data.rst.waitPayOrders);
            $("form:last").prepend("<div>等待补款订单</div>").append("<input id='callPay' type=button value='催款'><br><input id='return' type=button value='退货'>");
        }
    });
    $("body").delegate('#payedOrders_form','submit',function(e){
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
                live_id:'{%$smarty.get.live_id%}',
                name:form.find("[name='name']").val(),
                order_ids:JSON.stringify(order_ids)
            },
            'url':'/buyer/order/finishPack'
        });
        return false;
    });
    $("body").delegate('#callPay','click',function(e){
        var form=$(this).parents("form");
        var order_ids=form.find('.order_id:checked').map(function(){
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
                live_id:'{%$smarty.get.live_id%}',
                order_ids:JSON.stringify(order_ids)
            },
            'url':'/buyer/order/callPay'
        });
        return false;
    });
    $("body").delegate('#return','click',function(e){
        var form=$(this).parents("form");
        var order_ids=form.find('.order_id:checked').map(function(){
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
                live_id:'{%$smarty.get.live_id%}',
                order_ids:JSON.stringify(order_ids)
            },
            'url':'/buyer/order/return'
        });
        return false;
    });
</script>
{%/block%}


