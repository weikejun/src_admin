<?php
class Model_Project extends Base_Project{
    public static function getItemStatusChoices() {
        return [
            ['closing','已完成'],
            ['ongoing','待完成'],
            ['invalid','失效']
        ];
    }

    public static function getTurnChoices() {
        return [
            ['A轮','A轮'],
            ['B轮','B轮'],
            ['C轮','C轮'],
            ['D轮','D轮'],
            ['E轮','E轮'],
            ['F轮','F轮'],
            ['F轮后','F轮后'],
            ['不适用','不适用']
        ];
    }

    public static function getNewFollowChoices() {
        return [
            ['new','new'],
            ['follow on','follow on'],
            ['其他','其他']
        ];
    }

    public static function getEnterExitTypeChoices() {
        return [
            ['领投','领投'],
            ['跟投','跟投'],
            ['不跟投','不跟投'],
            ['部分退出','部分退出'],
            ['全部退出','全部退出'],
            ['清算退出','清算退出'],
            ['重组','重组'],
            ['上市','上市'],
            ['其他','其他']
        ];
    }
}
