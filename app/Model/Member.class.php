<?php
class Model_Member extends Base_Member{
    protected static $_model=null;

    public static function listAll() {
        if (empty(self::$_model)) {
            self::$_model = new self();
            self::$_model = self::$_model->findMap('id');
        }
        return self::$_model;
    }
}
