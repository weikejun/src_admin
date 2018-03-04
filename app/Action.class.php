<?php
class Action extends Base_Action{
    public static function getPermissionId($actionName){
        $action = new Action();
        $action = $action->addWhere('name', $actionName)->select();
        return $action ? $action->mPermissionId : null;
    }

    public static function getActionNames($permissionIds){
        $actionNames = array();
        if(count($permissionIds) > 0){
            $action = new Action();
            $actions = $action->addWhere("permission_id", $permissionIds, 'in')->find();
            if($actions){ 
                foreach($actions as $action){
                    $name = $action->mName;
                    array_push($actionNames, $name);
                }
            }
        }
        return $actionNames;
    }
}
