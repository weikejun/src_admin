<?php
class Model_EntityPermission extends Base_EntityPermission{
    public static function getAdminPerm() {
        $admin = Model_Admin::getCurrentAdmin();
        $item = new self;
        $item->addWhere('admin_id', $admin->mId);
        $pers = $item->find();
        $persIds = [
            'entity' => [0],
            'lp' => [0],
        ];
        foreach($pers as $i => $per) {
            $lpId = $per->getData('lp_id');
            $entityId = $per->getData('entity_id');
            if (empty($entityId) && empty($lpId)) {
                return ['all' => true];
            }
            if (is_numeric($lpId)) {
                $persIds['lp'][] = $lpId;
            } else if (is_numeric($entityId)) {
                $persIds['entity'][] = $entityId;
            }
        }
        return $persIds;
    }
}
