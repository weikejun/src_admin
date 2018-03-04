<?php
class RolePermission extends Base_Role_Permission{
    public static function getPermissionIdsByAdmin($adminId){
        $admin_permission = new RolePermission();
        $admin_permissions = $admin_permission->addWhere('admin_id', $adminId)->find();
        $permission_ids = array();
        if($admin_permissions){
            foreach($admin_permissions as $admin_permission){
                $permission_id = $admin_permission->mPermissionId;
                if(!is_null($permission_id))
                    array_push($permission_ids, $permission_id);
            }
        }
        return $permission_ids;
    }

    public static function getPermissionIdsByGroup($groupIds){
        $group_permission = new RolePermission();
        $group_permissions = $group_permission->addWhere("group_id", $groupIds, 'in')->find();
        $permission_ids = array();
        if($group_permissions){
            foreach($group_permissions as $group_permission){
                $permission_id = $group_permission->mPermissionId;
                if(!is_null($permission_id))
                    array_push($permission_ids, $permission_id);
            }
        }
        return $permission_ids;
    }
}
