<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <style>
    @media print {
    * { margin: 0; padding 0; border 0; }
    .pr_tck { font-weight: bold; font-size: 16px; position: relative; text-align: left; border: 0; page-break-after:always; } 
    .sdr_date, .sdr_store, .sdr_order, .sdr_name, .sdr_from, .sdr_co, .sdr_addr, .sdr_addr2, .sdr_tel, .sdr_sign, .rcvr_user, .rcvr_name, .rcvr_to, .rcvr_co, .rcvr_addr, .rcvr_stock, .rcvr_tel, .sdr_src, .sdr_pay, .sdr_account, .sdr_reciever, .sdr_sign { position: absolute; }
    .sdr_date { left: 180px; top: 37px; }
    .sdr_order { left: 101px; top: 125px; }
    .sdr_name { left: 307px; top: 154px; }
    .sdr_store { left: 101px; top: 154px; }
    .sdr_tel { left: 180px; top: 234px; }
    .sdr_addr { left: 101px; top: 178px; }
    .sdr_src { left: 565px; top: 82px; }
    .sdr_pay { left: 565px; top: 125px; }
    .sdr_account { left: 565px; top: 162px; }
    .sdr_reciever { left: 565px; top: 262px; }
    .sdr_sign { left: 670px; top: 331px; }
    .rcvr_user { left: 173px; top: 246px; }
    .rcvr_name { left: 307px; top: 307px; }
    .rcvr_addr { left: 101px; top: 331px; }
    .rcvr_stock { left: 69px; top: 459px; }
    .rcvr_tel { left: 180px; top: 384px; }
    body { margin: 0; padding: 0 }
    }
    </style>
</head>
<body>
    {%foreach from=$users item=user%}
    <div class="pr_tck">
        <div class="sdr_date">{%date('Y-m-d H:i:s')%}</div>
        <div class="sdr_store">淘世界</div>
        <div class="sdr_order">订单号: {%foreach from=$user item=order%}{%$order['id']%} {%/foreach%}</div> 
        <div class="sdr_name">陈丹丹</div>
        <div class="sdr_tel">18600616816</div>
        <div class="sdr_addr">北京市朝阳区爱美主义小店</div>    
        <div class="sdr_src">010AE</div>    
        <div class="sdr_pay">X</div>    
        <div class="sdr_account">0&nbsp;1&nbsp;0&nbsp;3&nbsp;4&nbsp;4&nbsp;3&nbsp;7&nbsp;3&nbsp;5</div>    
        <div class="sdr_reciever">1&nbsp;7&nbsp;1&nbsp;6&nbsp;5&nbsp;0</div>    
        <div class="sdr_sign">陈丹丹</div>    
        <div class="rcvr_user"></div>
        <div class="rcvr_name">{%$user[0]['name']%}</div>
        <div class="rcvr_tel">{%$user[0]['cellphone']%}</div> 
        <div class="rcvr_addr">{%$user[0]['province']%} {%$user[0]['city']%} {%$user[0]['addr']%}</div>
        <div class="rcvr_stock">{%foreach from=$user item=order%}{%$order['stockObj']['name']%}({%str_replace("\t", "/", $order['skuObj']['sku_value'])%}) {%/foreach%}</div>
    </div>
    {%/foreach%}
</body>
</html>
