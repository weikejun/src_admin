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

    public static function getEmailById($id) {
        $list = self::listAll();
        return isset($list[$id]) ? $list[$id]->mMail : $id;
    }

    public static function getNameById($id) {
        $list = self::listAll();
        return isset($list[$id]) ? $list[$id]->mName : $id;
    }

    public static function getIdByEmail($mail) {
        $list = self::listAll();
        foreach($list as $id => $member) {
            if ($member->mMail == $mail) {
                return $id;
            }
        }
        return null;
    }
}
