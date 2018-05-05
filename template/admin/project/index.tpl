{%extends file="admin/framework.tpl"%}
{%block name="head" append%}
	<link href="/winphp/metronic/media/css/DT_bootstrap.css" rel="stylesheet" type="text/css"/>
{%/block%}
{%block name="content"%}

<div class="row-fluid">
    <div class="span12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box light-grey">
            <!--div class="portlet-title">
                <div class="caption"><i class="icon-globe"></i>{%$controllerText%}</div>
            </div-->
            <div class="portlet-body">
                {%if !$pageAdmin->hide_action_new %}
                <div class="clearfix">
                    <div class="btn-group">
                        <a href="?action=read" id="sample_editable_1_new" class="btn green"><i class="icon-plus"></i>新增 </a>
                    </div>
                </div>
                {%/if%}

                {%include file="admin/project/_list.tpl" inline%}
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>
{%*include file="admin/base/top_msg.html"*%}
{%/block%}

{%block name="footjs" append%}
<script>
    (function(){
        var itemDatas={%json_encode(array_map(create_function('$item','return $item->getData();'),$modelDataList))%};
        $("tbody>tr").dblclick(function(){
            var index=$(this).index();
	    location = $('#read_'+itemDatas[index]['id']).prop('href');
        });
    })();
</script>

    {%include file="admin/base/_filter_js.html" inline%}
    {%include file="admin/base/_multi_actions_js.html" inline%}
{%/block%}
