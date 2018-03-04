<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <style>
    @media print{
    * { margin: 0; padding 0; border 0; }
    .pr_tck { font-weight: bold; font-size: 16px; position: relative; text-align: left; border: 0; page-break-after:always; } 
    .sdr_date, .sdr_type, .sdr_desc, .sdr_pcode, .sdr_store, .sdr_order, .sdr_name, .sdr_from, .sdr_co, .sdr_addr, .sdr_addr2, .sdr_tel, .sdr_sign, .rcvr_pcode, .rcvr_user, .rcvr_name, .rcvr_to, .rcvr_co, .rcvr_addr, .rcvr_stock, .rcvr_tel { position: absolute; }
    .sdr_name { left: 104px; top: 88px; }
    .sdr_order { left: 277px; top: 58px; }
    .sdr_from { left: 250px; top: 88px; }
    .sdr_store { left: 104px; top: 108px; }
    .sdr_addr { left: 104px; top: 128px; }
    .sdr_tel { left: 138px; top: 203px; }
    .sdr_pcode { left: 335px; top: 203px; }
    .sdr_type { left: 131px; top: 238px; }
    .sdr_desc { left: 104px; top: 261px; }
    .rcvr_name { left: 466px; top: 88px; }
    .rcvr_addr { left: 500px; top: 128px; }
    .rcvr_tel { left: 500px; top: 203px; }
    .rcvr_pcode { left: 719px; top: 203px; }
    .rcvr_to { left: 654px; top: 31px; }
    }
    </style>
</head>
<body>
    {%foreach from=$users item=user%}
    <div class="pr_tck">
        <div class="sdr_store">淘世界</div>
        <div class="sdr_order">订单号: {%foreach from=$user item=order%}{%$order['id']%} {%/foreach%}</div> 
        <div class="sdr_name">陈丹丹</div>
        <div class="sdr_from">北京</div>
        <div class="sdr_tel">18600616816</div>
        <div class="sdr_addr">北京市朝阳区爱美主义小店</div>    
        <div class="sdr_pcode">100029</div>
        <div class="sdr_type">√</div>
        <div class="sdr_desc">{%foreach from=$user item=order%}{%$order['stockObj']['name']%}({%str_replace("\t", "/", $order['skuObj']['sku_value'])%}) {%/foreach%}</div>
        <div class="rcvr_name">{%$user[0]['name']%}</div>
        <div class="rcvr_tel">{%$user[0]['cellphone']%}</div> 
        <div class="rcvr_addr">{%$user[0]['province']%} {%$user[0]['city']%} {%$user[0]['addr']%}</div>
        <div class="rcvr_pcode">{%$user[0]['postcode']%}</div>
        <div class="rcvr_to">{%$user[0]['province']%} {%$user[0]['city']%}</div>
    </div>
    {%/foreach%}
</body>
</html>
