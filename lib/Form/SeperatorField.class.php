<?php

class Form_SeperatorField extends Form_Field{
    public function __construct($config){
        parent::__construct($config);
    }

    public function to_html($is_new){
        $html = "<div class='control-seperator'><a name='$this->name'></a><li name='$this->name'>".htmlspecialchars($this->label)."</li></div>";
        return $html;
    }

    public function head_css() {
        $css=<<<EOF
<style>
.control-seperator {font-size:14px;background-color:#eee;padding:5px;margin-bottom:10px;border:1px solid #eee;}
#seperator-index {position:fixed;top:110px;right:31px;background-color:#fff;padding:5px 10px;border:1px solid #eee;}
#seperator-index a {color:black;}
</style>
EOF;
        return $css;
    }

    public function foot_js() {
        $js = <<<EOF
<div id='seperator-index'></div>
<script>
$(document).ready(function() {
    $(document).scroll(function() {
        var docScrTop = $(this).scrollTop();
        if (docScrTop <= 110) {
            $('#seperator-index').css('top', (110 - docScrTop) + 'px');
        } else {
            $('#seperator-index').css('top', '10px');
        }
    });
    $('.control-seperator').each(function() {
        var lkElem = $(this).find('a');
        var liElem = $(this).find('li').clone();
        $(liElem).html('<a href="#'+$(lkElem).prop('name')+'">'+liElem.html()+'</a>');
        $('#seperator-index').append(liElem);
    });
});
</script>
EOF;
        return $js;
    }
}
