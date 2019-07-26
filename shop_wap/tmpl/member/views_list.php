<?php 
include __DIR__.'/../../includes/header.php';
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="Author" contect="U2FsdGVkX1+liZRYkVWAWC6HsmKNJKZKIr5plAJdZUSg1A==">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
    <title>浏览历史</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_products_list.css">
</head>

<body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>
            <div class="header-title">
                <h1>浏览记录</h1>
            </div>
            <div class="header-r">
                <a id="clearbtn" href="javascript:void(0);" class="text">清空</a>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div id="viewlist" class="list"> </div>
    </div>
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
    <script type="text/html" id="viewlist_data">
        <% if ('undefined' != typeof arr.items  &&  arr.items.length > 0) {%>
        <ul class="goods-secrch-list">
            <% for (var i=0; i<arr.items.length; i++) {%>
            <li class="goods-item">
                    <span class="goods-pic">
                        <a href="../<% if(arr.items[i].common_is_virtual == 1){%>service_product_detail.html<%}else{%>store.html<%}%>?goods_id=<%=arr.items[i].goods_id%>">
                            <img src="<%=$image_thumb(arr.items[i].common_image, 116, 116)%>"/>
                        </a>
                    </span>
                <dl class="goods-info">
                    <dt class="goods-name">
                        <a href="../<% if(arr.items[i].common_is_virtual == 1){%>service_product_detail.html<%}else{%>store.html<%}%>?goods_id=<%=arr.items[i].goods_id%>">
                            <h4><%=arr.items[i].common_name%></h4>
                            <h6></h6>
                        </a>
                    </dt>
                    <dd class="goods-sale">
                        <a href="../<% if(arr.items[i].common_is_virtual == 1){%>service_product_detail.html<%}else{%>store.html<%}%>?goods_id=<%=arr.items[i].goods_id%>">
                            <span class="goods-price">￥<em><%=arr.items[i].common_price%></em></span>
                        </a>
                    </dd>
                </dl>
            </li>
            <% } %>
            <li class="loading">
                <div class="spinner"><i></i></div>浏览记录读取中...
            </li>
        </ul>
        <% } else {%>
        <div class="nctouch-norecord views">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>暂无您的浏览记录</dt>
                <dd>可以去看看哪些想要买的</dd>
            </dl>
            <a href="<%=WapSiteUrl%>" class="btn">随便逛逛</a>
        </div>
        <% } %>
    </script>
    
    <script type="text/javascript" src="../../js/libs/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/libs/template.js"></script>
    <script type="text/javascript" src="../../js/libs/ncscroll-load.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/libs/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member/view_list.js"></script>
</body>

</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>