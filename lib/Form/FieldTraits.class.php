<?php

Trait Form_FieldTraits {
    public function format_value() {
        $value = $this->value();
        $model = WinRequest::getModel('modelData');
        if (is_callable($this->config['field']) && $model) {
            $value = call_user_func($this->config['field'], $model);
            if (!$value && $value !== 0) {
                $value = '无记录';
            }
        }
        return htmlspecialchars($value, ENT_QUOTES);
    }

    public function to_text(){
        if ($this->config['type'] == 'hidden') return;
        $html="<div class='control-group'>";
        $html.= "<label class='control-label'>".htmlspecialchars($this->label)."</label><div class='controls'><div class='raw-text'><i>".$this->format_value()."</i></div></div></div>";
        return $html;
    }

    public function clone_clear() {
        $this->tempValue = $this->value;
        $this->value = null;
    }
}
