

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
				<!--li class="start {%if $executeInfo.controllerName=='Index'%} active {%/if%}">
					<a href="/admin/index">
					<i class="icon-home"></i> 
                    <span class="title">首页</span>
					<span class="selected"></span>
					</a>
				</li-->
				<!--li class="start {%if $executeInfo.controllerName=='BuyerVerify'%} active {%/if%}">
					<a href="/cadmin/buyerVerify">
					<i class="icon-home"></i> 
                    <span class="title">买手审核</span>
					<span class="selected"></span>
					</a>
				</li-->
                {%foreach from=$controllers item=c %}
                {%if $c=='IndexNew'%}
				<li class="start {%if $executeInfo.controllerName=='IndexNew'%} active {%/if%}">
					<a href="/admin/indexNew">
					<i class="icon-home"></i> 
                    <span class="title">首页推荐</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='BuyerRank'%}
                <li class="start {%if $executeInfo.controllerName=='BuyerRank'%} active {%/if%}">
                    <a href="/admin/buyerRank">
                        <i class="icon-home"></i>
                        <span class="title">推荐买手</span>
                        <span class="selected"></span>
                    </a>
                </li>
                {%/if%}
                {%if $c=='StockBook'%}
                <li class="start {%if $executeInfo.controllerName=='StockBook'%} active {%/if%}">
                    <a href="/admin/stockBook">
                        <i class="icon-home"></i>
                        <span class="title">图墙推荐</span>
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
                {%if $c=='Buyer'%}
				<li class="start {%if $executeInfo.controllerName=='Buyer'%} active {%/if%}">
					<a href="/admin/buyer">
					<i class="icon-home"></i> 
                    <span class="title">买手管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='Live'%}
				<li class="start {%if $executeInfo.controllerName=='Live'%} active {%/if%}">
					<a href="/admin/live">
					<i class="icon-home"></i> 
                    <span class="title">直播管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='LiveFlow'%}
				<li class="start {%if $executeInfo.controllerName=='LiveFlow'%} active {%/if%}">
					<a href="/admin/liveFlow">
					<i class="icon-home"></i> 
                    <span class="title">直播流</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='LiveStock'%}
                <li class="start {%if $executeInfo.controllerName=='LiveStock'%} active {%/if%}">
                    <a href="/admin/LiveStock">
                        <i class="icon-home"></i>
                        <span class="title">直播商品审核</span>
                        <span class="selected"></span>
                    </a>
                </li>
                {%/if%}
                {%if $c=='Stock'%}
				<li class="start {%if $executeInfo.controllerName=='Stock'%} active {%/if%}">
					<a href="/admin/stock">
					<i class="icon-home"></i> 
                    <span class="title">商品管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='Order'%}
				<li class="start {%if $executeInfo.controllerName=='Order'%} active {%/if%}">
					<a href="/admin/order">
					<i class="icon-home"></i> 
                    <span class="title">订单管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='Payment'%}
                <li class="start {%if $executeInfo.controllerName=='Payment'%} active {%/if%}">
					<a href="/admin/payment">
					<i class="icon-home"></i> 
                    <span class="title">支付管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='Pack'%}
				<li class="start {%if $executeInfo.controllerName=='Pack'%} active {%/if%}">
					<a href="/admin/pack">
					<i class="icon-home"></i> 
                    <span class="title">包裹管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='Storage'%}
				<li class="start {%if $executeInfo.controllerName=='Storage'%} active {%/if%}">
					<a href="/admin/storage">
					<i class="icon-home"></i> 
                    <span class="title">库存管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='StoragePurchasePending'%}
				<li class="start {%if $executeInfo.controllerName=='StoragePurchasePending'%} active {%/if%}">
					<a href="/admin/storagePurchasePending">
					<i class="icon-home"></i> 
                    <span class="title">问题件处理（买手）</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='StorageCsPending'%}
				<li class="start {%if $executeInfo.controllerName=='StorageCsPending'%} active {%/if%}">
					<a href="/admin/storageCsPending">
					<i class="icon-home"></i> 
                    <span class="title">问题件处理（买家）</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='DeliveryAbroad'%}
				<li class="start {%if $executeInfo.controllerName=='DeliveryAbroad'%} active {%/if%}">
					<a href="/admin/deliveryAbroad">
					<i class="icon-home"></i> 
                    <span class="title">买手结算管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='Logistic'%}
				<li class="start {%if $executeInfo.controllerName=='Logistic'%} active {%/if%}">
					<a href="/admin/logistic">
					<i class="icon-home"></i> 
                    <span class="title">国内物流单</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='ExpressPrint'%}
				<li class="start {%if $executeInfo.controllerName=='ExpressPrint'%} active {%/if%}">
					<a href="/admin/expressPrint">
					<i class="icon-home"></i> 
                    <span class="title">发货打印记录</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='UserRefund'%}
				<li class="start {%if $executeInfo.controllerName=='UserRefund'%} active {%/if%}">
					<a href="/admin/userRefund">
					<i class="icon-home"></i> 
                    <span class="title">退款管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='User'%}
				<li class="start {%if $executeInfo.controllerName=='User'%} active {%/if%}">
					<a href="/admin/user">
					<i class="icon-home"></i> 
                    <span class="title">买家管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='Coupon'%}
				<li class="start {%if $executeInfo.controllerName=='Coupon'%} active {%/if%}">
					<a href="/admin/coupon">
					<i class="icon-home"></i> 
                    <span class="title">代金券管理</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='buyerWithdraw'%}
				<li class="start {%if $executeInfo.controllerName=='buyerWithdraw'%} active {%/if%}">
					<a href="/admin/buyerWithdraw">
					<i class="icon-home"></i> 
                    <span class="title">买手提款</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='TaskPush'%}
				<li class="start {%if $executeInfo.controllerName=='TaskPush'%} active {%/if%}">
					<a href="/admin/TaskPush">
					<i class="icon-home"></i> 
                    <span class="title">定时消息推送</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='UserReminder'%}
				<li class="start {%if $executeInfo.controllerName=='UserReminder'%} active {%/if%}">
					<a href="/admin/UserReminder">
					<i class="icon-home"></i> 
                    <span class="title">用户提醒</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='SystemLog'%}
				<li class="start {%if $executeInfo.controllerName=='SystemLog'%} active {%/if%}">
					<a href="/admin/SystemLog">
					<i class="icon-home"></i> 
                    <span class="title">系统日志</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='OrderLog'%}
				<li class="start {%if $executeInfo.controllerName=='OrderLog'%} active {%/if%}">
					<a href="/admin/OrderLog">
					<i class="icon-home"></i> 
                    <span class="title">订单日志</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='EasemobMsg'%}
				<li class="start {%if $executeInfo.controllerName=='EasemobMsg'%} active {%/if%}">
					<a href="/admin/EasemobMsg">
					<i class="icon-home"></i> 
                    <span class="title">聊天消息记录</span>
					<span class="selected"></span>
					</a>
				</li>
                {%/if%}
                {%if $c=='Cs'%}
				<li class="start {%if $executeInfo.controllerName=='Cs'%} active {%/if%}">
					<a href="/admin/cs">
					<i class="icon-home"></i> 
                    <span class="title">客服账号管理</span>
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
