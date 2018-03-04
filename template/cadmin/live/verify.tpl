{%extends file="admin/framework.tpl"%}

{%block name="content"%}
<div class="container-fluid">
{%if !$live%}
    <div class="row-fluid">
        <div class="span12">
            <h1>所有直播申请已经审核完毕</h1>
        </div>
    </div>
{%else%}
    <div class="row-fluid">
        <div class="span12">
            待审核直播信息<b>{%$live_count%}</b>条
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
                    <div class="span12"><h5>直播名：</h5>{%$live->mName|escape%}</div>
                </div>
                <div class="row-fluid">
                    <div class="span12"><h5>所在地：</h5>{%$live->mCountry|escape%}，{%$live->mProvince|escape%}，{%$live->mCity|escape%}，{%$live->mAddress|escape%}</div>
                </div>
                <div class="row-fluid">
                    <div class="span12"><h5>直播时间：</h5>{%$live->mStartTime|date_format:"%Y-%m-%d %H:%M:%S"%}-----{%$live->mEndTime|date_format:"%Y-%m-%d %H:%M:%S"%}</div>
                </div>
                <div class="row-fluid">
                    <div class="span12"><h5>直播品牌：</h5>{%json_decode($live->mBrands,true)|implode:","%}</div>
                </div>
                <div class="row-fluid">
                    <div class="span12"><h5>直播介绍：</h5><br>{%$live->mIntro|escape%}</div>
                </div>
                <div class="row-fluid">
                    <div class="span12"><h5>申请时间：</h5>{%$live->mUpdateTime|date_format:"%Y-%m-%d %H:%M:%S"%}</div>
                </div>
                <div class="row-fluid">
                    <div class="span12"><h5>图片：</h5><br>
                    {%foreach json_decode($live->mImgs,true) as $img%}
                        <img src="{%$img%}"/>
                    {%/foreach%}
                    </div>
                </div>
            </div>
        </div>
        <div class="span3">
        </div>
    </div>
    <hr>
    <div class="row-fluid">
        <div class="span2">
            <form name="pass" action="/cadmin/liveVerify/pass" method="post">
                <input name="id" value="{%$live->mId%}" type="hidden">
                <input type="submit" class="btn blue" value="通过"/>
            </form>
        </div>
        <div class="span6">
            <form name="reject" action="/cadmin/liveVerify/reject" method="post">
                <input name="id" value="{%$live->mId%}" type="hidden">
                <input style="margin:0" placeholder="驳回理由" name="check_words"  type="text" class="span4">
                <input type="submit" class="btn" value="驳回">
            </form>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span1">
            <a href="/cadmin/liveVerify?last_id={%$live->mId%}">下一个</a>
        </div>
        <div class="span1">
            <a href="/cadmin/liveVerify">第一个</a>
        </div>
    </div>
{%/if%}
</div>
{%/block%}


