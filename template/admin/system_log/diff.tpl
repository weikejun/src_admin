<style>
.content {
width: 600px;
height: 1800px;
border: 1px solid grey;
}
.diff {
position: absolute;
top: 0px;
left: 20px;
font-size: 14px;
}
.diff .title {
padding:0px;
margin:0px;
height: 30px;
}
.ver_1 {
position:absolute;
top:0px;
left:0px;
}
.ver_2 {
position:absolute;
top:0px;
left:600px;
}
.ver_1 .txt {
top:20px;
}
</style>
<body>
<div class="diff">
    <div class="ver_1">
    <div>版本1：操作人 <b>{%Model_Admin::getNameById($logs1->mOperatorId)%}</b> 操作时间 <b>{%date('Ymd H:i:s', $logs1->mCreateTime)%}</b></div>
    <div class="content txt" readonly>
{%foreach from=$kvs1 item=value key=index%}
{%$index%}: {%$value%}<br />
{%/foreach%}
    </div>
    </div>
    <div class="ver_2">
    <div>版本2：操作人 <b>{%Model_Admin::getNameById($logs2->mOperatorId)%}</b> 操作时间 <b>{%date('Ymd H:i:s', $logs2->mCreateTime)%}</b></div>
    <div class="content txt" readonly>
{%foreach from=$kvs2 item=value key=index%}
{%$index%}: {%$value%}<br />
{%/foreach%}
</div>
    </div>
</div>
</body>
