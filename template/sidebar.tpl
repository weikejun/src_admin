

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
                {%foreach from=$controllers item=board key=boardName%}
                {%$curCtrl = $executeInfo.controllerName%}
                {%$selected = false%}
                {%foreach from=$board item=nav key=navName%}
                {%if in_array($curCtrl, $nav) %}{%$selected = true%}{%break%}{%/if%}
                {%/foreach%}
                <li class="start {%if $selected%}active{%/if%}">
                    <a>
                        <i class="icon-list"></i>
                        <span class="title">{%$boardName%}</span>
                        <span class="selected"></span>
                    </a>
                    <ul class="sub-menu">
                    {%foreach from=$board item=nav key=navName%}
                        <li class="start {%if in_array($curCtrl, $nav) %} active {%/if%}">
                            <a href="/admin/{%$nav[0]%}">
                                <i class="{%if in_array($curCtrl, $nav) %}icon-folder-open{%else%}icon-folder-close{%/if%}"></i>
                                <span class="title">{%$navName%}</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                    {%/foreach%}
                    </ul>
                </li>
                {%/foreach%}
			</ul>

			<!-- END SIDEBAR MENU -->
		</div>
