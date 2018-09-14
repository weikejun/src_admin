<?php

class Form_ChoosemodelMultiField extends Form_Field{
    private $modelClass;
    public function __construct($config){
        parent::__construct($config);
        if(!class_exists($config['model'])){
            throw new ModelAndViewException("text:no this model:{$config['model']}",1,"text:no this model:{$config['model']}");
        }
        $this->modelClass=$config['model'];
        $this->config['show'] = isset($config['show']) ? $config['show'] : 'name';
    }

    public function to_html($is_new){
        $class=$this->config['class'];
        $model = new $this->modelClass;
        $model->mId = $this->value;
        $show = 'm'.ucfirst($this->config['show']);
        if ($model->mId) {
            $model->select();
        }
        $html="<div class='control-group'>";
        $html.= "<label class='control-label'>".htmlspecialchars($this->label)."</label>".
            "<div class='controls'>".
            '<div class="msel-input" id="msel_'.$this->name.'"><a _name="'.$this->name.'" class="choosemodel-multi" model="'.str_replace("Model_", "", $this->modelClass).'" _show="'.$this->config['show'].'" href="javascript:void(0)">+选择</a></div>';
        if($this->error){
            $html.="<span class='help-inline'>".$this->error."</span>";
        } else {
            $html.="<span class='help-inline'><span class=tips>".$this->config['help']."</span></span>";
        }
        $html.='</div>';
        $html.='</div>';
        return $html;
    }
    public function head_css(){
        $css=<<<EOF
<style>
    #popup .content iframe{width:1000px;height:768px;overflow:auto;}
    .b-close{background:#fff;display:block;}
    .b-close span{float:right;width:20px;display:block;background:#000;color:#fff;text-align:center;cursor:pointer;}
    .msel-item{margin: 0 5px;cursor:pointer;}
    .control-group .msel-input{display:inline-block;padding-top:7px;}
</style>
EOF;
        return $css;
    }
    public function foot_js(){
        $js=<<<EOF
<script>
use("popup",function(){
    if(window.__init_choosemodelMulti_field){
        return;
    }
    window.__init_choosemodelMulti_field=true;
    $(".choosemodel-multi").click(function(){
        if($(this).hasClass('readonly')){
            return;
        }
        var model=$(this).attr("model");
        var field=$(this).attr("_name");
        var showField=$(this).attr("_show");
        window.choosemodelMultiPopup=$('#popup').find('.content').html('').end().bPopup({
            content:'iframe', //'ajax', 'iframe' or 'image'
            contentContainer:'.content',
            iframeAttr:'scrolling="yes" frameborder="0"',
        loadUrl:'{%\$__controller->getUrlPrefix()%}/'+encodeURIComponent(model)+'?action=select&multi=1&field='+encodeURIComponent(field)+'&show='+showField //Uses jQuery.load()
        });
        return false;

    });
    window.choosemodelMulti=function(model,field,items){
        //$(document.forms.main).find('[name="'+field+'"]').val(id);
        //$(document.forms.main).find('[_name="'+field+'"]').val(text);
        var selId = '#msel_' + field;
        var showField=$(selId + ' .choosemodel-multi').attr("_show");
        $(selId + ' .msel-item').remove();
        $(selId).parent().find('input').remove();
        for(i = 0; i < items.length; i++) {
            var tag = document.createElement('a');
            $(tag).text(items[i][showField]);
            $(tag).addClass('msel-item');
            /*
            $(tag).dblclick(function() {
                $(this).remove();
            });*/
            $(selId).prepend(tag);
            var inp = document.createElement('input');
            $(inp).attr('name', field + '[]');
            $(inp).attr('type', 'hidden');
            $(inp).val(items[i]['id']);
            $(selId).parent().append($(inp));
        }
        if (window.choose_callback) {
            choose_callback();
        }
    };
    $('#control-clear').click(function() {
        var pDiv = $(this).parents('div.control-group');
        pDiv.find('input').remove();
        pDiv.find('a.msel-item').remove();
    });
});
</script>
EOF;
        return $js;
    }
}

