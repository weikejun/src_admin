<?php
class Pack extends Base_Pack{
    public static function getAllStatus(){
        return [
            ['not_send','未发货'],
            ['send','已发货'],
        ];
    }

    public function getLogisticById($id) {
        if(empty($id)){
            return null;
        } else{
            $ret = $this->addWhere('id',$id)->select();
            if(!empty($ret)){
                $ret = $ret->getData();
            }
            return $ret;
        }
    }
}
