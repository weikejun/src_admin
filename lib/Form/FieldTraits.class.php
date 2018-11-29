<?php

Trait Form_FieldTraits {
    public $tempValue;
    public function format_value() {
        $value = $this->value();
        $model = WinRequest::getModel('modelData');
        if (isset($this->config['field']) && is_callable($this->config['field']) && $model) {
            $value = call_user_func($this->config['field'], $model);
            if (!$value && $value !== 0) {
                $value = '无记录';
            }
        }
        return "<pre>".$value."</pre>";
    }

    public function to_text(){
        if ($this->config['type'] == 'hidden') return;
        $html="<div class='control-group'>";
        $html.= "<label class='control-label'>".htmlspecialchars($this->label)."</label><div class='controls'><div class='raw-text'>".$this->format_value()."</div></div></div>";
        return $html;
    }

    public function clone_clear() {
        $this->tempValue = $this->value;
        $this->value = null;
    }
}
