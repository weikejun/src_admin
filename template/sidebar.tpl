

		<div class="page-sidebar nav-collapse collapse" style="position:fixed">
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
                        <span class="title">项目信息</span>
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
                <li class="start {%if $executeInfo.controllerName=='Company'%} active {%/if%}">
                    <a href="/admin/company">
                        <i class="icon-home"></i>
                        <span class="title">目标公司</span>
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
                {%if $c=='EntityRel'%}
                <li class="start {%if $executeInfo.controllerName=='EntityRel'%} active {%/if%}">
                    <a href="/admin/entityRel">
                        <i class="icon-home"></i>
                        <span class="title">主体关系</span>
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
                    <span class="title">权限管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='RolePermission'%}
				<li class="start {%if $executeInfo.controllerName=='RolePermission'%} active {%/if%}">
					<a href="/admin/RolePermission">
					<i class="icon-home"></i> 
                    <span class="title">角色权限管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='Group'%}
				<li class="start {%if $executeInfo.controllerName=='Group'%} active {%/if%}">
					<a href="/admin/Group">
					<i class="icon-home"></i> 
                    <span class="title">角色管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='AdminGroup'%}
				<li class="start {%if $executeInfo.controllerName=='AdminGroup'%} active {%/if%}">
					<a href="/admin/AdminGroup">
					<i class="icon-home"></i> 
                    <span class="title">用户角色管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='Action'%}
				<li class="start {%if $executeInfo.controllerName=='Action'%} active {%/if%}">
					<a href="/admin/Action">
					<i class="icon-home"></i> 
                    <span class="title">访问权限管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%/foreach%}

{%*
				<li class="">
					<a href="javascript:;">
					<i class="icon-cogs"></i> 
					<span class="title">旅行社管理</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="/RemoteTravelAgency">
                                发团社
                            </a>
						</li>
						<li>
							<a href="/LocalTravelAgency">
                                地接社
					            <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li >
                                    <a href="/TravelTeam">
                                        添加
                                    </a>
                                </li>
                                <li >
                                    <a href="/Tourist">
                                        修改
                                    </a>
                                </li>
                                <li >
                                    <a href="/Tourist">
                                        查询
                                    </a>
                                </li>
                            </ul>
						</li>
					</ul>
				</li>
				<li class="">
					<a href="javascript:;">
					<i class="icon-bookmark-empty"></i> 
					<span class="title">旅行团管理</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li >
							<a href="/TravelTeam">
                                旅行团
                            </a>
						</li>
						<li >
							<a href="/Tourist">
                                全部游客
                            </a>
						</li>
					</ul>
				</li>
				<li class="">
					<a href="/TravelNode">
					<i class="icon-table"></i> 
					<span class="title">景点管理（收费项目）</span>
					</a>
				</li>
				<li class="">
					<a href="javascript:;">
					<i class="icon-briefcase"></i> 
					<span class="title">面向旅行社界面(对外)</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li >
							<a href="/Agency/Login">
                                旅行社登录
                            </a>
						</li>
						<li >
							<a href="/Agency/TravelTeam">
                                旅行团管理（新建旅行团，查看历史）
                            </a>
						</li>
						<li >
							<a href="/Agency/Tourist">
                                修改自己的信息
                            </a>
						</li>
					</ul>
				</li>
				<li class="">
					<a href="javascript:;">
					<i class="icon-gift"></i> 
					<span class="title">b2c用户管理</span>
					</a>
				</li>
				<li class="">
					<a href="javascript:;">
					<i class="icon-gift"></i> 
					<span class="title">b2c商品种类管理</span>
					</a>
				</li>
				<li>
					<a class="active" href="javascript:;">
					<i class="icon-sitemap"></i> 
					<span class="title">b2c订单列表</span>
					</a>
				</li>
				<li class="last">
					<a class="active" href="javascript:;">
					<i class="icon-sitemap"></i> 
					<span class="title">b2c电子票管理</span>
					</a>
				</li>
*%}
			</ul>

			<!-- END SIDEBAR MENU -->
		</div>
