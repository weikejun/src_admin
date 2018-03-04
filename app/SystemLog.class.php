<?php
class SystemLog extends Base_System_Log{
    public static function add($info){
        $log=new self();
        $log->setData($info);
        $log->mCreateTime=time();
        $log->save();
        return $log;
    }
}
