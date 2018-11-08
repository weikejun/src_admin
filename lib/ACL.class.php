<?php

class ACL{
    public static $controllers = array(
        "交易版块" => [
            "目标企业" => ["Company","CompanyMemo"],
            "交易记录" => ["Project","DealMemo"],
            "Active进度表" => ["ActiveDeal"],
            "投资主体" => ["Entity","EntityRel"],
            "投决意见" => ["DealDecision"],
            "模板邮件" => ["MailSend"],
            "交易记录授权" => ["ItemPermission"],
        ],
        "基金版块" => [
            "基金主体" => ["FundEntity"],
            "基金LP表" => ["FundLp"],
            "合规审查事项" => ["ComplianceMatter"],
            "Checklist清单" => ["Checklist"],
            "基金LP授权" => ["EntityPermission"],
        ],
        "邮件版块" => [
            "提醒邮件策略" => ["MailStrategy"],
            "提醒邮件列表" => ["MailList"],
        ],
        "知识版块" => [
            "知识大类" => ["KnowledgeCate"],
            "知识列表" => ["KnowledgeList"],
            "合同条款" => ["ContractTerm"],
            "合同条款审核" => ["ContractTermCheck"],
        ],
        "权限版块" => [
            "系统用户" => ["Admin"],
            "项目成员" => ["Member"],
            "权限" => ["Action"],
            "权限组" => ["Permission"],
            "角色" => ["Group"],
            "用户角色" => ["AdminGroup"],
            "角色权限组" => ["RolePermission"],
            "权限组配置" => ["PermissionAction"],
            "特工模式" => ["Spy"],
        ],
        "统计版块" => [
            "数据统计" => ["DataStat"],
            "系统日志" => ["SystemLog"],
        ]
    );

    public static function checkPermission($permissionName){
        if(!ACL::checkPermissionResult($permissionName)){
            //throw new ModelAndViewException("no permission", 1, "json:",AppUtils::returnValue(['msg'=>'no permission'], '90001'));
            throw new ModelAndViewException("no permission", 1, ROOT_PATH.'/template/no_permission.tpl');
        }
    }

    public static function checkPermissionResult($actionName){
        $admin_id = Model_Admin::getCurrentAdmin()->mId;
        if(!$admin_id)  return false;

        // 判断用户状态
        $admin = new Model_Admin;
        $admin->addWhere('id', $admin_id);
        $admin = $admin->select();
        if (!$admin || $admin->getData('valid') != 'valid') {
            return false;
        }

        # 用户名为root直接获取权限
        $admin_name = Model_Admin::getCurrentAdmin()->mName;
        $group_ids = Model_AdminGroup::getGroupIdsByAdmin($admin_id);
        if(count($group_ids) == 0)  return false;
        if(Model_Group::isRoot($group_ids)){
            return true;
        }

        # 非root才走权限验证流程
        #$permissionName = Action::getAuth($actionName);
        #if(!$permissionName) return false;
        $permissionIds = Model_PermissionAction::getPermissionIds($actionName);
        if(!$permissionIds) return false;

        if(!Model_Permission::exist($permissionIds))   return false;

        # 首先直接判断用户直接权限
        $adminPermissionIds = Model_RolePermission::getPermissionIdsByAdmin($admin_id);
        #if(Permission::checkPermission($permission_ids, $permissionName)){
        #    return true;
        #}
        foreach($permissionIds as $i => $permissionId) {
            if(in_array($permissionId, $adminPermissionIds)) {
                return true;
            }
        }

        # 用户没有直接权限则查看用户的组权限
        $groupPermissionIds = Model_RolePermission::getPermissionIdsByGroup($group_ids);
        #return Permission::checkPermission($permission_ids, $permissionName);
        foreach($permissionIds as $i => $permissionId) {
            if(in_array($permissionId, $groupPermissionIds)) {
                return true;
            }
        }
        return false;
    } 

    public static function getPermissionControllers(){
        $permissionControllers = array();

        $admin_id = Model_Admin::getCurrentAdmin()->mId;
        if(!$admin_id)  return $permissionControllers;

        $admin_name = Model_Admin::getCurrentAdmin()->mName;
        if($admin_name == 'root'){
            return self::$controllers;
        }

        $group_ids = Model_AdminGroup::getGroupIdsByAdmin($admin_id);
        $permission_ids_by_group = array();
        if(count($group_ids) > 0){
            if(Model_Group::isRoot($group_ids)){
                return self::$controllers;
            }
            $permission_ids_by_group = Model_RolePermission::getPermissionIdsByGroup($group_ids);
        }

        # 根据用户权限和组权限找到用户的所有权限
        $permission_ids_by_admin = Model_RolePermission::getPermissionIdsByAdmin($admin_id);
        $permission_ids = array_unique(array_merge($permission_ids_by_admin, $permission_ids_by_group));

        if(count($permission_ids) == 0) return $permissionControllers;
        #$permissionNames = Permission::getPermissionNames($permission_ids); 
        #$actionNames = Action::getActionNames($permissionNames);
        $actionNames = Model_Action::getActionNames($permission_ids);

        $myControllers = array();
        if(count($actionNames) > 0){
            foreach(self::$controllers as $boardName => $board){
                foreach($board as $navName => $nav) {
                    foreach($nav as $c) {
                        $controllerIndex = strtolower($c).'_index';
                        if(in_array($controllerIndex,$actionNames)){
                            $myControllers[$boardName][$navName][]=$c;
                        }
                    }
                }
            }
        }
        return $myControllers;
    }
}
