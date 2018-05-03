<?php
class Base_DataStat extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[];
        return $FIELD_LIST;
    }
}
