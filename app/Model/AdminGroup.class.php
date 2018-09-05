<?php
class Model_AdminGroup extends Base_Admin_Group{
    public static function getGroupIdsByAdmin($adminId){
        $admin_group = new self();
        $admin_groups = $admin_group->addWhere('admin_id', Model_Admin::getCurrentAdmin()->mId)->find();
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

    public static function isCurrentAdminRoot() {
        $admin = Model_Admin::getCurrentAdmin();
        $groupIds = self::getGroupIdsByAdmin($admin->mId);
        return Model_Group::isRoot($groupIds);
    }
}
