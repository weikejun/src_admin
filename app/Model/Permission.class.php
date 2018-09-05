<?php
class Model_Permission extends Base_Permission{
    public static function exist($permissionId){
        if (!is_array($permissionId)) {
            $permissionId = array($permissionId);
        }
        $permission = new self();
        $permission = $permission->addWhere("id", $permissionId, 'IN', DBTable::ESCAPE)->count();
        return $permission;
    }

    public static function checkPermission($permissionIds, $permissionName){
        if(count($permissionIds) == 0)    return false;
        $permission = new self();
        $permissions = $permission->addWhere("id", $permissionIds, 'in')->find();
        if(!$permissions)   return false;
        foreach($permissions as $permission){
            $name = $permission->mName;
            if(!is_null($name) && $name == $permissionName){
                return true;
            }
        }
        return false;
    }

    public static function getPermissionNames($permissionIds){
        $permissionNames = array();
        if(count($permissionIds) > 0){
            $permission = new self();
            $permissions = $permission->addWhere("id", $permissionIds, 'in')->find();
            if($permissions){ 
                foreach($permissions as $permission){
                    $name = $permission->mName;
                    array_push($permissionNames, $name);
                }
            }
        }
        return $permissionNames;
    }
}
