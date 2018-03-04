<!doctype html>
<html>
    <head>
        <meta charset="utf-8"/>
        <style>
            input{width:500px;}
        </style>
    </head>
    <body>
        <form method="post" action="/admin/user/send">
            <div>目标用户：<input type="text" name="to" value="{%$to|escape%}"></div>
            <div>消息：<input type="text" name="msg"></div>
            <div>类型：<input type="text" name="type" value="{%$type|escape|default:"admin"%}"></div>
            <div>from：<input type="text" name="from" value="{%$from|escape|default:"admin"%}"></div>
            <hr>
            <div>order_id：<input type="text" name="order_id" value="{%$order_id|escape%}"></div>
            <div>trade_title：<input type="text" name="trade_title" value="{%$trade_title|escape%}"></div>
            <div>stock_imageUrl：<input type="text" name="stock_imageUrl" value="{%$stock_imageUrl|escape%}"></div>
            <input type="submit" value="提交">
        </form>
        <div>
            <pre>
                {%$notify|escape%}
            </pre>
        </div>
    </body>
</html>
