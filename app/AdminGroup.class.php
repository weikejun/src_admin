<?php
class AdminGroup extends Base_Admin_Group{
    public static function getGroupIdsByAdmin($adminId){
        $admin_group = new AdminGroup();
        $admin_groups = $admin_group->addWhere('admin_id', Admin::getCurrentAdmin()->mId)->find();
        $group_ids = array();
        if($admin_groups){
            foreach($admin_groups as $group){
                $group_id = $group->mGroupId;
                if(!is_null($group_id))
                    array_push($group_ids, $group_id);
            }
        }
        return $group_ids;
    }
}
