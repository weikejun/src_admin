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
    setInterval(function() {
        $('#seperator-index').find('li').css('color','');
        var markPending = function(elem) {
            var sepElem = $(elem).prevAll('.control-seperator').eq(0);
            $('#seperator-index').find('li[name='+$(sepElem).find('li').attr('name')+']').css('color', 'red');
        }
        $('.control-group').each(function() {
            var ipElem = $(this).find(':text');
            if(ipElem && ipElem.attr('name') && ipElem.val() == '') {
                markPending(this);
                return;
            };
            ipElem = $(this).find(':radio');
            if (ipElem && ipElem.length > 0) {
                for(i = 0; i < ipElem.length; i++) {
                    if(ipElem.attr('checked') || ipElem.parent('.checked').length) return;
                }
                markPending(this);
                return;
            }
            ipElem = $(this).find('textarea');
            if(ipElem && ipElem.attr('name') && ipElem.val() == '') {
                markPending(this);
                return;
            };
            var ipElem = $(this).find(':hidden');
            if(ipElem && ipElem.attr('name')) {
                if (ipElem.hasClass('datepicker') && !ipElem.next().val()) {
                    markPending(this);
                    return;
                } else if(ipElem.next.val == '') {
                    markPending(this);
                    return;
                }
            };
        });
    }, 1000);
    setInterval(function() {
        $.ajax({
            type: 'POST',
            url: $('#main_form').attr('action') + '/autoSave',
            data: $('#main_form').serialize(),
            dataType: 'json',
            success: function(ret) {
                if (ret.id) {
                    $('#autosave').html('&nbsp;<i>自动保存于 '+ret.stamp+'</i>');
                    $('#main_form input[name=id]').val(ret.id);
                    $('#main_form input[name=action]').val('update');
                }
            }
        });
    }, 180000);
});
</script>
EOF;
        return $js;
    }
}
