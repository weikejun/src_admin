<?php
class Model_Action extends Base_Action{
    public static function getPermissionId($actionName){
        $action = new self();
        $action = $action->addWhere('name', $actionName)->select();
        return $action ? $action->mPermissionId : null;
    }

    public static function getActionNames($permissionIds){
        $actionNames = array();
        if(count($permissionIds) > 0){
            $permAction = new Model_PermissionAction();
            $actionIds = $permAction->addWhere("permission_id", $permissionIds, 'IN', DBTable::ESCAPE)->findMap('action_id');
            $actions = new self();
            $actions = $actions->addWhere('id', array_keys($actionIds), 'IN', DBTable::ESCAPE)->find();
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
