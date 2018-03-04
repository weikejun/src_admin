<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>
<body>
<h1>渠道下载数据</h1>
<table>
<tr>
    <th>来源</th>
    <th>下载页pv</th>
    <th>下载页@iPhone pv</th>
    <th>下载页@Android pv</th>
    <th>下载页其它客户端 pv</th>
    <th>下载点击量</th>
    <th>下载点击量@iPhone</th>
    <th>下载点击量@Android</th>
    <th>下载点击其它客户端 pv</th>
</tr>
{%foreach $pv as $source=>$value%}
<tr>
    <td>{%$source%}</td>
    <td>{%$pv[$source]|default:0%}</td>
    <td>{%$pv_ios[$source]|default:0%}</td>
    <td>{%$pv_android[$source]|default:0%}</td>
    <td>{%intval($pv[$source])-intval($pv_ios[$source])-intval($pv_android[$source])%}</td>
    <td>{%$click[$source]|default:0%}</td>
    <td>{%$click_ios[$source]|default:0%}</td>
    <td>{%$click_android[$source]|default:0%}</td>
    <td>{%intval($click[$source])-intval($click_ios[$source])-intval($click_android[$source])%}</td>
</tr>
{%/foreach%}

</table>
</body>
</html>

