<?php
class Model_EntityRel extends Base_EntityRel{
    protected static $_model=null;

    public static function listAll() {
        if (empty(self::$_model)) {
            self::$_model = new self();
            self::$_model = self::$_model->findMap('id');
        }
        return self::$_model;
    }

    public static function getAllSubs($pid, $level) {
        $subIds = [];
        if ($level > 5) {
            return $subIds;
        }
        $rels = self::listAll();
        foreach($rels as $id => $rel) {
            if ($rel->getData('parent_id') == $pid) {
                $subIds = array_merge($subIds, [$rel->getData('sub_id')], self::getAllSubs($rel->getData('sub_id'), $level++));
            }
        }
        return $subIds;
    }
}
