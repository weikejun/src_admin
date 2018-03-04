<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<h1>订单统计</h1>
<table>
<tr>
    <th>关键指标</th>
    <th>数值</th>
</tr>
{%foreach $statistics as $key=>$value%}
<tr>
    <td>{%$key|escape%}</td>
    <td>{%$value|escape%}</td>
</tr>
{%/foreach%}

</table>


<h1>分直播统计</h1>
<table>
<tr>
    <th>直播id</th>
    <th>直播名字</th>
    <th>销售额</th>
</tr>
{%foreach $lives as $live%}
<tr>
    <td>{%$live->mId|escape%}</td>
    <td>{%$live->mName|escape%}</td>
    <td>{%$lives_sale[$live->mId]|escape%}</td>
</tr>
{%/foreach%}

</table>
</body>
</html>


