<?php
class Model_DealDecision extends Base_DealDecision{
    public function signData() {
        $data = [
            'id='.$this->mId,
            'project_id='.$this->mProjectId,
            'partner='.$this->mPartner,
            'create_time='.$this->mCreateTime,
            '_salted=#$#KJ:*(!,asf',
        ];
        sort($data);
        $dataStr = implode('&', $data);
        return md5($dataStr);
    }

    public static function checkSign($id, $sign) {
        $model = new self();
        $model->mId = $id;
        $model->select();
        return $sign == $model->signData();
    }

    public static function getDecisionChoices() {
        return [
            ['同意','同意'],
            ['不同意','不同意'],
            ['弃权','弃权'],
        ];
    }
}
