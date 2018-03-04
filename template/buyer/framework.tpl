<!doctype html>
<html>
    <head>
        <meta charset="utf-8"/>
        {%block name="head"%}
        {%/block%}
    </head>
    <body>
        {%block name="body"%}
        {%/block%}
        <script src="/winphp/js/jquery.js"></script>
        <script>
            String.prototype.escapeHTML=function(){
                return this.replace(/</g,"&lt;").replace(">","&gt;");
            };
            function ts_to_date(ts){
                var d=new Date();
                d.setTime(ts*1000);
                return d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
            }
        </script>
        {%block name="foot"%}
        {%/block%}
    </body>
</html>
