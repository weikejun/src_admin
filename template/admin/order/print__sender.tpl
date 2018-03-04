<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <style>
    table,td { 
        font-family: verdana,arial,sans-serif;
        border: solid 1px; 
        border-collapse: collapse;
        padding: 8px;
    }
    h1 { text-align: center; }
    .footer { text-align: right; }
    .break_page { page-break-after:always; } 
    </style>
</head>
<body>
    {%foreach from=$users item=user%}
    <div class="break_page">
    <h1>淘世界发货单</h1>
    <div>发货时间：{%date('Y-m-d H:i:s')%}<br /></div>
    <div>收货人：{%$user[0]['name']%}<br /></div>
    <div>收货电话：{%$user[0]['cellphone']%}<br /></div>
    <div>收货地址：{%$user[0]['province']%} {%$user[0]['city']%} {%$user[0]['addr']%}<br /><br /></div>
    <table>
        <tr><td width=100>订单号</td><td width=100>商品编号</td><td width=300>商品名</td><td width=100>规格</td><td width=50>数量</td><td width=100>备注</td></tr>
        {%foreach from=$user item=order%}
        <tr><td>{%$order['id']%}</td><td>{%$order['stockObj']['id']%}_{%$order['skuObj']['id']%}</td><td>{%$order['stockObj']['name']%}</td><td>{%str_replace("\t", "/", $order['skuObj']['sku_value'])%}</td><td>1</td><td>{%$order['note']%} {%$order['sys_note']%}</td></tr>
        {%/foreach%}
    </table>
    <div class="footer"><br /><br />-- www.aimeizhuyi.com 正品保障，身临其境--</div>
    </div>
    {%/foreach%}
</body>
</html>
