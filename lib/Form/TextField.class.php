<?php

class Form_TextField extends Form_Field{
    public function __construct($config){
        array_map(function($fname)use(&$config) {
            $config[$fname] = isset($config[$fname]) ? $config[$fname] : '';
        }, ['readonly','help','default','placeholder','class','labelClass']);
        parent::__construct($config);
    }

    public function to_html($is_new){
        $class=$this->config['class'];
        $html="<div class='control-group'>";
        $value=htmlspecialchars($this->value, ENT_QUOTES);
        $html.= "<label class='control-label ".$this->config['labelClass']."'>".$this->label."</label>".
            "<div class='controls'>".
            "<input class='$class span6' ".($this->config['readonly']&&($this->config['default']||!$is_new&&strlen(trim($value))!=0)?'readonly':"")." type='text' name='{$this->name}'  value='".$value."' autocomplete=off placeholder='".(isset($this->config['placeholder'])?$this->config['placeholder']:'')."'>";
        if($this->error){
            $html.="<span class='help-inline'>".$this->error."</span>";
        } else {
            $html.="<span class='help-inline'><span class='tips'>".$this->config['help']."</span></span>";
        }
        $html.='</div>';
        $html.='</div>';
        return $html;
    }
}
