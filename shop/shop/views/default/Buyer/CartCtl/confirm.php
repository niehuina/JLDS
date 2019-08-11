<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'site_nav.php';
?>

    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/shop-cart.css"/>
    <script type="text/javascript" src="<?= $this->view->js ?>/cart.js"></script>
    <script type="text/javascript" src="<?= $this->view->js ?>/alert.js"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
    <link type="text/css" rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css">
    <link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js ?>/common.js"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.toastr.min.js"
            charset="utf-8"></script>

    <div class="cart-head">
        <div class="wrap">
            <div class="head_cont clearfix">
                <div class="nav_left" style="float:none;">
                    <a href="index.php" class=""><img src="<?= $this->web['web_logo'] ?>"/></a>
                    <a href="#" class="download iconfont"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="wrap">
        <div class="shop_cart_head clearfix">
            <div class="cart_head_left">
                <h4><?= __('确认订单') ?></h4>

            </div>
            <div class="cart-head-module clearfix">
                <p class="tips-p"><span><i
                                class="iconfont icon-orders-tips"></i></span><?= __('请仔细核对收货,发货等信息,以确保物流快递能准确投递') ?>.
                </p>
                <ul class="cart_process">
                    <li class="mycart">
                        <div class="fl">
                            <i class="iconfont icon-wodegouwuche bbc_color"></i>
                            <h4><?= __('我的购物车') ?><h4>
                        </div>

                    </li>
                    <li class="mycart process_selected1">
                        <div class="fl to"></div>
                        <div class="fl">
                            <i class="iconfont icon-iconquerendingdan bbc_color"></i>
                            <h4 class=""><?= __('确认订单') ?><h4>
                        </div>


                    </li>
                    <li class="mycart">
                        <div class="fl to"></div>
                        <div class="fl">
                            <i class="iconfont icon-icontijiaozhifu"></i>
                            <h4><?= __('支付提交') ?><h4>
                        </div>


                    </li>
                    <li class="mycart">
                        <div class="fl to"></div>
                        <div class="fl">
                            <i class="iconfont icon-dingdanwancheng"></i>
                            <h4><?= __('订单完成') ?><h4>
                        </div>

                    </li>
                </ul>
            </div>

        </div>
        <ul class="receipt_address clearfix" id="user_address" style="display: block;">
            <div id="address_list">
                <?php if (isset($data['address'])) {
                    $total = 0;
                    $total_dian_rate = 0;
                    foreach ($data['address'] as $key => $value) {
                        ?>
                        <li class="<?php if (!$address_id && $value['user_address_default'] == 1) { ?>add_choose<?php } ?><?php if ($address_id && $value['user_address_id'] == $address_id) { ?>add_choose<?php } ?> "
                            id="addr<?= ($value['user_address_id']) ?>">
                            <input type="hidden" id="address_id" value="<?= ($value['user_address_id']) ?>">
                            <input type="hidden" id="user_address_province_id"
                                   value="<?= ($value['user_address_province_id']) ?>">
                            <input type="hidden" id="user_address_city_id"
                                   value="<?= ($value['user_address_city_id']) ?>">
                            <input type="hidden" id="user_address_area_id"
                                   value="<?= ($value['user_address_area_id']) ?>">
                            <div class="editbox">
                                <a class="edit_address" data_id="<?= ($value['user_address_id']) ?>"><?= __('编辑') ?></a>
                                <a class="del_address" data_id="<?= ($value['user_address_id']) ?>"><?= __('删除') ?></a>
                            </div>
                            <h5><?= ($value['user_address_contact']) ?></h5>
                            <p class="addr-len"><?= ($value['user_address_area']) ?> <?= ($value['user_address_address']) ?></p>
                            <span class="phone"><?= ($value['user_address_phone']) ?></span>

                        </li>
                    <?php }
                } ?>
                <script>
                    $(function () {
                        if (".addr-len") {

                        }
                    })
                </script>
            </div>
            <div class="add_address">
                <a><?= __('+') ?></a>
            </div>
        </ul>

        <ul class="receipt_address clearfix" id="shop_address" style="display: none">
            <div class="address_list">
                <?php if (isset($shopEntityList)) {
                    $total = 0;
                    $total_dian_rate = 0;
                    foreach ($shopEntityList as $key => $value) {
                        ?>
                        <li id="addr<?= ($value['entity_id']) ?>">
                            <input type="hidden" id="address_id" value="<?= ($value['entity_id']) ?>">
                            <input type="hidden" id="shop_address_province_id"
                                   value="<?= ($value['shop_address_province_id']) ?>">
                            <input type="hidden" id="shop_address_city_id"
                                   value="<?= ($value['shop_address_city_id']) ?>">
                            <input type="hidden" id="shop_address_area_id"
                                   value="<?= ($value['shop_address_area_id']) ?>">

                            <h5><?= ($value['entity_name']) ?></h5>
                            <p class="addr-len"><?= ($value['province']) ?> <?= ($value['city']) ?><?= ($value['district']) ?><?= ($value['street']) ?><?= ($value['entity_xxaddr']) ?></p>
                            <span class="phone"><?= ($value['entity_tel']) ?></span>

                        </li>
                    <?php }
                } ?>
            </div>

        </ul>

        <h4 class="confirm"><?= __('支付方式') ?></h4>
        <div class="pay_way pay-selected" pay_id="1">
            <i></i><?= __('在线支付') ?>
        </div>
        <!--		<div class="pay_way" pay_id="2">-->
        <!--			<i></i>--><? //=__('货到付款')?>
        <!--		</div>-->

        <h4 class="confirm"><?= __('确认商品信息') ?></h4>
        <div class="cart_goods">
            <ul class='cart_goods_head clearfix'>
                <li class="price_all"><?= __('小计') ?>(<?= (Web_ConfigModel::value('monetary_unit')) ?>)</li>
                <li class="confirm_sale"><?= __('优惠') ?></li>
                <li class="goods_num"><?= __('数量') ?></li>
                <li class="goods_price"><?= __('单价') ?>(<?= (Web_ConfigModel::value('monetary_unit')) ?>)</li>
                <li class="goods_name"><?= __('商品') ?></li>
                <li class="cart_goods_all"></li>
            </ul>

            <?php unset($data['glist']['count']);
            foreach ($data['glist'] as $key => $val){ ?>

            <!-- S 计算店铺的会员折扣和总价 -->
            <?php
            $reduced_money = 0;//满送活动优惠的金额单独赋予一个变量
            $voucher_money = 0;//代金券活动优惠的金额单独赋予一个变量
            //判断后台是否开启了会员折扣，如果开启会员折扣则判断是否为自营店铺。计算店铺的折扣
            if (!Web_ConfigModel::value('rate_service_status') || (Web_ConfigModel::value('rate_service_status') && $val['shop_self_support'] == 'true')) {
                $dian_rate = ($val['sprice'] - $val['mansong_info']['rule_discount']) * (100 - $user_rate) / 100;
            } else {
                $dian_rate = 0;
            }

            //扣除折扣后店铺的店铺价格（本店合计）
            $shop_all_cost = number_format($val['sprice'] - $dian_rate, 2, '.', '');

            ?>
            <!-- E 计算店铺的会员折扣和总价 -->
            <ul class="cart_goods_list clearfix">
                <li>
                    <div class="bus_imfor clearfix">
                        <p class="bus_name">
                            <input type="hidden" id="shop_id" name="shop_id" value="<?= ($key) ?>">
                            <span>
								<i class="iconfont icon-icoshop"></i>
								<a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?= ($key) ?>"><?= ($val['shop_name']) ?></a>
								<?php if ($val['shop_self_support'] == 'true') { ?>
                                    <span><?= __('自营店铺') ?></span>
                                <?php } ?>
							</span>
                        </p>

                    </div>
                    <table>
                        <tbody class="rel_good_infor rel_good_infor2">
                        <?php foreach ($val['goods'] as $k => $v) { ?>
                            <tr>
                                <td class="goods_sel">
                                    <p>
                                        <input type="hidden" name="cart_id" value="<?= ($v['cart_id']) ?>">
                                    </p>
                                </td>
                                <td class="goods_img"><img src="<?= ($v['goods_base']['goods_image']) ?>"/></td>
                                <td class="goods_name_reset">
                                    <a target="_blank"
                                       href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= ($v['goods_base']['goods_id']) ?>"><?= ($v['goods_base']['goods_name']) ?></a>
                                    <p>
                                        <?php if (!empty($v['goods_base']['spec'])) {
                                            foreach ($v['goods_base']['spec'] as $sk => $sv) { ?>
                                                <?= ($sv) ?> &nbsp;&nbsp;
                                            <?php }
                                        } ?>
                                    </p>
                                </td>

                                <td class="goods_price">
<!--                                    --><?php //if ($v['old_price'] > 0) { ?><!--<p-->
<!--                                            class="ori_price">--><?//= ($v['old_price']) ?><!--</p>--><?php //} ?>
                                    <p class="now_price"><?= ($v['now_price']) ?></p>

                                </td>
                                <td class="goods_num">
                                    <span><?= ($v['goods_num']) ?></span>
                                </td>
                                <td class="confirm_sale">
                                    <?php if (isset($v['goods_base']['promotion_type'])): ?>
                                        <?php if ($v['goods_base']['promotion_type'] == 'groupbuy' && $v['goods_base']['groupbuy_starttime'] < date('Y-m-d H:i:s') && $v['goods_base']['groupbuy_endtime'] > date('Y-m-d H:i:s')): ?>
                                            <p class="sal_price"><?= __('团购') ?></p>
                                            <?php if ($v['goods_base']['down_price']): ?>
                                                <p><?= __('直降') ?><?= format_money($v['goods_base']['down_price']) ?></p><?php endif; ?>
                                        <?php endif; ?>
                                        <?php if ($v['goods_base']['promotion_type'] == 'xianshi' && $v['goods_base']['groupbuy_starttime'] < date('Y-m-d H:i:s') && $v['goods_base']['groupbuy_endtime'] > date('Y-m-d H:i:s')): ?>
                                            <p class="sal_price"><?= __('限时折扣') ?></p>
                                            <?php if ($v['goods_base']['down_price']): ?>
                                                <p><?= __('每件直降') ?><?= format_money($v['goods_base']['down_price']) ?></p><?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="price_all">
                                    <span class="subtotal"><?= ($v['sumprice']) ?></span>
                                    <?php if (!$v['buy_able']) { ?><p class="colred"><?= __('无货') ?></p><?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                    <!-- 后期修改加价购2017.8.2 -->
                    <?php foreach ($val['goods'] as $increakey => $increaval) { ?>
                        <?php
                        $cart_total_price = $increaval['now_price'] * $increaval['goods_num'];
                        ?>
                        <?php if ($increaval['goods_base']['increase_info']) { ?>
                            <div class="more-buy">
                                <h4><?= __('加价购') ?></h4>
                                <div class="inline morder-buy-con">
                                    <p class="sel-goods">
                                        <span><?= __('购物满') ?><?= (Web_ConfigModel::value('monetary_unit')) ?><?php echo $increaval['goods_base']['increase_info']['rule'][0]['rule_price']; ?><?php if ($increaval['goods_base']['increase_info']['rule'][0]['rule_goods_limit'] > 0) { ?>，<?= __('最多可购') ?><?php echo $increaval['goods_base']['increase_info']['rule'][0]['rule_goods_limit']; ?><?= __('件') ?><?php } ?></span><i
                                                class="icon"></i></p>
                                    <!-- 点击.sel-goods下拉列表 -->
                                    <div class="quan-ar jia-shop-are">
                                        <div class="jia-gou-height">
                                            <table>
                                                <!-- 遍历div.item-li -->
                                                <?php foreach ($increaval['goods_base']['increase_info']['rule'] as $increasekey => $increaseval) {
                                                    ?>
                                                    <?php if ($cart_total_price > $increaseval['rule_price'] || $cart_total_price == $increaseval['rule_price']) { ?>
                                                        <?php foreach ($increaseval['redemption_goods'] as $redempotionkey => $redempotionval) {

                                                            ?>
                                                            <?php if ($increaseval['rule_goods_limit'] == 0) {
                                                                $increaseval['rule_goods_limit'] = $redempotionval['goods_stock'];
                                                            } ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="clearfix bgf item-li <?php echo $increaval['goods_base']['increase_info']['shop_id']; ?>">

                                                                        <p class="tit-tip">
                                                                            <input class="select_increase"
                                                                                   rule_id="<?php echo $increaseval['rule_id']; ?>"
                                                                                   goods_price="<?php echo $redempotionval['redemp_price']; ?>"
                                                                                   shop_price="<?= (number_format($val['sprice'], 2, '.', '')) ?>"
                                                                                   shop_id="<?php echo $increaval['goods_base']['increase_info']['shop_id']; ?>"
                                                                                   type="checkbox">
                                                                            <label for="jjg_rule16">
                                                                                <span><?= __('购物满') ?><?= (Web_ConfigModel::value('monetary_unit')) ?><?php echo $increaseval['rule_price']; ?><?php if ($increaseval['rule_goods_limit'] > 0) { ?>，<?= __('最多可购') ?><?php echo $increaseval['rule_goods_limit']; ?><?= __('件') ?><?php } ?></span>
                                                                            </label>
                                                                        </p>

                                                                        <ul class="nctouch-cart-item">
                                                                            <!-- 活动规则加价商品 -->
                                                                            <li class="buy-item">
                                                                                <div class="bgf6 buy-li pd10">
                                                                                    <div class="goods-pic">
                                                                                        <a target="_blank"
                                                                                           href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&gid=<?= ($redempotionval['goods_id']) ?>">
                                                                                            <img src="<?php echo $redempotionval['goods_image']; ?>">
                                                                                        </a>
                                                                                    </div>
                                                                                    <dl class="goods-info">
                                                                                        <dt class="goods-name">
                                                                                            <a target="_blank"
                                                                                               href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&gid=<?= ($redempotionval['goods_id']) ?>">
                                                                                                <?= $redempotionval['goods_name']; ?>
                                                                                            </a>
                                                                                        </dt>
                                                                                        <dd class="goods-type"
                                                                                            title="<?= $redempotionval['goods_spec']; ?>"><?= $redempotionval['goods_spec']; ?></dd>
                                                                                    </dl>
                                                                                    <div class="goods-subtotal">
                                                                                        <span class="goods-price"><?= (Web_ConfigModel::value('monetary_unit')) ?><em><?= $redempotionval['redemp_price']; ?></em></span>
                                                                                    </div>
                                                                                    <div class="goods-num">
                                                                                        <em>x<?= $redempotionval['goods_stock']; ?></em>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="jia-shop clearfix">
                                                                                    <p class="fl"><?= __('加价购') ?>
                                                                                        <em><?= (Web_ConfigModel::value('monetary_unit')) ?><?= $redempotionval['redemp_price']; ?></em>
                                                                                    </p>
                                                                                    <div class="fr mrt4 JS_operation">
                                                                                        <div class="num-sel">
                                                                                            <a class="declick"
                                                                                               href="javascript:;">-</a>
                                                                                            <input class="increase_num"
                                                                                                   goods_id="<?php echo $redempotionval['goods_id']; ?>"
                                                                                                   data-max="<?php echo $increaseval['rule_goods_limit']; ?>"
                                                                                                   type="text"
                                                                                                   value="1">
                                                                                            <a class="inclick">+</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        </ul>

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php }
                                                    }
                                                } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } ?>


                    <div class="goods_remark clearfix">
                        <p class="remarks" style="width: 100%; text-align: left"><span><?= __('备注：') ?></span><input
                                    type="text" class="remarks_content" name="remarks" id="<?= ($key) ?>"
                                    placeholder="<?= __('限45个字（定制类商品，请将购买需求在备注中做详细说明）') ?>"><?= __('提示：请勿填写有关支付、收货、发票方面的信息') ?>
                        </p>
                        <?php if ($data['isDispalyShipping'] == true) { ?>
                            <div style="border-top: 1px dashed #e3e3e3;border-bottom: 1px dashed #e3e3e3;margin-top: 15px; margin-bottom: 10px;float: left; left: 0px ;min-width: 500px; text-align: left;">
                                <p class="remarks"><span><?= __('配送方式：') ?></span>
                                    <?php
                                    if (count($val['method_list']) > 0) {
                                        $method_list = $val['method_list'][0];
                                        foreach ($method_list as $m => $method) {
                                            ?>
                                            <label style="margin-left: 20px;">
                                                <input type="radio" name="shipping_method"
                                                       style="width: 15px;height: 15px;"
                                                       value="<?php print($method['shipping_method_id']); ?>"
                                                       id="check_<?php print($method['shipping_method_id']); ?>"
                                                       shop_id="<?= ($method['shop_id']) ?>"><?php print($method['shipping_method_Name']); ?>
                                            </label>
                                        <?php }
                                    } ?></p>
                                <input type="hidden" id="shop_shipping_method" name="shop[shop_shipping_method]"
                                       value=""/>

                            </div>
                        <?php } ?>

                        <div class="order_total">
                            <p class="clearfix">
                                <span><?= __('商品金额') ?></span>
                                <i id="total_price" class="price<?= ($key) ?>"
                                   total_shop_price="<?= (number_format($val['sprice'], 2, '.', '')) ?>"><?= (number_format($val['sprice'], 2, '.', '')) ?></i>
                            </p>
                            <p id="shipping_fee<?= ($key) ?>" class="clearfix trans<?= ($key) ?>">
                                <span><?= __('物流运费') ?></span>
                                <?php if ($data['cost'][$key]['cost'] > 0) { ?>
                                    <strong class="trancon<?= ($key) ?>"><?= ($data['cost'][$key]['con']) ?></strong>
                                    <i id="total_shipping" class="trancost<?= ($key) ?>">
                                        <?= (number_format($data['cost'][$key]['cost'], 2)) ?>
                                        <input type="hidden" class="shop_trancost<?= ($key) ?>"
                                               value="<?= (number_format($data['cost'][$key]['cost'], 2)) ?>">
                                    </i>
                                <?php } else { ?>
                                    <i id="total_shipping" class="trancost<?= ($key) ?>">0</i>
                                    <input type="hidden" class="shop_trancost<?= ($key) ?>" value="0.00">
                                <?php } ?>
                            </p>
                            <p class="clearfix" id="shop_list<?= ($key) ?>" style="display: none">
                                <span><?= __('实体店铺') ?></span>
                                <select id="shop" name="shop" class="w70 vt valid" style="margin-left: 2px;">
                                    <option value="0"><?= __('请选择') ?></option>
                                    <?php
                                    if (count($val['shopEntityList']) > 0) {
                                        $shopEntityList = $val['shopEntityList'][0];
                                        foreach ($shopEntityList as $e => $entity) { ?>
                                            <option value="<?= $entity['entity_id'] ?>"><?= $entity['entity_name'] ?></option>

                                        <?php }
                                    } ?>
                                </select>
                            </p>

                            <?php if (!Web_ConfigModel::value('rate_service_status') || (Web_ConfigModel::value('rate_service_status') && $val['shop_self_support'] == 'true')) { ?>
                                <?php if ($dian_rate > 0) { ?>
                                    <p class="clearfix">
                                        <span><?= __('会员折扣') ?></span>
                                        <em></em>
                                        <i><?= __("-") ?><i class="shoprate<?= ($key) ?> shoprate"
                                                            shop_rate="<?= number_format($dian_rate, 2, '.', '') ?>"><?= number_format($dian_rate, 2, '.', '') ?></i></i>
                                    </p>
                                <?php } ?>

                            <?php } ?>

                            <p class="dian_total clearfix">
                                <span class=""><?= __('本店合计') ?></span>
                                <em></em>
                                <i id="sum_price" class="sprice<?= ($key) ?> sprice">
                                    <?php
                                    echo number_format($data['cost'][$key]['cost'] + $val['sprice'] - $val['mansong_info']['rule_discount'] - $dian_rate, 2, '.', '');
                                    ?>
                                </i>
                            </p>

                            <!--新增-->
                            <?php if (!empty($val['mansong_info'])) { ?>
                                <?php if ($val['mansong_info']['rule_discount']) { ?>
                                    <?php $reduced_money = $val['mansong_info']['rule_discount']; ?>
                                    <p class="clearfix">
                                        <span><i class="iconfont icon-manjian fln mr4 f22 middle"></i><?= __('满') ?><?= ($val['mansong_info']['rule_price']) ?><?= __('立减') ?><?= ($val['mansong_info']['rule_discount']) ?></span>
                                        <em></em>
                                        <i class="msprice<?= ($key) ?>"
                                           msprice="<?= ($val['mansong_info']['rule_discount']) ?>">
                                            -<?= ($val['mansong_info']['rule_discount']) ?>
                                        </i>
                                    </p>
                                <?php } ?>
                                <?php if ($val['mansong_info']['gift_goods_id']) { ?>
                                    <?= __('送') ?>&nbsp;<a
                                            href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&gid=<?= ($val['mansong_info']['gift_goods_id']) ?>"><img
                                                title="<?= ($val['mansong_info']['goods_name']) ?>"
                                                alt="<?= ($val['mansong_info']['goods_name']) ?>"
                                                src="<?= image_thumb($val['mansong_info']['goods_image'], 60, 60) ?>"></a>
                                    <?= ($val['mansong_info']['goods_name']) ?>
                                <?php } ?>
                            <?php } ?>

                            <?php if (isset($val['distributor_rate'])) { ?>
                                <p class="clearfix">
                                    <span><?= __('分销商优惠') ?></span>
                                    <i><?= number_format($val['distributor_rate'], 2, '.', '') ?></i>
                                </p>
                            <?php } ?>


                        </div>
                        <!-- S 平台红包  只有自营店铺可以使用平台红包-->
                        <input type="hidden" class="redpacked_id redpacket_<?= ($key) ?>">
                        <?php
                        if ($data['rpt_list_support_all']
                            || ($val['shop_self_support'] == 'true' && $data['rpt_list'])) {
                            ?>
                            <div class="hongb redpacket<?= ($key) ?>" style="margin-left: 0px;float: left">
                                <span><?= __('红包：') ?></span>
                                <div class="hongb-sel">
                                    <input type="hidden" class="red_shop_id" value="<?= ($key) ?>">
                                    <div class="hongb-text">
                                        <i class="icon icon-hongb"></i>
                                        <span><em class="redtitle"><?= __('请选择你的平台红包金额') ?></em><b class="price"><em
                                                        class="price redprice">0.00</em><?= __('￥') ?></b></span>
                                    </div>
                                    <div class="hongb-sel-btn" onclick="hongbmorebtn(this)" data="1"><i
                                                class="icon up"></i></div>
                                    <ul class="hongb-more">
                                        <li><?= __('请选择你的平台红包金额') ?></li>
                                        <?php
                                        foreach ($data['rpt_list_support_all'] as $redkey => $redval) {
                                            ?>
                                            <?php if ($shop_all_cost >= $redval['redpacket_t_orderlimit']) { ?>
                                                <li class="redpacket_list red<?= ($redval['redpacket_id']) ?>"
                                                    value="<?= $redval['redpacket_price'] ?>"
                                                    id="<?= ($redval['redpacket_id']) ?>">
                                                    <?= $redval['redpacket_title'] ?>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if ($val['shop_self_support'] == 'true' && $data['rpt_list']) { ?>
                                            <?php
                                            foreach ($data['rpt_list'] as $redkey => $redval) {
                                                ?>
                                                <?php if ($shop_all_cost >= $redval['redpacket_t_orderlimit']) { ?>
                                                    <li class="redpacket_list red<?= ($redval['redpacket_id']) ?>"
                                                        value="<?= $redval['redpacket_price'] ?>"
                                                        id="<?= ($redval['redpacket_id']) ?>">
                                                        <?= $redval['redpacket_title'] ?>
                                                    </li>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>
                        <!--- E 平台红包 ---->

                    </div>

                    <div class="tlr bgf">
                        <!--优惠劵-->

                        <?php if ($val['voucher_base'] || ($val['shop_self_support'] == 'true' && $data['rpt_list'])) { ?>
                            <p class="inline">
                                <select class="select" id="voucher<?= ($val['shop_id']) ?>">
                                    <option value="0" shop_id="<?= ($val['shop_id']) ?>">
                                        ----------------------------请选择优惠劵----------------------------
                                    </option>
                                    <?php foreach ($val['voucher_base'] as $voukey => $vouval) { ?>
                                        <!--判断店铺合计是否满足代金券的使用条件-->
                                        <?php if ($shop_all_cost >= $vouval['voucher_limit']) { ?>
                                            <option value="<?= ($vouval['voucher_price']) ?>"
                                                    voucher_id="<?= $vouval['voucher_id'] ?>"
                                                    shop_id="<?= ($vouval['voucher_shop_id']) ?>"><?= (Web_ConfigModel::value('monetary_unit')) ?><?= ($vouval['voucher_price']) ?>
                                                &nbsp;<?= ($vouval['voucher_title']) ?>&nbsp;<time><?= ($vouval['voucher_start_date']) ?></time> <?= __('-') ?>
                                                <time><?= ($vouval['voucher_end_date']) ?></time>
                                            </option>
                                        <?php } ?>
                                    <?php } ?>
                                    <!--									--><?php //if($data['rpt_list']){ ?>
                                    <!--										--><?php //foreach($data['rpt_list'] as $redkey => $redval){?>
                                    <!--											--><?php //if($shop_all_cost >= $redval['redpacket_t_orderlimit']){?>
                                    <!--												<option value="-->
                                    <? //=$redval['redpacket_price']?><!--" red_id="-->
                                    <? //=($redval['redpacket_id'])?><!--" shop_id="-->
                                    <? //=($val['shop_id'])?><!--">-->
                                    <? //=(Web_ConfigModel::value('monetary_unit'))?><!---->
                                    <? //=($redval['redpacket_price'])?><!--&nbsp;-->
                                    <? //=($redval['redpacket_title'])?><!--&nbsp;<time>-->
                                    <? //=($redval['redpacket_start_date'])?><!--</time> -->
                                    <? //=__('-')?><!-- <time>-->
                                    <? //=($redval['redpacket_end_date'])?><!--</time></option>-->
                                    <!--											--><?php //}?>
                                    <!--										--><?php //}?>
                                    <!--									--><?php //}?>

                                </select>
                            </p>
                        <?php } ?>
                        <!--如果有购物车商品所属店铺优惠券，则默认显示优惠券使用0-->
                        <?php if ($val['voucher_base'] || $data['rpt_list']) { ?>
                            <p class="inline vou-sels shop_voucher<?= ($key) ?>">
                                <span>代金券优惠</span>
                                <i shop_voucher="<?= $voucher_money ?>">
                                    <?= $voucher_money ?>
                                </i>
                            </p>

                        <?php } ?>
                    </div>
                    <?php
                    $total += $data['cost'][$key]['cost'] + $val['sprice'];
                    $total_dian_rate += $dian_rate;
                    //促销活动优惠的价格单独赋值一个变量
                    $promotion_reduced[] = $reduced_money; //满减
                    $voucher_reduced[] = $voucher_money;   //优惠劵
                    $promotion_money = array_sum($promotion_reduced) + array_sum($voucher_reduced);//促销活动优惠的总价格
                    } ?>

                    <div class="frank clearfix">
                        <div class="invoice tl">
                            <h3><?= __('发票信息') ?></h3>
                            <div class="invoice-cont">
                                <input type="hidden" name="invoice_id" value="" id='order_invoice_id'>
                                <input type="hidden" name="invoice_content" value="" id='order_invoice_content'>
                                <input type="hidden" name="invoice_title" value="" id='order_invoice_title'>
                                <span class="mr10 invoice-no"> <?= __('不开发票') ?> </span><a
                                        class="invoice-edit"><?= __('修改') ?><a style="margin-left: 5px;display: none"
                                                                               class="invoice-cancel"><?= __('取消') ?></a>
                            </div>
                        </div>

                        <p class="back_cart"><a id="back_cart"><i
                                        class="iconfont icon-iconjiantouzuo rel_top2"></i><?= __('返回我的购物车') ?></a></p>
                        <?php if (!Web_ConfigModel::value('rate_service_status') || (Web_ConfigModel::value('rate_service_status') && $val['shop_self_support'] == 'true')) {
                        } else {
                            $user_rate = 100;
                        } ?>
                        <p class="submit" style="text-align: center;" rate="<?php echo $user_rate; ?>">
                        <span>
                            <?= __('订单金额：') ?>
                            <strong>
                                <?= (Web_ConfigModel::value('monetary_unit')) ?><i id="total_order" class="total"
                                                                                   total_price="<?= (number_format($total, 2, '.', '')) ?>"><?= (number_format($total, 2, '.', '')) ?></i>
                                </strong>
                        </span>
                            <?php if (!Web_ConfigModel::value('rate_service_status') || (Web_ConfigModel::value('rate_service_status') && $val['shop_self_support'] == 'true')) { ?>
                                <?php if ($user_rate > 0) { ?>
                                    <?php if ($total_dian_rate > 0) { ?>
                                        <span>
								<?= __('会员折扣：') ?>
											<strong>
									-<?= (Web_ConfigModel::value('monetary_unit')) ?><i id="total_rate"
                                                                                        class="rate_total"
                                                                                        rate_total="<?= number_format($total_dian_rate, 2, '.', '') ?>"><?= number_format($total_dian_rate, 2, '.', '') ?></i>
								</strong>
							</span>
                                    <?php } ?>
                                <?php } else {
                                    $user_rate = 100;
                                } ?>
                            <?php } ?>
                            <span>
						<?php $after_total = number_format($total - $total_dian_rate - $promotion_money, 2, '.', ''); ?>
                                <?= __('支付金额：') ?>
								<strong class="common-color">
							<?= (Web_ConfigModel::value('monetary_unit')) ?><i id="total_pay"
                                                                               class="after_total bbc_color"
                                                                               after_total="<?= (number_format($after_total, 2, '.', '')) ?>"><?= (number_format($after_total, 2, '.', '')) ?></i>
						</strong>
					</span>
                            <!--					--><?php //echo $user_rate; ?>
                            <a id="pay_btn" class="bbc_btns"><?= __('提交订单') ?></a>
                        </p>

                    </div>
        </div>
    </div>

    <!-- 订单提交遮罩 -->
    <div id="mask_box" style="display:none;">
        <div class='loading-mask'></div>
        <div class="loading">
            <div class="loading-indicator">
                <img src="<?= $this->view->img ?>/large-loading.gif" width="32" height="32"
                     style="margin-right:8px;vertical-align:top;"/>
                <br/><span class="loading-msg"><?= __('正在提交订单，请稍后...') ?></span>
            </div>
        </div>
    </div>

    <script>
        var app_id = <?=(Yf_Registry::get('shop_app_id'))?>;
        var buy_able = <?=intval($buy_able) ? intval($buy_able) : 1?>;
        $("input[name='shipping_method']:radio:first").attr('checked', 'checked');
        $(function () {
            $(".remarks_content").val("");
            $(".remarks_content").keyup(function () {
                var len = $(this).val().length;
                if (len > 45) {
                    $(this).val($(this).val().substring(0, 45));
                }
            });
            var voucher_price = 0;
            var total_price = 0;//店铺加价购商品总金额
            var a_total_price = 0;//店铺加价购商品总金额
            var total_shop_price = 0;
            var old_order_price = parseFloat($('.submit').find('.total').attr('total_price'));//没选择加价购商品前的订单总金额
            var old_pay_price = parseFloat($('.submit').find('.after_total').attr('after_total'));//没选择加价购商品前的支付总金额

            //多个下拉框循环遍历获取下拉框下option个数，closest表示向上找到符合条件的第一个元素
            $('.select').each(function () {
                if ($(this)[0].options.length == 1) {
                    $(this).closest('div').remove();
                }
            });


            //下拉列表选中优惠券金额
            $(".select").change(function () {
                $(this).find("option[value=" + $(this).find('option').attr('voucher_id') + "]").attr("selected", true);
                var shop_id = $(this).find('option:selected').attr('shop_id');
                if ($(this).val() == 0) {
                    $(".shop_voucher" + shop_id).children("i").html($(this).val());
                    $(".shop_voucher" + shop_id).children("i").attr('shop_voucher', $(this).val());
                } else {
                    $(".shop_voucher" + shop_id).children("i").html("-" + $(this).val());
                    $(".shop_voucher" + shop_id).children("i").attr('shop_voucher', $(this).val());
                }
                //当选中的时候点击增加加价购商品，当前店铺总金额和结算总金额都要随着变化
                var shop_price = parseFloat($('.price' + shop_id).attr('total_shop_price'));//本店合计
                shop_save_money = 0;
                //循环累加当前店铺选中的加价购商品
                $('.clearfix.bgf.' + shop_id).each(function () {
                    if ($(this).find('input:checkbox').is(':checked')) {
                        var goods_price = parseFloat($(this).find('.select_increase:checked').attr('goods_price'));
                        var now_num = parseInt($(this).find('.select_increase:checked').parents('tr:eq(0)').find('.increase_num').val());
                        total_shop_price += (goods_price * now_num);
                    }
                })
                $(".select").find('option:selected').each(function () {
                    voucher_price += parseFloat($(this).val());
                })

                //选中加价购商品时，重新计算折扣金额
                var rate = parseFloat($('.submit').attr('rate'));


                //如果没有选中加价购商品而选择代金券时，店铺总价和折扣一起变化
                var hongb_price = $(".hongb-sel").find('.redprice').html() == undefined ? "0" : $(".hongb-sel").find('.redprice').html();
                var method = $("input[type='radio']:checked").parent('label').text().trim();
                var total_shipping = method == "自提" ? 0 : parseFloat($('#total_shipping').html());
                var shoprate = parseFloat($('.shoprate' + shop_id).attr('shop_rate')) ? parseFloat($('.shoprate' + shop_id).attr('shop_rate')) : 0;
                var shop_rate = shoprate;
                shop_save_money = parseFloat((total_shop_price * ((100 - rate) / 100)).toFixed(2));
                var msprice = parseFloat($('.msprice' + shop_id).attr('msprice')) ? parseFloat($('.msprice' + shop_id).attr('msprice')) : 0;

                //当前店铺总价和折扣显示
                $('.shoprate' + shop_id).html((shop_save_money + shop_rate).toFixed(2));
                //$('.price' + shop_id).html((shop_price + total_shop_price).toFixed(2));
                $('.sprice' + shop_id).html((shop_price + total_shop_price - (shop_save_money + shop_rate) - msprice - hongb_price + total_shipping - voucher_price).toFixed(2));
                //选中加价购商品时，重新计算折扣金额
                $('.clearfix.bgf.status').each(function () {
                    if ($(this).find('input:checkbox').is(':checked')) {
                        var goods_price = parseFloat($(this).find('.select_increase:checked').attr('goods_price'));
                        var now_num = parseInt($(this).find('.select_increase:checked').parents('tr:eq(0)').find('.increase_num').val());
                        a_total_price += +(goods_price * now_num);
                    }
                })

                //最后计算订单总价和总折扣
                var save_money = parseFloat((a_total_price * ((100 - rate) / 100)).toFixed(2));
                var voucher_rate = 0;
                //将各个店铺的折扣金额相加
                $('.shoprate').each(function () {
                    voucher_rate += parseFloat($(this).attr('shop_rate'));
                });
                var rate_total = voucher_rate;

                $('.rate_total').html((save_money + rate_total).toFixed(2));
                var pay_price = old_pay_price;
                if (method != "") {
                    if (method == "自提") {
                        pay_price = old_pay_price - parseFloat($('#total_shipping').html());
                    }
                }

                $('.submit').find('.total').html((old_order_price + a_total_price).toFixed(2));
                $('.submit').find('.after_total').html((pay_price + a_total_price - save_money - voucher_price - hongb_price).toFixed(2));
                total_price = 0;
                total_shop_price = 0;
                voucher_price = 0;
                a_total_price = 0;
                shop_rate = 0;

            });

            //-------

            //选择减少加价购商品数量
            $('.declick').on('click', function (e) {
                var num = parseInt($(this).next().val());
                var check_status = $(this).parents('tr:eq(0)').find('input:checkbox').is(':checked');
                var goods_price = parseFloat($(this).parents('tr:eq(0)').find('input:checkbox').attr('goods_price'));
                console.info($(this).parents('tr:eq(0)').find('.w150.tc').html());
                if (num > 1) {
                    $(this).next().val(num - 1);
                    jiagoods_price = parseFloat(((num - 1) * goods_price)).toFixed(2);
                    $(this).parents('tr:eq(0)').find('.jia-shop .fl em').html("<?=(Web_ConfigModel::value('monetary_unit'))?>" + jiagoods_price);
                    if (check_status) {
                        //当选中的时候点击减少加价购商品，当前店铺总金额和结算总金额都要随着变化
                        var shop_id = $(this).parents('tr:eq(0)').find('input:checkbox').attr('shop_id');
                        var voucher_price_shop = parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) ? parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) : 0;
                        var shop_price = parseFloat($(this).parents('tr:eq(0)').find('input:checkbox').attr('shop_price')) - voucher_price_shop;//本店合计
                        $(".select").find('option:selected').each(function () {
                            voucher_price += parseFloat($(this).val());
                        })
                        //循环累加当前店铺选中的加价购商品
                        $('.clearfix.bgf.' + shop_id).each(function () {
                            if ($(this).find('input:checkbox').is(':checked')) {
                                var goods_price = parseFloat($(this).find('.select_increase:checked').attr('goods_price'));
                                var now_num = parseInt($(this).find('.select_increase:checked').parents('tr:eq(0)').find('.increase_num').val());
                                total_shop_price += +(goods_price * now_num);
                            }
                        })

                        //选中加价购商品时，重新计算店铺折扣金额
                        var rate = parseFloat($('.submit').attr('rate'));
                        var shop_save_money = parseFloat((total_shop_price * ((100 - rate) / 100)).toFixed(2));

                        //如果没有选中加价购商品而选择代金券时，店铺总价和折扣一起变化
                        var voucher_price_total = parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) ? parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) : 0;

                        var shoprate = parseFloat($('.shoprate' + shop_id).attr('shop_rate')) ? parseFloat($('.shoprate' + shop_id).attr('shop_rate')) : 0;
//					var shop_rate = shoprate - parseFloat((voucher_price_total * ((100 - rate)/100)).toFixed(2));
                        var shop_rate = shoprate;

                        var msprice = parseFloat($('.msprice' + shop_id).attr('msprice')) ? parseFloat($('.msprice' + shop_id).attr('msprice')) : 0;

                        $('.shoprate' + shop_id).html((shop_save_money + shop_rate).toFixed(2));
                        $('.price' + shop_id).html((shop_price + total_shop_price).toFixed(2));
                        $('.sprice' + shop_id).html((shop_price + total_shop_price - (shop_save_money + shop_rate) - msprice).toFixed(2));
                        console.info(shop_rate);
                        //选中加价购商品时，重新计算折扣金额
                        $('.clearfix.bgf.status').each(function () {
                            if ($(this).find('input:checkbox').is(':checked')) {
                                var goods_price = parseFloat($(this).find('.select_increase:checked').attr('goods_price'));
                                var now_num = parseInt($(this).find('.select_increase:checked').parents('tr:eq(0)').find('.increase_num').val());
                                a_total_price += +(goods_price * now_num);
                            }
                        })

                        //最后计算订单总价和总折扣
                        var save_money = parseFloat((a_total_price * ((100 - rate) / 100)).toFixed(2));
                        var voucher_rate = 0;
                        //将各个店铺的折扣金额相加
                        $('.shoprate').each(function () {
                            voucher_rate += parseFloat($(this).attr('shop_rate'));
                        });
                        var rate_total = voucher_rate;

                        $('.rate_total').html((save_money + rate_total).toFixed(2));

                        $('.submit').find('.total').html((old_order_price + a_total_price).toFixed(2));
                        $('.submit').find('.after_total').html((old_pay_price + a_total_price - save_money - voucher_price).toFixed(2));

                        total_price = 0;
                        voucher_price = 0;
                        a_total_price = 0;
                        total_shop_price = 0;
                        shop_rate = 0;
                    }
                }

            })
            //选择增加加价购商品数量
            $('.inclick').on('click', function (e) {
                var num = parseInt($(this).prev().val());
                var num_max = parseInt($(this).prev().attr('data-max'));//最多购买数
                //点击增加按钮时判断当前加价购商品有没有被选中
                var check_status = $(this).parents('tr:eq(0)').find('input:checkbox').is(':checked');

                var goods_price = parseFloat($(this).parents('tr:eq(0)').find('input:checkbox').attr('goods_price'));

                if (num < num_max) {
                    $(this).prev().val(num + 1);
                    jiagoods_price = parseFloat(((num + 1) * goods_price)).toFixed(2);
                    $(this).parents('tr:eq(0)').find('.jia-shop .fl em').html("<?=(Web_ConfigModel::value('monetary_unit'))?>" + jiagoods_price);
                    if (check_status) {
                        //当选中的时候点击增加加价购商品，当前店铺总金额和结算总金额都要随着变化
                        var shop_id = $(this).parents('tr:eq(0)').find('input:checkbox').attr('shop_id');
                        var voucher_price_shop = parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) ? parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) : 0;
                        var shop_price = parseFloat($(this).parents('tr:eq(0)').find('input:checkbox').attr('shop_price')) - voucher_price_shop;//本店合计
                        $(".select").find('option:selected').each(function () {
                            voucher_price += parseFloat($(this).val());
                        })
                        //循环累加当前店铺选中的加价购商品
                        $('.clearfix.bgf.' + shop_id).each(function () {
                            if ($(this).find('input:checkbox').is(':checked')) {
                                var goods_price = parseFloat($(this).find('.select_increase:checked').attr('goods_price'));
                                var now_num = parseInt($(this).find('.select_increase:checked').parents('tr:eq(0)').find('.increase_num').val());
                                total_shop_price += +(goods_price * now_num);
                            }
                        })

                        //选中加价购商品时，重新计算店铺折扣金额
                        var rate = parseFloat($('.submit').attr('rate'));

                        var shop_save_money = parseFloat((total_shop_price * ((100 - rate) / 100)).toFixed(2));

                        //如果没有选中加价购商品而选择代金券时，店铺总价和折扣一起变化
                        var voucher_price_total = parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) ? parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) : 0;

                        var shoprate = parseFloat($('.shoprate' + shop_id).attr('shop_rate')) ? parseFloat($('.shoprate' + shop_id).attr('shop_rate')) : 0;

                        var shop_rate = shoprate;

                        var msprice = parseFloat($('.msprice' + shop_id).attr('msprice')) ? parseFloat($('.msprice' + shop_id).attr('msprice')) : 0;

                        $('.shoprate' + shop_id).html((shop_save_money + shop_rate).toFixed(2));
                        $('.price' + shop_id).html((shop_price + total_shop_price).toFixed(2));
                        $('.sprice' + shop_id).html((shop_price + total_shop_price - (shop_save_money + shop_rate) - msprice).toFixed(2));

                        //选中加价购商品时，重新计算折扣金额
                        $('.clearfix.bgf.status').each(function () {
                            if ($(this).find('input:checkbox').is(':checked')) {
                                var goods_price = parseFloat($(this).find('.select_increase:checked').attr('goods_price'));
                                var now_num = parseInt($(this).find('.select_increase:checked').parents('tr:eq(0)').find('.increase_num').val());
                                a_total_price += +(goods_price * now_num);
                            }
                        })

                        //最后计算订单总价和总折扣
                        var save_money = parseFloat((a_total_price * ((100 - rate) / 100)).toFixed(2));
                        var voucher_rate = 0;
                        //将各个店铺的折扣金额相加
                        $('.shoprate').each(function () {
                            voucher_rate += parseFloat($(this).attr('shop_rate'));
                        });
                        var rate_total = voucher_rate;

                        $('.rate_total').html((save_money + rate_total).toFixed(2));

                        $('.submit').find('.total').html((old_order_price + a_total_price).toFixed(2));

                        $('.submit').find('.after_total').html((old_pay_price + a_total_price - save_money - voucher_price).toFixed(2));

                        total_price = 0;
                        voucher_price = 0;
                        a_total_price = 0;
                        total_shop_price = 0;
                        shop_rate = 0;
                    }
                }
            })


            //-------


            //当输入框获取焦点时，获取当前的商品数量
            $('.increase_num').on('focus', function () {
                old_goods_num = parseInt($(this).val());
            })

            //判断加价购输入框手动输入的内容
            $('.increase_num').on('keyup', function () {
                //最大限购数量^[1-9]\\d*$
                var num_max = parseInt($(this).attr('data-max'));
                var check_status = $(this).parents('tr:eq(0)').find('input:checkbox').is(':checked');

                console.info(old_goods_num);

                if (!/^[1-9]\d*$/.test(this.value) || $(this).val() < 1) {
                    $(this).val(1);
                    $(this).blur();
                    //判断当前加价购商品是否被选中，如果选中将总计价格做相应修改
                    if (check_status) {
                        if (old_goods_num > 1) {
                            increase_rate_total(this);
                        }
                    }
                } else if ($(this).val() > num_max) {
                    $(this).val(num_max);
                    $(this).blur();
                    if (check_status) {
                        increase_rate_total(this);
                    }
                } else if ($(this).val() <= num_max) {
                    var now_num = $(this).val();
                    $(this).val(now_num);
                    $(this).blur();
                    if (check_status) {
                        increase_rate_total(this);
                    }
                }
            })

            var total_shop_price = 0;//店铺加价购商品总金额

            //点击选中一个加价购商品
            $('.select_increase').on('click', function () {
                var shop_id = $(this).attr('shop_id');
                var voucher_price_shop = parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) ? parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) : 0;
                var shop_price = parseFloat($(this).attr('shop_price')) - voucher_price_shop;//本店合计

                if ($(this).is(':checked')) {
                    $(this).attr('checked', true);
                    $(this).parents('.clearfix.bgf.' + shop_id).addClass('status');
                } else {
                    $(this).attr('checked', false);
                    $(this).parents('.clearfix.bgf.' + shop_id).removeClass('status');
                    //当某个店铺取消选择加价购商品时，将当前店铺的商品总价恢复，总订单金额改变
                    //循环累加当前店铺选中的加价购商品
                }

                $(".select").find('option:selected').each(function () {
                    voucher_price += parseFloat($(this).val());
                });
                //循环累加当前店铺选中的加价购商品
                $('.clearfix.bgf.' + shop_id).each(function () {
                    if ($(this).find('input:checkbox').is(':checked')) {
                        var goods_price = parseFloat($(this).find('.select_increase:checked').attr('goods_price'));
                        var now_num = parseInt($(this).find('.select_increase:checked').parents('tr:eq(0)').find('.increase_num').val());
                        total_shop_price += +(goods_price * now_num);
                    }
                })
                //选中加价购商品时，重新计算店铺折扣金额
                var hongb_price = $(".hongb-sel").find('.redprice').html() == undefined ? "0" : $(".hongb-sel").find('.redprice').html();//红包金额
                var rate = parseFloat($('.submit').attr('rate'));
                var shop_save_money = parseFloat((total_shop_price * ((100 - rate) / 100)).toFixed(2));

                //如果没有选中加价购商品而选择代金券时，店铺总价和折扣一起变化
                var voucher_price_total = parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) ? parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) : 0;

                var shoprate = parseFloat($('.shoprate' + shop_id).attr('shop_rate')) ? parseFloat($('.shoprate' + shop_id).attr('shop_rate')) : 0;
                var shop_rate = shoprate;

                var method = $("input[type='radio']:checked").parent('label').text().trim();
                var total_shipping = method == "自提" ? 0 : parseFloat($('#total_shipping').html());//运费

                var msprice = parseFloat($('.msprice' + shop_id).attr('msprice')) ? parseFloat($('.msprice' + shop_id).attr('msprice')) : 0;
                $('.shoprate' + shop_id).html((shop_save_money + shop_rate).toFixed(2));
                $('.price' + shop_id).html((shop_price + total_shop_price).toFixed(2));
                $('.sprice' + shop_id).html((shop_price + total_shop_price - (shop_save_money + shop_rate) - msprice + total_shipping - hongb_price).toFixed(2));

                $('.clearfix.bgf.status').each(function () {
                    if ($(this).find('input:checkbox').is(':checked')) {
                        var goods_price = parseFloat($(this).find('.select_increase:checked').attr('goods_price'));
                        var now_num = parseInt($(this).find('.select_increase:checked').parents('tr:eq(0)').find('.increase_num').val());
                        total_price += +(goods_price * now_num);
                    }
                })

                //最后计算订单总价和总折扣
                var save_money = parseFloat((total_price * ((100 - rate) / 100)).toFixed(2));
                var voucher_rate = 0;
                //将各个店铺的折扣金额相加
                $('.shoprate').each(function () {
                    voucher_rate += parseFloat($(this).attr('shop_rate'));
                });
                var rate_total = voucher_rate;
                $('.rate_total').html((save_money + rate_total).toFixed(2));

                var pay_price = old_pay_price;
                if (method != "") {
                    if (method == "自提") {
                        pay_price = old_pay_price - parseFloat($('#total_shipping').html());
                    }
                }
                $('.submit').find('.total').html((old_order_price + total_price).toFixed(2));
                $('.submit').find('.after_total').html((pay_price + total_price - save_money - voucher_price - hongb_price).toFixed(2));
                total_shop_price = 0;
                total_price = 0;
                voucher_price = 0;
            })

            //将加价购和商品折扣封装成一个函数
            function increase_rate_total(obj) {
                //当选中的时候点击增加加价购商品，当前店铺总金额和结算总金额都要随着变化
                var shop_id = $(obj).parents('tr:eq(0)').find('input:checkbox').attr('shop_id');
                var voucher_price_shop = parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) ? parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) : 0;
                var shop_price = parseFloat($(obj).parents('tr:eq(0)').find('input:checkbox').attr('shop_price')) - voucher_price_shop;//本店合计
                shop_save_money = 0;
                //循环累加当前店铺选中的加价购商品
                $('.clearfix.bgf.' + shop_id).each(function () {
                    if ($(this).find('input:checkbox').is(':checked')) {
                        var goods_price = parseFloat($(this).find('.select_increase:checked').attr('goods_price'));
                        var now_num = parseInt($(this).find('.select_increase:checked').parents('tr:eq(0)').find('.increase_num').val());
                        total_shop_price += +(goods_price * now_num);
                    }
                })

                $(".select").find('option:selected').each(function () {
                    voucher_price += parseFloat($(this).val());
                })

                //选中加价购商品时，重新计算折扣金额
                var rate = parseFloat($('.submit').attr('rate'));
                shop_save_money = parseFloat((total_shop_price * ((100 - rate) / 100)).toFixed(2));

                //如果没有选中加价购商品而选择代金券时，店铺总价和折扣一起变化
                var voucher_price_total = parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) ? parseFloat($('.shop_voucher' + shop_id).find('i').attr('shop_voucher')) : 0;

                var shoprate = parseFloat($('.shoprate' + shop_id).attr('shop_rate')) ? parseFloat($('.shoprate' + shop_id).attr('shop_rate')) : 0;
                var shop_rate = shoprate;

                var msprice = parseFloat($('.msprice' + shop_id).attr('msprice')) ? parseFloat($('.msprice' + shop_id).attr('msprice')) : 0;


                //当前店铺总价和折扣显示
                var hongb_price = $(".hongb-sel").find('.redprice').html()
                $('.shoprate' + shop_id).html((shop_save_money + shop_rate).toFixed(2));
                $('.price' + shop_id).html((shop_price + total_shop_price).toFixed(2));
                $('.sprice' + shop_id).html((shop_price + total_shop_price - (shop_save_money + shop_rate) - msprice - hongb_price).toFixed(2));


                //选中加价购商品时，重新计算折扣金额
                $('.clearfix.bgf.status').each(function () {
                    if ($(this).find('input:checkbox').is(':checked')) {
                        var goods_price = parseFloat($(this).find('.select_increase:checked').attr('goods_price'));
                        var now_num = parseInt($(this).find('.select_increase:checked').parents('tr:eq(0)').find('.increase_num').val());
                        a_total_price += +(goods_price * now_num);
                    }
                })

                //最后计算订单总价和总折扣
                var save_money = parseFloat((a_total_price * ((100 - rate) / 100)).toFixed(2));
                var voucher_rate = 0;
                //将各个店铺的折扣金额相加
                $('.shoprate').each(function () {
                    voucher_rate += parseFloat($(this).attr('shop_rate'));
                });
                var rate_total = voucher_rate;

                $('.rate_total').html((save_money + rate_total).toFixed(2));

                $('.submit').find('.total').html((old_order_price + a_total_price).toFixed(2));
                $('.submit').find('.after_total').html((old_pay_price + a_total_price - save_money - voucher_price - hongb_price).toFixed(2));
                total_price = 0;
                total_shop_price = 0;
                voucher_price = 0;
                a_total_price = 0;
                shop_rate = 0;
            }

            $('input:radio').click(function () {
                var shop_id = $(this).attr('shop_id');

                //选中加价购商品时，重新计算折扣金额
                var rate = parseFloat($('.submit').attr('rate'));
                //选中加价购商品时，重新计算折扣金额
                $('.clearfix.bgf.status').each(function () {
                    if ($(this).find('input:checkbox').is(':checked')) {
                        var goods_price = parseFloat($(this).find('.select_increase:checked').attr('goods_price'));
                        var now_num = parseInt($(this).find('.select_increase:checked').parents('tr:eq(0)').find('.increase_num').val());
                        total_shop_price += +(goods_price * now_num);
                    }
                })

                var hongb_price = $(".hongb-sel").find('.redprice').html() == undefined ? "0" : $(".hongb-sel").find('.redprice').html();//红包金额
                var voucher_price = $('#voucher' + shop_id).find('option:selected').val() == undefined ? "0" : $('#voucher' + shop_id).find('option:selected').val();
                var method = $("input[type='radio']:checked").parent('label').text().trim();
                var total_shipping = method == "自提" ? 0 : parseFloat($('#total_shipping').html());//运费
                var shoprate = parseFloat($('.shoprate' + shop_id).attr('shop_rate')) ? parseFloat($('.shoprate' + shop_id).attr('shop_rate')) : 0;//店铺折扣
                var shop_rate = shoprate;
                var shop_price = parseFloat($('.price' + shop_id).attr('total_shop_price'));//本店合计
                shop_save_money = parseFloat((total_shop_price * ((100 - rate) / 100)).toFixed(2));
                var msprice = parseFloat($('.msprice' + shop_id).attr('msprice')) ? parseFloat($('.msprice' + shop_id).attr('msprice')) : 0;

                $('.shoprate' + shop_id).html((shop_save_money + shop_rate).toFixed(2));//会员折扣
                // $('.price' + shop_id).html((shop_price + total_shop_price).toFixed(2));//商品金额
                $('.sprice' + shop_id).html((shop_price + total_shop_price - (shop_save_money + shop_rate) - msprice - hongb_price + total_shipping - voucher_price).toFixed(2));//本店合计

                //最后计算订单总价和总折扣
                var save_money = parseFloat((total_shop_price * ((100 - rate) / 100)).toFixed(2));
                var voucher_rate = 0;
                //将各个店铺的折扣金额相加
                $('.shoprate').each(function () {
                    voucher_rate += parseFloat($(this).attr('shop_rate'));
                });
                var rate_total = voucher_rate;

                $('.rate_total').html((save_money + rate_total).toFixed(2));
                var pay_price = old_pay_price;
                if (method != "") {
                    if (method == "自提") {
                        pay_price = old_pay_price - parseFloat($('#total_shipping').html());
                    }
                }
                $('.submit').find('.total').html((old_order_price + total_shop_price).toFixed(2));
                $('.submit').find('.after_total').html((pay_price + total_shop_price - save_money - voucher_price - hongb_price).toFixed(2));
                if (method == "自提") {
                    $('#shop_list' + shop_id).css('display', 'block');
                    $('#shipping_fee' + shop_id).css('display', 'none');
                } else {
                    $('#shop_list' + shop_id).css('display', 'none');
                    $('#shipping_fee' + shop_id).css('display', 'block');
                }
                total_price = 0;
                total_shop_price = 0;
                voucher_price = 0;
                a_total_price = 0;
                shop_rate = 0;
            });
            $('#shop').change(function () {
                $('#user_address').css('display', 'none');
                $('#shop_address').css('display', 'block');
                var entity_id = $(this).val();
                $("#shop_address").find("li").each(function () {
                    debugger;
                    var id = $(this).attr('id').substr(4);//addr3
                    if (id == entity_id) {
                        $(this).siblings().removeClass('add_choose');
                        $(this).addClass('add_choose');
                    }
                });

            });

            $('.pay_way').click(function () {
                if ($(this).hasClass('pay-selected')) {
                    if ($(this).html().indexOf("货到付款") > 0) {
                        //配送方式默认选择其他配送，并且自提不可用
                        $('input:radio').each(function () {
                            if ($(this).parent('label').text().trim() == "自提") {
                                $(this).attr('disabled', 'disabled');
                            } else {
                                $(this).attr('checked', 'checked');
                            }
                        })
                    } else {
                        $('input:radio').each(function () {
                            $(this).removeAttr('disabled');
                        })
                    }
                }

            });
        });
    </script>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>