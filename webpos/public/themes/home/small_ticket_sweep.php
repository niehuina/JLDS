<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo __('小票扫码');?></title>
    <link rel="stylesheet" href="<?php echo theme_url(); ?>/css/base.css">
    <link rel="stylesheet" href="http://at.alicdn.com/t/font_372409_ck1oqp4yisy8pvi.css">
    <link href="<?php echo theme_url(); ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo theme_url(); ?>/css/common.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo theme_url(); ?>/css/style.css">
    <script src="<?php echo theme_url(); ?>/js/jquery.min.js"></script>
    <script src="<?php echo theme_url(); ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo theme_url().'/js/jquery.jqprint-0.3.js';?>"></script>
    <script src="<?php echo theme_url().'/js/jquery.qrcode.min.js';?>"></script>
</head>

<body>
<div class="tic-area scan-code"  id="small_ticket_sweets">
    <div class="cpay-left-top ">
        <ul>
            <li><?php echo __('关联单号：');?><span><?php echo $order_id;?></span></li>
            <li><?php echo __('交易时间：');?><span><?php echo $time;?></span></li>
            <li><?php echo __('收银员：');?><span><?php echo $shop_users_name;?></span></li>
        </ul>
    </div>
    <div class="cpay-goods">
        <div class="tit">
            <span class="tit-head"><?php echo __('商品名称');?></span>
            <span><?php echo __('数量');?></span>
            <span><?php echo __('单价');?></span>
            <span><?php echo __('金额');?></span>
        </div>
        <ul class="borUl">
            <?php
            if($goods){
                foreach($goods as $k=>$v){
                    ?>
                    <li>
                        <div class="clearfix">
                            <span class="spanW"><?php echo $v->goods_name;?> <em>(<?php echo $v->yf_goods_common->common_stock;?>)</em></span>
                            <span><?php echo $v->num;?></span>
                            <span><?php echo $v->goods_price/$v->num;?></span>
                            <span><?php echo $v->goods_price;?></span>
                        </div>
                    </li>
                <?php }}?>
        </ul>
    </div>
    <div class="cpay-total">
        <ul>
            <li><?php echo __('累计消费数量');?><span><?php echo $ljxf;?></span></li>
            <li><?php echo __('会员编号');?><span><?php echo $numbers;?></span></li>
            <li><?php echo __('优惠方式');?><span><?php echo $discount;?></span></li>
            <li>
                <div class="total-area"><?php echo __('合计');?><span><?php echo $goods_price;?></span>
                </div>
            </li>
        </ul>
    </div>
    <div class="cpay-way cpay-total">
        <ul>
            <li><?php echo __('收款方式');?><span><?php echo $payid;?></span></li>
            <li><?php echo __('收据编号');?><span><?php echo $id;?></span></li>
        </ul>
        <div class="tc tic-code">
            <div id="code"></div>

            <p><?php echo __('支付宝、微信扫码立即支付');?></p>
        </div>
    </div>
    <p class="tic-store title"><?php echo __('webpos收银系统');?><?php echo $shop_name;?></p>
</div>
</body>
<script>
    $(function(){
        var url = "<?php echo 'http://'."$_SERVER[HTTP_HOST]".url('home/welcome/ticket_code_pay',['order_id'=>$order_id]);?>";
        $('#code').qrcode(url);
    })

</script>
</html>
