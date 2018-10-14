<?php

class Form_MessageField extends Form_Field{
    public function __construct($config){
        parent::__construct($config);
    }

    public function to_html($is_new){
        $class=$this->config['class'];
        $arr=json_decode($this->value(),true);
        $arr=$arr?$arr:[];
        $links=array_map(function($a){
            return "<li><a target='_blank' href='javascript:;'>".htmlspecialchars($a)."</a><button type='button' class='close' aria-hidden='true'>&nbsp;</button></li>";
        },$arr);
        $links=implode("\n",$links);
        $links=$links?"<ul>$links</ul>":"<ul></ul>";
        $html="<div class='control-group json_array'>";
        $html.= "<label class='control-label'>".htmlspecialchars($this->label)."</label>".
            "<div class='controls'>".
            $links.
            '<textarea class="array_input '.$class.'"/></textarea><a class="json_array_add btn" href="javascript:;" class="button">添加</a>'.
            "<input type='hidden' name='{$this->name}'  value='".$this->value."'>";
        if($this->error){
            $html.="<span class='help-inline'>".$this->error."</span>";
        }
        $html.='</div>';
        $html.='</div>';
        return $html;
    }
    public function head_css(){
        $css=<<<EOF
<style>
    .json_array .close{
        float:none;
        margin:0 0 0 10px;
    }
</style>
EOF;
        return $css;
    }
    
    public function foot_js(){
        $js=<<<EOF
<script>
(function(){
    if(window.__init_json_array_field){
        return;
    }
    window.__init_json_array_field=true;

    var upload_btn;
    var input_date = function() {
        var dt = new Date();
        var month = dt.getMonth() + 1;
        month = month < 10 ? (0+''+month) : month;
        var day = dt.getDate();
        day = day < 10 ? (0+''+day) : day;
        return dt.getFullYear() + '.' + month + '.' + day + ' ';
    }
    var htmlEscape = function(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
    $(document).delegate(".json_array_add",'click',function(){
        upload_btn=$(this);
        var input=upload_btn.prev("textarea");
        var link_list;
        link_list=$(upload_btn).prevAll("ul");
        link_list.prepend("<li><a target='_blank' href='javascript:;'>"+(input.hasClass('with_date')?input_date():'')+htmlEscape(input.val())+"</a><button type='button' class='close' aria-hidden='true'>&nbsp;</button></li>");
        update_input_value();
        input.val("");
        return false;
    });
    $(".json_array").delegate('ul .close','click',function(){
        upload_btn=$(this).parents(".json_array").find('.json_array_add');
        $(this).parent('li').remove();
        update_input_value();
    });
    $(".json_array").delegate('ul li a', 'click', function() {
        var message = $(this).text().split(' ', 2);
        var par = $(this).parent();
        par.html('<span>'+message[0]+'</span><input type=text value="'+message[1]+'" />');
        par.find('input').focus();
        par.find('input').blur(function() {
            var msgDate = par.find('span').text();
            var msgCont = par.find('input').val();
            par.html("<a target='_blank' href='javascript:;'>"+msgDate+' '+htmlEscape(msgCont)+"</a><button type='button' class='close' aria-hidden='true'>&nbsp;</button>");
            upload_btn = par.parents(".json_array").find('.json_array_add');
            update_input_value();
        });
    });
    function update_input_value(){
        var link_list=$(upload_btn).prevAll("ul").find("li a");
        var input=$(upload_btn).next("input");
        var links=$.map(link_list,
            function(link){
                return $(link).text();
            }
        );
        input.val(JSON.stringify(links));
    }
})();
</script>
EOF;
        return $js;
    }
}



