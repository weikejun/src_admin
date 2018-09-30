

		<div class="page-sidebar nav-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->        
			<ul class="page-sidebar-menu">
				<li>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler hidden-phone"></div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				<li>
					<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                    <!--
					<form class="sidebar-search">
						<div class="input-box">
							<a href="javascript:;" class="remove"></a>
							<input type="text" placeholder="Search..." />
							<input type="button" class="submit" value=" " />
						</div>
					</form>
                    -->
					<!-- END RESPONSIVE QUICK SEARCH FORM -->
				</li>
                {%foreach from=$controllers item=c %}
                {%if $c=='Project'%}
                <li class="start {%if $executeInfo.controllerName=='Project'%} active {%/if%}">
                    <a href="/admin/project">
                        <i class="icon-home"></i>
                        <span class="title">交易记录</span>
                        <span class="selected"></span>
                    </a>
                </li>
                {%/if%}
                {%if $c=='ActiveDeal'%}
                <li class="start {%if $executeInfo.controllerName=='ActiveDeal'%} active {%/if%}">
                    <a href="/admin/ActiveDeal">
                        <i class="icon-home"></i>
                        <span class="title">Active项目进度表</span>
                        <span class="selected"></span>
                    </a>
                </li>
                {%/if%}
                {%if $c=='DealDecision'%}
                <li class="start {%if $executeInfo.controllerName=='DealDecision'%} active {%/if%}">
                    <a href="/admin/DealDecision">
                        <i class="icon-home"></i>
                        <span class="title">投决意见</span>
                        <span class="selected"></span>
                    </a>
                </li>
                {%/if%}
                {%if $c=='MailSend'%}
                <li class="start {%if $executeInfo.controllerName=='MailSend'%} active {%/if%}">
                    <a href="/admin/MailSend">
                        <i class="icon-home"></i>
                        <span class="title">邮件模板</span>
                        <span class="selected"></span>
                    </a>
                </li>
                {%/if%}
                {%if $c=='DataStat'%}
                <li class="start {%if $executeInfo.controllerName=='DataStat'%} active {%/if%}">
                    <a href="/admin/dataStat">
                        <i class="icon-home"></i>
                        <span class="title">数据统计</span>
                        <span class="selected"></span>
                    </a>
                </li>
                {%/if%}
                {%if $c=='Company'%}
                <li class="start {%if strpos($executeInfo.controllerName, 'Company') === 0%} active {%/if%}">
                    <a href="/admin/company">
                        <i class="icon-home"></i>
                        <span class="title">目标企业</span>
                        <span class="selected"></span>
                    </a>
                </li>
                {%/if%}
                {%if $c=='Entity'%}
                <li class="start {%if $executeInfo.controllerName=='Entity'%} active {%/if%}">
                    <a href="/admin/entity">
                        <i class="icon-home"></i>
                        <span class="title">投资主体</span>
                        <span class="selected"></span>
                    </a>
                </li>
                {%/if%}
                <!--
                {%if $c=='EntityRel'%}
                <li class="start {%if $executeInfo.controllerName=='EntityRel'%} active {%/if%}">
                    <a href="/admin/entityRel">
                        <i class="icon-home"></i>
                        <span class="title">投资主体关系</span>
                        <span class="selected"></span>
                    </a>
                </li>
                {%/if%}
                {%if $c=='Payment'%}
                <li class="start {%if $executeInfo.controllerName=='Payment'%} active {%/if%}">
                    <a href="/admin/payment">
                        <i class="icon-home"></i>
                        <span class="title">付款记录</span>
                        <span class="selected"></span>
                    </a>
                </li>
                {%/if%}
                -->
                {%if $c=='Admin'%}
				<li class="start {%if $executeInfo.controllerName=='Admin'%} active {%/if%}">
					<a href="/admin/admin">
					<i class="icon-home"></i> 
                    <span class="title">系统用户</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='Permission'%}
				<li class="start {%if $executeInfo.controllerName=='Permission'%} active {%/if%}">
					<a href="/admin/Permission">
					<i class="icon-home"></i> 
                    <span class="title">权限组</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='RolePermission'%}
				<li class="start {%if $executeInfo.controllerName=='RolePermission'%} active {%/if%}">
					<a href="/admin/RolePermission">
					<i class="icon-home"></i> 
                    <span class="title">角色权限组</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='Group'%}
				<li class="start {%if $executeInfo.controllerName=='Group'%} active {%/if%}">
					<a href="/admin/Group">
					<i class="icon-home"></i> 
                    <span class="title">角色</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='AdminGroup'%}
				<li class="start {%if $executeInfo.controllerName=='AdminGroup'%} active {%/if%}">
					<a href="/admin/AdminGroup">
					<i class="icon-home"></i> 
                    <span class="title">用户角色</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='Action'%}
				<li class="start {%if $executeInfo.controllerName=='Action'%} active {%/if%}">
					<a href="/admin/Action">
					<i class="icon-home"></i> 
                    <span class="title">权限</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='PermissionAction'%}
				<li class="start {%if $executeInfo.controllerName=='PermissionAction'%} active {%/if%}">
					<a href="/admin/PermissionAction">
					<i class="icon-home"></i> 
                    <span class="title">权限组配置</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='ItemPermission'%}
				<li class="start {%if $executeInfo.controllerName=='ItemPermission'%} active {%/if%}">
					<a href="/admin/ItemPermission">
					<i class="icon-home"></i> 
                    <span class="title">交易记录授权</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='SystemLog'%}
				<li class="start {%if $executeInfo.controllerName=='SystemLog'%} active {%/if%}">
					<a href="/admin/systemLog">
					<i class="icon-home"></i> 
                    <span class="title">系统日志</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%/foreach%}
			</ul>

			<!-- END SIDEBAR MENU -->
		</div>
