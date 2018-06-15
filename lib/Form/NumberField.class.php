<?php

class Form_NumberField extends Form_Field{
    public function __construct($config){
        parent::__construct($config);
    }

    public function value() {
        $this->value = preg_replace('/[^0-9\.]/', '', $this->value);
        return parent::value();
    }

    public function to_html($is_new){
        $class=$this->config['class'];
        $html="<div class='control-group'>";
        $value=(!$this->value&&$this->value!==0)?$this->value:htmlspecialchars(number_format(floatval($this->value), 2), ENT_QUOTES);
        $html.= "<label class='control-label'>".htmlspecialchars($this->label)."</label>".
            "<div class='controls'>".
            "<input class='numeric $class span6' ".($this->config['readonly']&&($this->config['default']||!$is_new&&strlen(trim($value))!=0)?'readonly':"")." type='text' name='{$this->name}'  value='".$value."'>";
        if($this->error){
            $html.="<span class='help-inline'>".$this->error."</span>";
        } else {
            $html.="<span class='help-inline'><span class='tips'>".$this->config['help']."</span></span>";
        }
        $html.='</div>';
        $html.='</div>';
        return $html;
    }

    public function foot_js() {
        $js = <<<EOF
<script>
    $(document).ready(function() {
        $('input.numeric').each(function(index,elem) {
            $(elem).data('timers', -1);
            $(elem).keyup(function(evt) {
                if(evt.keyCode == 37 || evt.keyCode == 39) {
                    return;
                }
                if ($(elem).data('timers')) {
                    clearTimeout($(elem).data('timers'));
                    $(elem).data('timers', setTimeout(function() {
                        var re=/\d{1,3}(?=(\d{3})+$)/g;
                        $(elem).val($(elem).val().replace(/[^0-9\.]/g,'').replace(/^(\d+)((\.\d+)?)$/,function(s,s1,s2){return s1.replace(re,"$&,")+s2;}));
                    },500));
                }
                $(elem).val($(this).val().replace(/[^0-9\.]/g,''));
            });
        });
    });
</script>
EOF;
        return $js;
    }
}
