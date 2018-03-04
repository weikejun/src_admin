<?php
class Group extends Base_Group{
    public static function isRoot($groupIds){
        $group = new Group();
        $groups = $group->addWhere("id", $groupIds, 'in')->find();
        if($groups){
            foreach($groups as $group){
                $name = $group->mName;
                if(!is_null($name) && $name == 'root')
                    return true;
            }
        }
        return false;
    }
}
