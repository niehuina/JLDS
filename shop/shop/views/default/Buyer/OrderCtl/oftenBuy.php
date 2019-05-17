<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
<link href="<?= $this->view->css ?>/goods-list.css" rel="stylesheet" type="text/css"
      xmlns="http://www.w3.org/1999/html">
<link href="<?= $this->view->css ?>/freqt-buy.css" rel="stylesheet" type="text/css"
      xmlns="http://www.w3.org/1999/html">
<script src="<?= $this->view->js_com ?>/plugins/jquery.imagezoom.small.js"></script>
    <script type="text/javascript" src="<?= $this->view->js ?>/jquery.lazy.js"></script>
<script>
    $(function(){
    });
</script>
</div>
<div class="order_content">
    <div class="category-box">
        <div class="s-main">
            <div>
                <ul class="category-list">
                    <li class="selected">
                        <a class="search" href="<?=Yf_Registry::get('url')?>?ctl=Buyer_Order&met=oftenBuy" data-cid="0">全部商品</a>
                    </li>
                    <?php if($goods_cats):?>
                        <?php foreach($goods_cats as $cat):?>
                        <li>
                            <a class="search" href="<?=Yf_Registry::get('url')?>?ctl=Buyer_Order&met=oftenBuy&cat_id=<?=$cat['cat_id']?>" data-cid="<?=$cat['cat_id']?>" ><?=$cat['cat_name']?>(<?=$cat['nums']?>)</a>
                        </li>
                        <?php endforeach;?>
                    <?php endif;?>
                </ul>
            </div>
        </div>
        <?php if($goods_cats && count($goods_cats) > 10):?>
        <div class="s-ext" style="display: block;"><span>更多</span><s></s></div>
        <?php endif;?>
    </div>
    <div class="freqt-buy" style="" id="freqt-buy">
        <div class="freqt-items">
            <?php foreach($data['items'] as $key => $val):
            $good = $val['goods_base'];
            $common = $val['common_base'];
            if($good):
            ?>
            <div class="freqt-item J-item-container" data-sku="<?=$common['goods_id']?>">
                <div class="off-container off-container-<?=$common['goods_id']?>">
                    <div class="tip">已下架</div>
                </div>
                <div class="freqt-goods freqt-goods-<?=$common['goods_id']?>">
                    <div class="p-img">
                        <a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$val['goods_id']?>" target="_blank">
                            <img class='lazy' alt="<?=$common['goods_name']?>" data-original="<?=image_thumb($common['common_image'],100,100)?>"
                        </a>
                    </div>
                    <div class="p-msg">
                        <div class="p-name">
                            <a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$val['goods_id']?>" target="_blank"><?=$common['common_name']?></a>
                        </div>
                        <div class="price-box">
                            <div class="p-price J-p-<?=$common['goods_id']?>">￥<strong><?=$common['common_price']?></strong></div>
                        </div>
                        <div class="freqt-num"><?=__('已购买')?><?=$val['goods_num']?><?=__('次')?></div>
                    </div>
                </div>

                <?php if($good['goods_stock']):?>
                    <?php if($common['common_is_virtual']){?>
                        <div class="freqt-opbtns">
                            <a class="buy-btn-only buy_now_virtual" data-id="<?=$val['goods_id']?>" target="_blank">
                                <?=__('立即购买')?>
                            </a>
                        </div>
                    <?php } else if($common['product_is_behalf_delivery'] == 1 && $common['common_parent_id']) { ?>
                        <div class="freqt-opbtns">
                            <a class="buy-btn-only buy_now_supplier" data-id="<?=$val['goods_id']?>" target="_blank">
                                <?=__('立即购买')?>
                            </a>
                        </div>
                    <?php } else { ?>
                        <div class="freqt-opbtns">
                            <a class="buy-btn buy_now" data-id="<?=$val['goods_id']?>" target="_blank">
                                <?=__('立即购买')?>
                            </a>
                            <a class="addcart-btn tuan_join_cart bbc_btns" data-id="<?=$val['goods_id']?>" target="_blank">
                                <?=__('加入购物车')?>
                            </a>
                        </div>
                    <?php } ?>
                <?php endif;?>
            </div>
            <?php endif;?>
            <?php endforeach;?>
        </div>
    </div>

    <div class="flip page clearfix">
        <?=$page_nav?>
        <!--<p><a href="#" class="page_first">首页</a><a href="#" class="page_prev">上一页</a><a href="#" class="numla cred">1</a><a href="#" class="page_next">下一页</a><a href="#" class="page_last">末页</a></p>-->
    </div>
</div>
</div>
</div>
</div>
</div>
<script type="application/javascript">
    $(function () {
        lazyload();

        $('.s-ext').bind("click", function () {
            var text = $(this).find('span')[0].innerText;
            if(text == "更多"){
                $(this).find('span')[0].innerText = '收起';
            }else if(text == "收起"){
                $(this).find('span')[0].innerText = '更多';
            }

            $('.category-list').toggleClass('show');
        });

        //立即购买服务商品
        $(".buy_now_virtual").bind("click", function ()
        {
            var goods_id = $(this).attr("data-id");
            var goods_num = 1;

            if ($.cookie('key'))
            {
                window.location.href = SITE_URL + '?ctl=Buyer_Cart&met=buyVirtual&goods_id=' + goods_id +'&goods_num='+ goods_num;

            }else
            {
                $("#login_content").show();
                load_goodseval(SITE_URL  + '?ctl=Index&met=fastLogin','login_content');
            }

        });

        //立即购买一件代发的分销商品
        $(".buy_now_supplier").bind("click", function ()
        {
            var goods_id = $(this).attr("data-id");
            var goods_num = 1;

            if ($.cookie('key'))
            {
                window.location.href = SITE_URL + '?ctl=Buyer_Cart&met=confirmGoods&goods_id=' + goods_id +'&goods_num='+ goods_num;
            } else {
                $("#login_content").show();
                load_goodseval(SITE_URL  + '?ctl=Index&met=fastLogin','login_content');
            }

        });

        //立即购买 - 实物商品
        $(".buy_now").bind("click", function ()
        {
            var goods_id = $(this).attr("data-id");
            var goods_num = 1;

            if ($.cookie('key'))
            {
                $.ajax({
                    url: SITE_URL + '?ctl=Buyer_Cart&met=addCart&typ=json',
                    data: {goods_id: goods_id, goods_num: goods_num},
                    dataType: "json",
                    contentType: "application/json;charset=utf-8",
                    async: false,
                    success: function (a)
                    {
                        if (a.status == 250)
                        {
                            Public.tips.error(a.msg);
                        }
                        else
                        {
                            if(a.data.cart_id)
                            {
                                window.location.href = SITE_URL + '?ctl=Buyer_Cart&met=confirm&product_id=' + a.data.cart_id;
                            }

                        }
                    },
                    failure: function (a)
                    {
                        Public.tips.error('<?=__('操作失败！')?>');
                    }
                });
            }else
            {
                $("#login_content").show();
                load_goodseval(SITE_URL  + '?ctl=Index&met=fastLogin','login_content');
            }

        });

        //加入购物车
        $(".tuan_join_cart").bind("click", function ()
        {
            var goods_id = $(this).attr("data-id");
            var goods_num = 1;

            if ($.cookie('key'))
            {
                $.ajax({
                    url: SITE_URL + '?ctl=Buyer_Cart&met=addCart&typ=json',
                    data: {goods_id:goods_id, goods_num: goods_num},
                    dataType: "json",
                    contentType: "application/json;charset=utf-8",
                    async: false,
                    success: function (a)
                    {
                        if (a.status == 250)
                        {
                            Public.tips.error(a.msg);
                            //$.dialog.alert(a.msg);
                        }
                        else
                        {
                            //加入购物车成功后，修改购物车数量
                            $.ajax({
                                type: "GET",
                                url: SITE_URL + "?ctl=Buyer_Cart&met=getCartGoodsNum&typ=json",
                                data: {},
                                dataType: "json",
                                success: function(data){
                                    getCartList();
                                    $('#cart_num').html(data.data.cart_count);
                                    $('.cart_num_toolbar').html(data.data.cart_count);
                                }
                            });

                            $.dialog({
                                title: "<?=__('加入购物车')?>",
                                height: 100,
                                width: 250,
                                lock: true,
                                drag: false,
                                content: 'url: '+SITE_URL + '?ctl=Buyer_Cart&met=add&typ=e'
                            });
                        }
                    },
                    failure: function (a)
                    {
                        Public.tips.error('<?=__('操作失败！')?>');
                    }
                });
            }
            else
            {
                $("#login_content").show();
                load_goodseval(SITE_URL  + '?ctl=Index&met=fastLogin','login_content');
            }
        });
    });
</script>
<!-- 尾部 -->
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>