<?php

class Form_CheckboxField extends Form_Field{
    protected $choices;

    public function value() {
        return json_encode($this->value);
    }

    public function __construct($config){
        array_map(function($fname)use(&$config) {
            $config[$fname] = isset($config[$fname]) ? $config[$fname] : '';
        }, ['checked']);
        parent::__construct($config);
        if(!isset($config['choices'])){
            throw new Exception("field {$this->name} need set choices");
        }
        $this->choices=$config['choices'];
    }

    public function to_html($is_new){
        $html="<div class='control-group'>";
        $html.= "<label class='control-label'>".htmlspecialchars($this->label)."</label>";
        $html.="<div class='controls'>";
        if (!empty($this->value)) {
            $this->value = json_decode($this->value, true);
        } else {
            $this->value = [];
        }
        foreach($this->choices as $choice){
            $value=$choice[0];
            $display=isset($choice[1])?$choice[1]:$value;
            if($this->config['checked']){
                $this->value = $this->config['checked'];
            }else if($is_new && $this->value === []) {
                $this->value = $this->config['default'];
            }
            $checked=in_array($value,$this->value)?"checked='checked'":"";
            if(isset($this->config['readonly']) 
                && $this->config['readonly']) {
                $html.=$checked ? '<input size="16" type="text" value="'.$display.'" readonly /><input size="16" name='.$this->name.' type="hidden" value="'.htmlspecialchars($value).'" readonly />' : '';
            } else {
                $html.="<label class='checkbox'><div class='checkbox'><span><input type='checkbox' $checked name='{$this->name}[]' value='".htmlspecialchars($value)."'></span>$display</div></label>";
            }
        }
        $html .= '<input type="hidden" name="'.$this->name.'[]" value="" />';
        if($this->error){
            $html.="<span class='help-inline'>".$this->error."</span>";
        } else {
            $html.="<span class='help-inline'><span class='tips'>".$this->config['help']."</span></span>";
        }
        $html.="</div>";
        $html.="</div>";
        return $html;
    }

    public function foot_js(){
        $js=<<<EOF
<script>
    $('#control-clear').click(function() {
        var pDiv = $(this).parents('div.control-group');
        pDiv.find(':checkbox').removeAttr('checked');
        pDiv.find(':checkbox').parent().removeClass('checked');
    });
</script>
EOF;
        return $js;
    }
}
