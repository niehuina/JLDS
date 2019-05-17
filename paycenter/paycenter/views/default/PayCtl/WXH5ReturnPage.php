
<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="<?=$this->view->js?>/jquery-1.9.1.js"></script>
    <title>等待支付结果</title>
    <style type="text/css">
        body {
            background:#fff;
            width: 100%;
            z-index: -10;
            padding: 0;
        }
    </style>
</head>
<body>
<div id="content" align="center">
    <div style="margin-left: 10px;margin-top:100px;color:#556B2F;font-size:20px;font-weight: bolder;">等待返回支付结果</div><br/>
    <A href="http://localhost:8008/tmpl/member/order_list.html" style="margin-left: 10px;margin-top:100px;color:#556B2F;font-size:20px;font-weight: bolder;">返回全部订单</><br/>
</div>
<script>
        $(function(){
            setInterval(function(){check()}, 5000);  //5秒查询一次支付是否成功
        })
        function check(){
            var url = '<?php echo $checkurl;?>';

            $.post(url, null, function(data){
                //data = JSON.parse(data);
                if(data.status == "200"){
                    //alert(JSON.stringify(data));
                  //  alert("订单支付成功,即将跳转...");
                    window.location.href = "http://localhost:8008/tmpl/member/order_list.html";
                }else{
                    console.log(data);
                }
            },'json');
        }
    </script>

</body>
</html>


