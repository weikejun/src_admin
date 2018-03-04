{%extends file="admin/framework.tpl"%}

{%block name="content"%}
<div class="container-fluid">
{%if !$buyer%}
    <div class="row-fluid">
        <div class="span12">
            <h1>所有买手申请已经审核完毕</h1>
        </div>
    </div>
{%else%}
    <div class="row-fluid">
        <div class="span12">
            待审核买手信息<b>{%$buyer_count%}</b>条
        </div>
    </div>
    <hr>
    <div class="row-fluid">
        <div class="span9">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
                        <h5>
                            基本信息
                        </h5>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span4">姓名：{%$buyer->mName|escape%}</div>
                    <div class="span4">性别：{%$buyer->mGender|escape%}</div>
                    <div class="span4">职业：{%$buyer->mProfession|escape%}</div>
                </div>
                <div class="row-fluid">
                    <div class="span12">所在地：{%$buyer->mCountry|escape%}，{%$buyer->mProvince|escape%}，{%$buyer->mCity|escape%}，{%$buyer->mAddress|escape%}</div>
                </div>
                <div class="row-fluid">
                    <div class="span6">邮箱：{%$buyer->mEmail|escape%}</div>
                    <div class="span6">手机：{%$buyer->mPhone|escape%}</div>
                </div>
                <div class="row-fluid">
                    <div class="span6">关注品牌：{%implode(",",$buyer->favorBrands())|escape%}</div>
                    <div class="span6">可垫付金额：{%$buyer->mMaxpay|escape%}</div>
                </div>
                <div class="row-fluid">
                    <div class="span12">申请时间：{%$buyer->mUpdateTime|date_format:"%D %H:%M:%S"%}</div>
                </div>
            </div>
        </div>
        <div class="span3">
            <img src="{%$buyer->mHead|escape:quotes%}" />
        </div>
    </div>
    <hr>
    <div class="row-fluid">
        <div class="span12">
            <h5>
                资质信息
            </h5>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span6">
            证件类型：护照
        </div>
        <div class="span6">
            证件号码：{%$buyer->mIdNum|escape%}
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            证件照片：
        </div>
    </div>
    <div class="row-fluid">
        {%foreach $buyer->idPics() as $idPic%}
        <div class="span6"><img class="span6" src="{%$idPic|escape:quotes%}" /></div>
        {%/foreach%}
    </div>
    <hr>
    <div class="row-fluid">
        <div class="span2">
            <form name="pass" action="/cadmin/buyerVerify/pass" method="post">
                <input name="id" value="{%$buyer->mId%}" type="hidden">
                <input type="submit" class="btn blue" value="通过"/>
            </form>
        </div>
        <div class="span6">
            <form name="reject" action="/cadmin/buyerVerify/reject" method="post">
                <input name="id" value="{%$buyer->mId%}" type="hidden">
                <input style="margin:0" placeholder="驳回理由" name="check_words"  type="text" class="span4">
                <input type="submit" class="btn" value="驳回">
            </form>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span1">
            <a href="/cadmin/buyerVerify?last_id={%$buyer->mId%}">下一个</a>
        </div>
        <div class="span1">
            <a href="/cadmin/buyerVerify">第一个</a>
        </div>
    </div>
{%/if%}
</div>
{%/block%}

