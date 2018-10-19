<?php

class Form_DateField extends Form_Field{
    public function __construct($config){
        array_map(function($fname)use(&$config) {
            $config[$fname] = isset($config[$fname]) ? $config[$fname] : '';
        }, ['labelClass']);
        parent::__construct($config);
    }

    public function format_value() {
        return $this->value() ? date('Ymd', $this->value()) : '';
    }

    public function to_html($is_new){
        $class=$this->config['class'];
        $value=$this->value?htmlspecialchars($this->value):$this->config['default'];
        $html="<div class='control-group'>";
        $html.= "<label class='control-label ".$this->config['labelClass']."'>".$this->label."</label>".
            "<div class='controls'>".
//                                            '<div class="input-append date date-picker" data-date="12-02-2012" data-date-format="dd-mm-yyyy" data-date-viewmode="years">'.
                                                '<input size="16" name='.$this->name.'  type="hidden" value="'.$value.'" readonly class="m-wrap m-ctrl-medium datepicker '.$class.'"'.($this->tempValue?" _value=$this->tempValue":'').'>';
//                                                '<span class="add-on"><i class="icon-calendar"></i></span>'.
//                                            '</div>';
            //"<input class='date-input $class' type='hidden' name='{$this->name}'  value='".htmlspecialchars($this->value)."'>";
        if($this->error){
            $html.="<span class='help-inline'>".$this->error."</span>";
        } else {
            $html.="<span class='help-inline'><span class='tips'>".$this->config['help']."</span></span>";
        }
        $html.='</div>';
        $html.='</div>';
        return $html;
    }
    public function head_css(){
        $css=<<<EOF
	<link rel="stylesheet" type="text/css" href="/winphp/metronic/media/css/datepicker.css" />
EOF;
        return $css;
    }
    public function foot_js(){
        $js=<<<EOF
<script type="text/javascript" src="/winphp/metronic/media/js/bootstrap-datepicker.js"></script>
<script>
(function(){
    (function(controlType){
        $('.'+controlType).each(function(i,elem){
            var dt_picker=$(elem);
            var input=dt_picker.clone().attr({"type":"text","name":''}).insertAfter(dt_picker);
            var timer = null;
            input.hover(function() {
                if (dt_picker.attr('_value') > 0 && dt_picker.val() == '') {
                    timer = setTimeout(function() {
                        var dt = new Date(dt_picker.attr('_value') * 1000 - new Date().getTimezoneOffset() * 60 * 1000);
                        var dateStr = dt.getFullYear() + '' + (dt.getMonth()<9?('0'+(dt.getMonth()+1)):(dt.getMonth()+1)) + '' + (dt.getDate()<10?('0'+dt.getDate()):dt.getDate());
                        var ret = (confirm('使用上一条记录日期“'+dateStr+'”'));
                        if (ret) {
                            input.val(dt_picker.attr('_value'));
                            dt_picker.val(dt_picker.attr('_value'));
                            input.data(controlType).update(dt);
                        } else {
                            dt_picker.removeAttr('_value');
                        }
                    }, 1);
                }
                }, function() {
                    clearTimeout(timer);
                    timer = null;
                });
            input.click(function() {
                clearTimeout(timer);
                timer = null;
            });
            input[controlType]({
                format:'yyyymmdd',
                autoclose: true,
                rtl : App.isRTL()
            });
            if (input.val()) {
                input.data(controlType).update(new Date(dt_picker.val()*1000-new Date().getTimezoneOffset() * 60 * 1000));
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
    })('datepicker');
})();
</script>
EOF;
        return $js;
    }
}
