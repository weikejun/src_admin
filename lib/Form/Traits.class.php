<?php

Trait Form_Traits {
    public static function createSqlFields() {
        // TODO: for quick create .sql
        foreach(self::getFieldsMap() as $field) {
            if ($field['type'] == 'seperator' || $field['type'] == 'rawText') {
                continue;
            }
            $sqlFieldType = 'varchar(32)';
            if ($field['type'] == 'textarea' || $field['type'] == 'selectInput') {
                $sqlFieldType = 'varchar(512)';
            } elseif ($field['type'] == 'date' || $field['type'] == 'datetime') {
                $sqlFieldType = 'int(11)';
            }
            file_put_contents("/tmp/".str_replace('Form_', '', __class__).".fields.sql", "`".$field['name']."` $sqlFieldType DEFAULT ".($field['default']?("'".$field['default']."'"):'NULL')." COMMENT '".$field['label']."', \n", FILE_APPEND);
        }
    }

    public static function getFieldViewName($fieldName) {
        static $nameMap;
        if (!$nameMap) {
            $nameMap = [];
            foreach(self::getFieldsMap() as $field) {
                $nameMap[$field['name']] = $field['label'];
            }
        }
        return $nameMap[$fieldName]?$nameMap[$fieldName]:$fieldName;
    }

    public static function getFieldNameByView($fieldView) {
        static $viewMap;
        if (!$viewMap) {
            $viewMap = [];
            foreach(self::getFieldsMap() as $field) {
                $viewMap[$field['label']] = $field['name'];
            }
        }
        return $viewMap[$fieldView]?$viewMap[$fieldView]:null;
    }
}
