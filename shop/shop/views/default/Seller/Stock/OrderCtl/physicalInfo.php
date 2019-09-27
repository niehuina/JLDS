<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
    <link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet">
    <script src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js" charset="utf-8"></script>
    <style>
        .ncsc-order-condition {
            width: 55%;
        }
    </style>
</head>
<body>

<div id="mainContent">

    <div class="ncsc-oredr-show">
        <div class="ncsc-order-info">
            <div class="ncsc-order-details">
                <div class="title"><?=__('订单信息')?></div>
                <div class="content">
                    <dl>
                        <dt><?=__('收&nbsp;&nbsp;货&nbsp;&nbsp;人')?>：</dt>
                        <dd><?= $data['receiver_info']; ?></dd>
                    </dl>
                    <dl>
                        <dt><?=__('支付方式')?>：</dt>
                        <dd> <?= $data['payment_name']; ?> </dd>
                    </dl>
                    <dl>
                        <dt><?=__('发&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;票')?>：</dt>
                        <dd> <?= $data['order_invoice']; ?> </dd>
                    </dl>
                    <dl>
                        <dt><?=__('买家留言')?>：</dt>
                        <dd><?= $data['order_message']; ?></dd>
                    </dl>
                    <dl class="line">
                        <dt><?=__('订单编号')?>：</dt>
                        <dd><?= $data['stock_order_id']; ?>
                            <a href="javascript:void(0);"><?=__('更多')?><i class="iconfont icon-iconjiantouxia"></i>
                                <div class="more"><span class="arrow"></span>
                                    <ul>
                                        <li><span><?= $data['order_create_time']; ?></span><?=__('买家下单')?></li>
                                        <li><span><?= $data['order_create_time']; ?></span><?=__('买家 生成订单')?></li>
                                    </ul>
                                </div>
                            </a>
                        </dd>
                    </dl>
                    <dl>
                        <dt></dt>
                        <dd></dd>
                    </dl>
                </div>
            </div>
            <div class="ncsc-order-condition">
                <dl>
                    <dt><i class="icon-ok-circle green"></i><?=__('订单状态')?>：</dt>
                    <dd><?= $data['order_status_text']; ?></dd>
                </dl>
                <ul class="order_state">
                    <?= $data['order_status_html']; ?>
                    <?php if ($data['shop_user_id'] == Perm::$userId) { ?>
                        <?php if ($data['order_status'] == Order_StateModel::ORDER_PAYED): ?>
                        <li>
                            <?= __('3. 如果您想取消该备货单，请与平台沟通后对订单进行') ?>
                            <a onclick="javascript:cancelOrder('<?= $val['order_id'] ?>', '1')" class="ncbtn-mini bbc_seller_btns"><?= __('取消订单') ?></a>
                            <?= __('操作。') ?>
                        </li>
                        <?php endif; ?>
                    <?php } ?>
                    <?php if ($data['order_shipping_status'] == 2): ?>
                        <?php if ($data['shop_user_id'] == Perm::$userId) { ?>
                            <li><?= __('3. 如果您已收到货，且对商品满意，您可以 ') ?>
                                <a onclick="confirmOrder('<?= $data['order_id'] ?>')" class="ncbtn-mini bbc_seller_btns"><?= __('确认收货') ?></a><?= __('完成交易。 ') ?>
                            </li>
                        <?php } ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <?php if ($data['order_status'] != Order_StateModel::ORDER_CANCEL) { ?>
        <div id="order-step" class="ncsc-order-step" style="text-align: center;">
            <dl class="step-first current">
                <dt><?=__('提交订单')?></dt>
                <dd class="bg"></dd>
                <dd class="date" title="<?=__('下单时间')?>"><?= $data['order_create_time']; ?></dd>
            </dl>
            <?php if($data['payment_id'] != PaymentChannlModel::PAY_CONFIRM): ?>
            <dl class="<?= $data['order_payed']; ?>">
                <dt><?=__('支付订单')?></dt>
                <dd class="bg"> </dd>
                <dd class="date" title="<?=__('付款时间')?>"><?= $data['payment_time']; ?></dd>
            </dl>
            <?php endif; ?>
            <dl class="<?= $data['order_wait_confirm_goods']; ?>">
                <dt><?=__('商家发货')?></dt>
                <dd class="bg"> </dd>
                <dd class="date" title="<?=__('发货时间')?>"><?= $data['order_shipping_time']; ?></dd>
            </dl>
            <dl class="<?= $data['order_received']; ?>">
                <dt><?=__('确认收货')?></dt>
                <dd class="bg"> </dd>
                <dd class="date" title="<?=__('完成时间')?>"><?= $data['order_finished_time']; ?></dd>
            </dl>
            <dl class="<?= $data['order_evaluate']; ?>">
                <dt><?=__('评价')?></dt>
                <dd class="bg"></dd>
                <dd class="date" title="<?=__('评价时间')?>"><?= $data['order_buyer_evaluation_time']; ?></dd>
            </dl>
        </div>
        <?php } ?>
        <div class="ncsc-order-contnet">
            <table class="ncsc-default-table order">
                <thead>
                <tr>
                    <th class="w10">&nbsp;</th>
                    <th colspan="2"><?=__('商品')?></th>
                    <th class="w120"><?=__('商品会员价格')?><!--(<?/*=Web_ConfigModel::value('monetary_unit')*/?>)--></th>
                    <th class="w60"><?=__('数量')?></th>
                    <th class="w150"><strong><?=__('应返还差价')?>(<?=Web_ConfigModel::value('monetary_unit')?>)</strong></th>
<!--                    <th class="w100">--><?//=__('优惠活动')?><!--</th>-->
                    <th class="w150"><?=__('操作')?></th>
                </tr>
                </thead>
                <tbody>
                <?php if ( !empty($data['goods_list']) ) { ?>
                <?php foreach ( $data['goods_list'] as $key => $val ) { ?>
                <tr class="bd-line">
                    <td>&nbsp;</td>
                    <td class="w50">
                        <div class="pic-thumb">
                            <a target="_blank" href="<?= $val['goods_link']; ?>">
                                <img src="<?= $val['goods_image']; ?>">
                            </a>
                        </div>
                    </td>
                    <td class="tl">
                        <dl class="goods-name">
                            <dt>
                                <a target="_blank" href="<?= $val['goods_link']; ?>"><?= $val['goods_name']; ?></a>
                                <a target="_blank" href="<?= $val['goods_link']; ?>" class="blue ml5"><?=__('[交易快照]')?></a>
                            </dt>
                            <!--<dd><?/*= $val['spec_name']; */?></dd>-->
                        </dl>
                    </td>
                    <td><?= format_money($val['goods_price_vip']); ?><p class="green"></p></td>
                    <td><?= $val['goods_num']; ?></td>
                    <td class="commis bdl bdr"><?= $val['order_goods_commission']?></td>
                    <!-- S 合并TD -->
                    <?php if ( $key == 0 ) { ?>
<!--                    <td class="bdl bdr" rowspan="--><?//= $data['goods_cat_num']; ?><!--">--><?//= $data['order_shop_benefit']?><!--</td>-->
                    <td class="bdl bdr" rowspan="<?= $data['goods_count']; ?>">
                        <?= $data['order_stauts_const']; ?><br/>
                        <?php if($data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS ){ ?>
                            <?php if($data['order_shipping']){ ?>
                                <?php $is_show = count($data['order_shipping']) > 1 ?>
                                <?php foreach($data['order_shipping'] as $i=>$ship){ ?>
                                    <a style="position:relative;" onmouseover="show_logistic('<?=($ship['stock_order_id'])?>','<?=($ship['shipping_express_id'])?>','<?=($ship['shipping_code'])?>')"
                                       onmouseout="hide_logistic('<?=($ship['stock_order_id'])?>')">
                                        <i class="iconfont icon-icowaitproduct rel_top2"></i><?=__('物流信息')?><?php if($is_show) echo $i+1; ?>
                                        <div style="display: none;" id="info_<?=($ship['stock_order_id'])?>" class="prompt-01"> </div>
                                    </a>
                                    <?php if($data['shop_user_id'] == Perm::$userId && $ship['shipping_status'] == 0){ ?>
                                    ->
                                    <a onclick="confirmShipping('<?= $ship['stock_order_id'] ?>', '<?= $ship['shipping_express_id'] ?>', '<?= $ship['shipping_code'] ?>')" class="to_views ">
                                        <i class="iconfont icon_size22"></i><?= __('确认收货') ?></a><br/>
                                    <?php }else{?>
                                        -><?= __('已收货') ?><br/>
                                    <?php }?>
                                <?php }?>
                            <?php } else {?>
                                <a style="position:relative;" onmouseover="show_logistic('<?=($data['stock_order_id'])?>','<?=($data['order_shipping_express_id'])?>','<?=($data['order_shipping_code'])?>')"
                                   onmouseout="hide_logistic('<?=($data['stock_order_id'])?>')">
                                    <i class="iconfont icon-icowaitproduct rel_top2"></i><?=__('物流信息')?>
                                    <div style="display: none;" id="info_<?=($data['stock_order_id'])?>" class="prompt-01"> </div>
                                </a><br/>
                                <?php if($data['shop_user_id'] == Perm::$userId){ ?>
                                    <a onclick="confirmShipping('<?= $data['stock_order_id'] ?>', '<?= $data['shipping_express_id'] ?>', '<?= $data['shipping_code'] ?>')" class="to_views ">
                                        <i class="iconfont icon_size22"></i><?= __('确认收货') ?></a>
                                <?php }?>
                            <?php }?>
                        <?php }?>
                    </td>
                    <?php } ?>
                    <!-- E 合并TD -->
                </tr>
                <?php } ?>
                <?php } ?>
                <!-- S 赠品列表 -->
                <!-- E 赠品列表 -->

                </tbody>
                <tfoot>
                <tr>
                    <td colspan="7">
                        <dl class="freight">
                            <dd><?= $data['shipping_info']; ?></dd>
                        </dl>
                        <dl class="sum">
                            <dt><?=__('订单金额')?>：</dt>
                            <dd><em class="bbc_seller_color"><?= format_money($data['order_payment_amount_vip']); ?></em></dd>
                            <?php if($data['order_status'] == Order_StateModel::ORDER_WAIT_PAY){?>
                            <a onclick="edit_cost('<?=($data['order_id'])?>')" class="ncbtn-mini bbc_seller_btns"><?=__('修改金额')?></a>
                            <?php }?>
                        </dl>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        $('.tabmenu > ul').find('li:gt(3)').remove();
        $('.tabmenu > ul').find('li:lt(3)').remove();
        var href = window.location.href;
        $('.tabmenu > ul > li > a').attr('href',href);
        /*$($('.tabmenu > ul')[0]).find('li:lt(6)').remove();*/

        window.confirmShipping = function(a, b, c){
            var url = SITE_URL + "?ctl=Seller_Stock_Order&met=confirmShipping&typ=";
            $.dialog({
                title: '确认收货',
                content: 'url: ' + url + 'e&user=buyer',
                data: {order_id: a, shipping_express_id: b, shipping_code:c},
                height: 300,
                width: 500,
                lock: true,
                drag: false,
                ok: function () {
                    var form_ser = $(this.content.order_confirm_form).serialize();
                    $.post(url + 'json', form_ser, function (rs) {
                        if (rs.status == 200) {
                            Public.tips.success('确认收货成功！');
                            window.location.reload();
                            //$.dialog.alert('确认收货成功'), window.location.reload();
                            return true;
                        } else {
                            Public.tips.error('确认收货失败！');
                            //$.dialog.alert('确认订单失败');
                            return false;
                        }
                    })
                }
            })
        }

        window.edit_cost = function (e)
        {
            url = SITE_URL + "?ctl=Seller_Stock_Order&met=cost&typ=e&order_id="+e;

            $.dialog({
                title: '<?=__('修改订单金额')?>',
                content: 'url: ' + url ,
                height: 340,
                width: 580,
                lock: true,
                drag: false

            })
        }

        window.hide_logistic = function (order_id)
        {
            $("#info_"+order_id).hide();
            $("#info_"+order_id).html("");
        }

        window.show_logistic = function (order_id,express_id,shipping_code)
        {
            $("#info_"+order_id).show();
            $.post(BASE_URL + "/shop/api/logistic.php",{"order_id":order_id,"express_id":express_id,"shipping_code":shipping_code} ,function(da) {

                if(da)
                {
                    $("#info_"+order_id).html(da);
                }
                else
                {
                    $("#info_"+order_id).html('<div class="error_msg"><?=__('接口出现异常')?></div>');
                }

            })
        }
    </script>
</div>


<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>