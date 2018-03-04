<?php
class Permission extends Base_Permission{
    public static function exist($permissionId){
        $permission = new Permission();
        $permission = $permission->addWhere("id", $permissionId)->select();
        return $permission != null;
    }

    public static function checkPermission($permissionIds, $permissionName){
        if(count($permissionIds) == 0)    return false;
        $permission = new Permission();
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
            $permission = new Permission();
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
