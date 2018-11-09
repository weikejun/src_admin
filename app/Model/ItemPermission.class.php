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

    public static function isDealAuth($adminId, $dealId) {
        $items = new self;
        $items->addWhere('admin_id', $adminId);
        $pers = $items->find();
        $deal = new Model_Project;
        $deal->addWhere('id', $dealId);
        $deal->addWhere('status', 'valid');
        $deal->select();
        if (!$deal->mId) {
            return true;
        }
        foreach($pers as $per) {
            if ((empty($per->mProjectId) && empty($per->mCompanyId)) // 全量授权
                || $per->mProjectId == $dealId // 授权到记录
                || $per->mCompanyId == $deal->mCompanyId) { // 授权到企业
                return true;
            }
        }
        return false;
    }
}
