<?php

class Form_SelectInputField extends Form_Field{
    public function __construct($config){
        parent::__construct($config);
    }

    public function to_html($is_new){
        $class=$this->config['class'];
        $optionStr = '';
        for($i = 0; $i < count($this->config['choices']); $i++) {
            $optionStr .= '<div class="select-choice">'.$this->config['choices'][$i][0].'</div>';
        }
        $html="<div class='control-group'>";
        $value=htmlspecialchars($this->value, ENT_QUOTES);
        $html.= "<label class='control-label'>".htmlspecialchars($this->label)."</label>".
            "<div class='controls'>".
            "<input class='$class span6 select-input' ".($this->config['readonly']&&($this->config['default']||!$is_new&&strlen(trim($value))!=0)?'readonly':"")." type='text' name='{$this->name}' autocomplete=off  value='".$value."'><div class='select-choices'>".$optionStr."</div>";
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
        $css=<<<EOF
<style>
.controls .select-choices {position:absolute;border:1px solid #ccc;z-index:99;background-color:#fff;display:none;height:96px;overflow-y:scroll;}
.select-choices .select-choice {cursor: pointer;padding:2px 4px;}
.select-choices .select-choice:hover {background-color:#eee;}
</style>
EOF;
        return $css;
    }

    public function foot_js() {
        $js = <<<EOF
<script _form_control='select input'>
$('input.select-input').each(function() {
    $(this).focus(function() {
        var inWidth = $(this).css('width').replace(/px/ig, '')-2;
        $(this).next('.select-choices').css('width', inWidth).show();
    }).blur(function() {
        $(this).next('.select-choices').hide();
    });
});
$('.select-choice').mousedown(function() {
    var inputElem = $(this).parents('div .controls').find('input').eq(0);
    inputElem.val($(this).html());
});
</script>
EOF;
        return $js;
    }
}
