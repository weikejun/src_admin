<?php

class Form_TextareaField extends Form_Field{
    public function __construct($config){
        parent::__construct($config);
    }

    public function to_html($is_new){
        $class=$this->config['class'];
        $rows=isset($this->config['rows']) ? 'rows='.$this->config['rows'] : ''; 
        $cols=isset($this->config['cols']) ? 'cols='.$this->config['cols'] : '';
        $html="<div class='control-group'>";
        $html.= "<label class='control-label'>".$this->label."</label>".
            "<div class='controls'>".
            "<textarea class='$class span6' $rows $cols name='{$this->name}'>{$this->value}</textarea>";
        if($this->error){
            $html.="<span class='help-inline'>".$this->error."</span>";
        }
        $html.='</div>';
        $html.='</div>';
        return $html;
    }

    public function foot_js(){
        $js=<<<EOF
<script>
    $('#control-clear').click(function() {
        var pDiv = $(this).parents('div.control-group');
        pDiv.find('textarea').val('');
    });
</script>
EOF;
        return $js;
    }
}
