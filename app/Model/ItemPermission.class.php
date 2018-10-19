<?php
class Model_ItemPermission extends Base_Item_Permission{
    public static function getAdminItem() {
        $admin = Model_Admin::getCurrentAdmin();
        $item = new self;
        $item->addWhere('admin_id', $admin->mId);
        $pers = $item->find();
        $persIds = [
            'project' => [0],
            'company' => [0],
        ];
        foreach($pers as $i => $per) {
            $projectId = $per->getData('project_id');
            $companyId = $per->getData('company_id');
            if (empty($companyId) && empty($projectId)) {
                return ['all' => true];
            }
            if (is_numeric($projectId)) {
                $persIds['project'][] = $projectId;
            } else if (is_numeric($companyId)) {
                $persIds['company'][] = $companyId;
            }
        }
        return $persIds;
    }
}
