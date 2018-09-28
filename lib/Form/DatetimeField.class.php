<?php

class Form_DatetimeField extends Form_Field{
    public function __construct($config){
        $config[$fname = 'auto_update'] = isset($config[$fname]) ? $config[$fname] : '';
        parent::__construct($config);
    }

    public function to_html($is_new){
        $class=$this->config['class'];
        $html="<div class='control-group'>";
        $value=$this->value?htmlspecialchars($this->value):$this->config['default'];
        $html.= "<label class='control-label'>".htmlspecialchars($this->label)."</label>".
            "<div class='controls'>".(isset($this->config['readonly']) && $this->config['readonly']
            ? '<input size="16" type="text" value="'.($value?date('Ymd H:i:s',$value):$value).'" readonly><input size="16" name='.$this->name.'  type="hidden" value="'.$value.'">'
            : '<input size="16" name='.$this->name.' type="hidden" value="'.$value.'" class="m-wrap m-ctrl-medium datetimepicker">'); 
        if($this->error){
            $html.="<span class='help-inline'>".$this->error."</span>";
        }
        $html.='</div>';
        $html.='</div>';
        return $html;
    }

    public function value(){
        if($this->config['auto_update']){
            return time();
        }
        return $this->value;
    }
    public function head_css(){
        $css=<<<EOF
	<link rel="stylesheet" type="text/css" href="/winphp/metronic/media/css/datetimepicker.css" />
EOF;
        return $css;
    }
    public function foot_js(){
        $js=<<<EOF
<script type="text/javascript" src="/winphp/metronic/media/js/bootstrap-datetimepicker.js"></script>
<script>
(function(){
    (function(controlType){
        $('.'+controlType).each(function(i,elem){
            var dt_picker=$(elem);
            var input=dt_picker.clone().attr({"type":"text","name":''}).insertAfter(dt_picker);
            input[controlType]({
                format:'yyyymmdd hh:ii:ss',
                autoclose: true,
                rtl : App.isRTL()
            });
            if (input.val()) {
                input.data(controlType).update(new Date(dt_picker.val()*1000));
            }
            //console.debug(dt_picker.parents("form"));
            dt_picker.parents("form").submit(function(e){
                var d=input.data(controlType).getDate();
                if (input.val()) {
                    dt_picker.val(parseInt(d.getTime()/1000));
                } else {
                    dt_picker.val(0);
                }
            });
        });
    })('datetimepicker');
})();
</script>
EOF;
        return $js;
    }
}
