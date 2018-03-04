<?php

class TaskPush extends Base_Task_Push{
    public static function getAllStatus(){
        return [
            ['0','未处理'],
            ['1','处理中'],
            ['2','已完成'],
            ['3','已取消'],
        ];
    }

    public static function getAllType(){
        return [
            ['0','全部'],
            ['1','指定'],
        ];
    }
}
