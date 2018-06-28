<?php

class Form_RawTextField extends Form_Field{
    public function __construct($config){
        parent::__construct($config);
    }

    public function to_html($is_new){
        $value = '等待计算……';
        $model = WinRequest::getModel('modelData');
        if (is_callable($this->config['field']) && $model) {
            $value = call_user_func($this->config['field'], $model);
            if (!$value && $value !== 0) {
                $value = '无记录';
            }
        }
        $class=$this->config['class'];
        $html="<div class='control-group'>";
        $html.= "<label class='control-label'>".htmlspecialchars($this->label)."</label>".
            "<div class='controls'><div class='raw-text'><i>$value</i></div>";
        if($this->error){
            $html.="<span class='help-inline'>".$this->error."</span>";
        } else {
            $html.="<span class='help-inline'><span class='tips'>".$this->config['help']."</span></span>";
        }
        $html.='</div>';
        $html.='</div>';
        return $html;
    }

    public function head_css() {
        $css = <<<EOF
<style>
.control-group .raw-text {display:inline-block;padding-top:7px;};
</style>
EOF;
        return $css;
    }

    public function to_text() {
        return $this->to_html(false);
    }
}
