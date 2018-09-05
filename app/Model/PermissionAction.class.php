<?php
class Model_PermissionAction extends Base_Permission_Action{
    public static function getPermissionIds($actionName){
        $action = new Model_Action();
        $action = $action->addWhere('name', $actionName)->select();
        $perIds = [];
        if ($action) {
            $pers = new Model_PermissionAction;
            $pers->addWhere('action_id', $action->getData('id'));
            $pers = $pers->find();
            foreach($pers as $i => $per) {
                $perIds[] = $per->getData('permission_id');
            }
        }
        return $perIds;
    }

    public static function getActionNames($permissionIds){
        $actionNames = array();
        if(count($permissionIds) > 0){
            $action = new self();
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
