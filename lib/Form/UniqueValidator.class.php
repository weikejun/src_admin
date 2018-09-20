<?php

class Form_UniqueValidator{
    public function __construct($model,$field_name){
        $this->model=$model;
        $this->field_name=$field_name;
    }
    public function validate($values){
        if ($values[$this->field_name]) {
            $this->model->addWhere($this->field_name, $values[$this->field_name]);
            if (isset($values['id']) && $values['id'] > 0) {
                $this->model->addWhere('id', $values['id'], '<>');
            }
            if ($this->model->count()) {
                $this->error = '"'.$values[$this->field_name].'"已存在，请重新输入';
                return false;
            }
        }
        return true;
    }
}
