<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';

$static_url = Yf_Registry::get('static_url');

?>
<?php if ($uorder_base) { ?>

    <form style="padding-left:6px;">
        <div class="recharge2-content-top content-public clearfix">
            <div class="header">
                <div class="title">
                    <a class="arrow-box" href="<?=Yf_Registry::get('shop_wap_url')?>tmpl/order/order_detail.html?order_id=<?=$uorder_base['inorder']?>"></a>
                    一次方商城收银台
                </div>
                <?php if (!empty($banner)) { ?>
                    <div class="banner">
                        <a href="<?= ($banner['bannber_url']) ?>">
                            <img src="<?= ($banner['banner_image']) ?>" alt="">
                        </a></div>
                <?php } ?>
            </div>
            <div class="clearfix pay-head">
                <div class="fl">
                    <dl>
                        <dt><?= _('支付单号：') ?></dt>
                        <dd><?= ($uorder_base['union_order_id']) ?></dd>
                    </dl>
                </div>
                <div class="fr">
                    <dl>
                        <dt><?= _('支付金额：') ?></dt>
                        <dd><?= format_money($uorder_base['trade_payment_amount']) ?></dd>
                        <!--  订单需要支付的金额  -->
                        <input type="hidden" name="pay_amount" value="<?= ($uorder_base['trade_payment_amount']) ?>">
                    </dl>
                </div>
            </div>


            <div class="module-new">


                <?php if (($user_resource['user_recharge_card'] > 0 || $user_resource['user_money'] > 0 || isset($bt_money)) && !$act) { ?>

                    <p class="yue_pay"><?= _('余额支付') ?></p>
                    <div class="box clearfix">
                        <?php if ($user_resource['user_recharge_card'] > 0) { ?>

                            <div>
                                <input type="checkbox" class="pay_yue" name="choice" value="cards"
                                       id='cards_checkbox'>
                                <label for="cards_checkbox">
                                    <?= _('购物卡（可用余额：') ?><?= format_money($user_resource['user_recharge_card']) ?>
                                    ）
                                    <input type="hidden" name="cards" id="cards"
                                           value="<?= ($user_resource['user_recharge_card']) ?>">
                                    <!--  用购物卡支付的金额  -->
                                    <input type="hidden" name="cards_pay" id="cards_pay" value="0"/>
                                    <span id="pay_yue_cards_span" style="margin-left:73px;color:red;"></span></label>
                            </div>
                        <?php } ?>

                        <?php if ($user_resource['user_money'] > 0) { ?>
                            <div>
                                <input type="checkbox" class="pay_yue" name="choice" value="money"
                                       id='money_checkbox'>

                                <label for="money_checkbox"><?= _('预存款（可用余额：') ?><?= format_money($user_resource['user_money']) ?>
                                    ）

                                    <input type="hidden" name="money" id="money"
                                           value="<?= ($user_resource['user_money']) ?>">
                                    <!--  用余额支付的金额  -->
                                    <input type="hidden" name="money_pay" id="money_pay" value="0"/>
                                    <span id="pay_yue_money_span" style="margin-left:73px;color:red;"></span>
                                </label>
                            </div>
                        <?php } ?>
                        <?php if (isset($bt_money) && $bt_money > $uorder_base['trade_payment_amount']) { ?>
                            <div>
                                <input type="checkbox" class="pay_yue" name="choice"
                                       value="bt"><?= _('使用白条支付（可用额度：') ?><?= format_money($bt_money) ?>）
                                <input type="hidden" name="bt" id="bt" value="<?= $bt_money ?>">
                                <!--  用白条支付的金额  -->
                                <input type="hidden" name="bt_pay" id="bt_pay" value="0"/>
                                <span id="pay_yue_bt_span"
                                      style="margin-left:73px;color:red;">(注：白条不能与其他支付方式同时使用)</span>
                            </div>
                        <?php } ?>
                        <div id="pay_password" style="display: none;">
                            <span class="spanmt"><?= _('支付密码') ?> :&nbsp;</span>
                            <input type="password" name="password" class="text text-1"/>
                            <span class="msg-box red"></span>
                        </div>

                    </div>

                <?php } ?>
                <!--  最后在线支付需要支付的金额  -->
                <input type="hidden" name="online_pay" id="online_pay" autocomplete="off"
                       value="<?= ($uorder_base['trade_payment_amount']) ?>">

                <div class="online_pay">
                    <p class="online_title tl"><?= _('在线支付') ?> </p>
                    <?php foreach ($payment_channel as $key => $val) { ?>
                        <div class="box-public box<?= ($key) ?>">
                            <a href="javascript:;">
                                <div class="mallbox-public">
                                    <img src="<?= ($val['payment_channel_image']) ?>"
                                         alt="<?= ($val['payment_channel_name']) ?>"/>
                                    <input type="hidden" name="payway_name" class="payway_name"
                                           value="<?= ($val['payment_channel_code']) ?>">
                                </div>
                            </a>
                            <span class="sel_icon"></span>
                        </div>
                    <?php } ?>
                    <input type="hidden" name="online_payway" class="online_payway" id="online_payway">

                </div>

                <?php if ($sub_user_status && !$act) { ?>
                    <p class="yue_pay">选择代支付</p>
                    <div class="sub_user_pay box">
                        <input type="radio" name="sub_user_payway" class="sub_user_payway" id="sub_user_payway"
                               value="1"><?= ('主管账号支付') ?>
                    </div>
                <?php } ?>

                <div class="recharge2-content-center content-public">
                    <div class="pc_trans_btn clearfix"><a id="submit" class="btn_big btn_active submit_disable"
                                                          style="float:left;"><?= _('确认付款') ?></a></div>
                    <!--<div id="test">TEst</div>-->
                </div>
            </div>
        </div>
        <div class="recharge2-content-bottom content-public">
            <div class="theme" style="margin-top:60px;">
                <span class="title"><?= _('支付遇到问题') ?></span>
            </div>
            <div class="content">
                <div class="one">
                    <h3><?= _(' 1.我还能用信用卡进行网购么？ ') ?></h3>
                    <p class="texts"><?= _('答：您在带有信用卡小标识') ?><?= _('的店铺购物，可以直接使用信用卡快捷（含卡通）、网银进行信用卡支付，支付限额为您的卡面额度。在没有信用卡标识的店铺购物 时，您可以使用信用卡快捷（含卡通）、网银进行信用卡支付，月累计支付限额不超过500元。') ?>
                    </p>
                </div>
                <div class="one">
                    <h3><?= _('2.没有网上银行，怎么用银行卡充值？') ?></h3>
                    <p class="texts"><?= _('答：储蓄卡用户，请使用储蓄卡快捷支付充值，开通后只需输入网付宝支付密码，即可完成充值。') ?></p>
                </div>
                <div class="one">
                    <h3><?= _('3.怎样在网上开通储蓄卡快捷支付(含卡通)？') ?> </h3>
                    <p class="texts"><?= _('答：已支持国内大部分主流银行在线开通。在网付宝填写信息后，根据页面引导在网上银行完成开通。') ?></p>
                </div>
            </div>
        </div>
    </form>

<?php } else { ?>
    <?= _('订单号无效，请确认后再付款。') ?>
<?php } ?>


    <!-- 订单提交遮罩 -->
    <div id="mask_box" style="display:none;">
        <div class='loading-mask'></div>
        <div class="loading">
            <div class="loading-indicator">
                <!--<img src="<? /*= $this->view->img */ ?>/large-loading.gif" width="32" height="32" style="margin-right:8px;vertical-align:top;"/>-->
                <br/><span class="loading-msg"><?= _('正在付款，请稍后...') ?></span>
            </div>
        </div>
    </div>

    <style>
        @media screen and (max-width: 768px) {
            .hd_content, .recharge2-content-bottom, .footer {
                display: none;
            }

            form {
                padding: 0 !important;
            }

            .recharge2-content-top {
                padding: 0 !important;
                margin: 0 !important;
            }

            .module-new {
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;;
            }

            .pay-head {
                border-bottom: 1px solid #ddd;
            }

            .pay-head .fr {
                float: left !important;
            }

            .yue_pay, .online_title {
                border-left: none !important;
                padding: 0 10px !important;
                height: 50px;
                line-height: 50px;
                border-bottom: 1px solid #ddd;
                background: #f3f3f3;
                margin: 0 !important;
            }

            .header .title a {
                position: absolute;
                color: #b9b9b9;
                left: 0;
                font-size:24px ;
                font-weight: bold;
                display: block;
                width: 40px;

            }

            .header .title {
                font-size: 16px;
                height: 40px;
                line-height: 40px;
                border-bottom: 1px solid #ddd;
                background: #06c1ae;
                position: relative;
                text-align: center;
            }

            .header .banner img {
                width: 100%;
            }

            .recharge2-content-top .box-public {
                display: block !important;
                margin: 0 !important;
                border: none !important;
                border-bottom: 1px solid #ddd !important;
                width: 99.5% !important;
            }

            .box input[type="checkbox"], .box input[type="radio"] {
                height: 24px !important;
                width: 24px !important;
                position: absolute !important;
                top: 14px;
            }

            .box input[type="checkbox"]:checked:after, .box input[type="radio"]:checked:after {
                top: 3px !important;
                left: 2px !important;
                height: 10px !important;
                width: 16px !important;
            }

            .recharge2-content-top .box {
                width: 100% !important;
            }

            .recharge2-content-top .box div {
                border: none !important;
                margin: 0 !important;
                padding: 0 20px !important;
                border-bottom: 1px solid #ddd !important;
                position: relative !important;
            }

            .recharge2-content-top .box div#pay_password {
                padding: 10px 20px !important;
            }

            .recharge2-content-top .box div label {
                display: block;
                width: 100%;
                padding-left: 40px;
                height: 50px;
                line-height: 50px;

            }

            .recharge2-content-center {
                margin-top: 0 !important;
            }

            .recharge2-content-top .box-public.pay_method_sel {
                border: 1px solid #17ba55 !important;
            }

            .btn_big {
                width: 90% !important;
            }

            .header .title a.arrow-box{
                left: 14px;
                top: 13px;
                content: "";
                display: inline-block;
                height: 11px;
                width: 11px;
                border-width: 0 0 2px 2px;
                border-color: #bbeee9;
                border-style: solid;
                transform: matrix(0.71, 0.71, -.71, 0.71, 0, 0);
                -webkit-transform: matrix(0.71, 0.71, -.71, 0.71, 0, 0);
            }
        }

        @media screen and (min-width: 768px) {
            .header {
                display: none
            }
        }
    </style>
    <script>
        $(function () {
            $(".box0").click();
        });

        //选择主管账号付款后，其他付款方式选择取消
        $("#sub_user_payway").click(function () {
            $("#pay_password").hide();
            //取消余额支付
            $("[name=choice]:checkbox").prop("checked", false);
            //取消已选择的在线支付方式
            if ($(".online_pay>.pay_method_sel").size() > 0) {
                $('.box-public').removeClass("pay_method_sel");
                $("input[type='hidden'][name='online_payway']").val('');
                //主管账号的选择
                $("#sub_user_payway").prop("checked", true);
            }
            var online_money = 0;
            $(".online_money").html(Number(online_money).toFixed(2));
            $("#online_pay").val(online_money);
        });

        $("input[type='checkbox']").prop('checked', false);
        $(".pay_yue").click(function () {

            //取消主管账号的选择
            $("#sub_user_payway").prop("checked", false);

            var pay_type = $(this).val();

            //白条不与其他支付方式同时使用
            if (pay_type === 'bt') {
                $("input[type='checkbox'][value='cards']").attr('checked', false);
                $("input[type='checkbox'][value='money']").attr('checked', false);
                $('.box-public').removeClass("pay_method_sel");
                $("input[type='hidden'][name='online_payway']").val('');
            } else {
                $("input[type='checkbox'][value='bt']").attr('checked', false);
            }
            if ($("input[class='pay_yue']:checked").length > 0) {
                $("#pay_password").show();
            } else {
                $("#pay_password").hide();
            }
            $('.box-public').removeClass("pay_method_sel");
            $("input[type='hidden'][name='online_payway']").val('');
            var online_money = Number($("input[type='hidden'][name='pay_amount']").val());
            $(".online_money").html(online_money.toFixed(2));
            $("#online_pay").val(online_money);


        });

        function checkPassword() {
            if (!checkMoney()) {
                Public.tips.error('账户余额不足');
                return false;
            }
            var display = $("#pay_password").css('display');
            if (display == 'block' || display == undefined) {
                var flag = false;
                $.ajaxSetup({
                    async: false
                });
                $.post(SITE_URL + "?ctl=Info&met=checkPassword&typ=json", {password: $("input[name='password']").val()},
                    function (data) {
                        var winWidth = $(window).width();
                        var url = SITE_URL + '?ctl=Info&met=passwd';
                        if(winWidth <= 768){
                            url = WAP_URL + '/tmpl/member/pay_password_edit.html';
                        }
                        if (data.status == 250) {
                            $(".msg-box").html(data.msg + "，<a style='color: hsl(205, 66%, 48%);border-bottom-color: hsl(206, 57%, 76%);text-decoration: underline;' href=' " + url + "'>忘记支付密码</a>");

                            $("#submit").addClass("submit_disable");
                            $("#submit").removeClass("submit_able");

                            return false;
                        }
                        else if (data.status == 230) {
                            $(".msg-box").html("<a style='color:red;' href=' " + url + "'>请设置支付密码</a>");

                            $("#submit").addClass("submit_disable");
                            $("#submit").removeClass("submit_able");

                            return false;
                        }
                        else {
                            $(".msg-box").html("");
                            $("#submit").removeClass("submit_disable");
                            $("#submit").addClass("submit_able");
                            flag = true;
                            return true;
                        }
                    }
                );
                return flag;
            }
            return false;
        }

        $(".box-public").click(function () {
            //取消主管账号的选择
            $("#sub_user_payway").prop("checked", false);
            $("input[type='checkbox']").prop("checked", false);
            $("#pay_password").hide();
            var online_money = Number($("input[type='hidden'][name='pay_amount']").val());
            $(".online_money").html(online_money.toFixed(2));
            $("#online_pay").val(online_money);
            $('.box-public').removeClass("pay_method_sel");
            $(this).addClass("pay_method_sel");

            $("input[type='hidden'][name='online_payway']").val($(this).find("input[type='hidden'][name='payway_name']").val());

            if (document.getElementById("pay_password") && !$("#pay_password").is(":hidden")) {
                checkPassword();
            }
            else {
                $("#submit").addClass("submit_able");
                $("#submit").removeClass("submit_disable");
            }

        });

        $("#submit").click(function () {
            //是否使用主管账号支付
            var sub_user_payway = $("input[type='radio'][name='sub_user_payway']:checked").val();
            var pay_user_way = $('#online_payway').val();
            if (sub_user_payway == 1) {
                var uorder_id = '<?=($uorder)?>';

                $.post(SITE_URL + "?ctl=Pay&met=subpay&typ=json", {trade_id: uorder_id}, function (data) {
                    if (data.status == 200) {
                        window.location.href = data.data.return_app_url + '?ctl=Buyer_Order&met=<?=$order_g_type?>';
                    }
                    else {
                        $(".loading-msg").html('支付失败，请重新支付！');
                        window.location.reload();
                    }
                })
            }
            else if (pay_user_way) {
                paySubmit($(this));
            }
            else {
                var checked = checkPassword();
                if (checked === true) {
                    paySubmit($(this));
                }
            }

            return false;
        });

        function paySubmit(e) {
            if (e.hasClass("submit_able")) {
                e.addClass("submit_disable").removeClass("submit_able");
                $("body").css("overflow", "hidden");
                $("#mask_box").show();
                var uorder_id = '<?=($uorder)?>';
                data = {trade_id: uorder_id}

                var card_payway = $("input[type='checkbox'][value='cards']").is(':checked');
                var money_payway = $("input[type='checkbox'][value='money']").is(':checked');
                var bt_payway = $("input[type='checkbox'][value='bt']").is(':checked');
                var online_payway = $("input[type='hidden'][name='online_payway']").val();
                //将选用的付款方式保存如数据库
                data = {
                    card_payway: card_payway,
                    money_payway: money_payway,
                    bt_payway: bt_payway,
                    online_payway: online_payway,
                    uorder_id: uorder_id
                };
                $.post(SITE_URL + "?ctl=Info&met=checkPayWay&typ=json", data,
                    function (data) {
                        if (data.status == 200) {
                            //如果选择了在线支付方式
                            if (online_payway) {
                                if (online_payway == "wx_native" && IsPC() == false) {
                                    window.location.href = SITE_URL + "?ctl=Pay&met=" + online_payway + "&trade_id=" + uorder_id + "&trade_type=H5";
                                } else {
                                    window.location.href = SITE_URL + "?ctl=Pay&met=" + online_payway + "&trade_id=" + uorder_id;
                                }
                            }
                            else {
                                $.post(SITE_URL + "?ctl=Pay&met=money&typ=json", {trade_id: uorder_id}, function (data) {
                                    if (data.status == 200) {
                                        window.location.href = data.data.return_app_url + '?ctl=Buyer_Order&met=<?=$order_g_type?>';
                                    }
                                    else {
                                        e.addClass("submit_able").removeClass("submit_disable");
                                        $(".loading-msg").html('支付失败，请重新支付！');
                                        window.location.reload();
                                    }
                                })
                            }
                        }
                        else {
                            Public.tips.error('支付失败');
                        }
                    }
                );
            }
        }

        function getMoney() {
            var online = Number($("input[type='hidden'][name='pay_amount']").val());
            if ($("input[type='checkbox'][value='money']").prop('checked') && $("input[type='checkbox'][value='cards']").prop('checked')) {
                var money = Number($("input[type='hidden'][name='money']").val()) + Number($("input[type='hidden'][name='cards']").val());
            } else if ($("input[type='checkbox'][value='money']").prop('checked')) {
                //用户余额
                var money = Number($("input[type='hidden'][name='money']").val());
            } else if ($("input[type='checkbox'][value='cards']").prop('checked')) {
                var money = Number($("input[type='hidden'][name='cards']").val());
            } else {
                var money = 0;
            }
            var result = online - money;
            if (result < 0) {
                result = 0;
            }
            return result;
        }

        function IsPC() {
            var userAgentInfo = navigator.userAgent;
            var Agents = ["Android", "iPhone",
                "SymbianOS", "Windows Phone",
                "iPad", "iPod"];
            var flag = true;
            for (var v = 0; v < Agents.length; v++) {
                if (userAgentInfo.indexOf(Agents[v]) > 0) {
                    flag = false;
                    break;
                }
            }
            return flag;
        }

        function checkMoney() {
            var online_money = Number($("input[type='hidden'][name='pay_amount']").val());
            var card_payway = $("input[type='checkbox'][value='cards']").is(':checked');
            var money_payway = $("input[type='checkbox'][value='money']").is(':checked');
            var bt_payway = $("input[type='checkbox'][value='bt']").is(':checked');
            var money = 0
            if (bt_payway) {
                money = Number($('#bt').val());
            }
            if (card_payway && money_payway) {
                money = Number($('#money').val()) + Number($('#cards').val());
            }
            if (card_payway && !money_payway) {
                money = Number($('#cards').val());
            }
            if (!card_payway && money_payway) {
                money = Number($('#money').val());
            }
            if (money < online_money) {
                return false
            } else {
                return true;
            }
        }
    </script>

    <script type="application/javascript">
        //如果当前是手机端微信浏览器，隐藏支付宝
        $(function () {

            var $alipay = $("[name=\"payway_name\"][value=\"alipay\"]");
            if ($alipay.get(0)) {
                var ua = navigator.userAgent.toLowerCase();

                if (ua.match(/MicroMessenger/i) == "micromessenger") {
                    var alipayOption = $alipay.parents("div:eq(1)");
                    $(alipayOption).hide();

                    $("input[type='hidden'][name='online_payway']").val('wx_native');
                    $("input[type='hidden'][value='wx_native']").parents('div').addClass('pay_method_sel');
                }
            }


            $("form").on('submit', function (e) {
                e.preventDefault();
                $("#submit").trigger("click");
            })
            /*$(".recharge2-content-top .box div").not('input').click(function () {
                $(this).find('input[type="checkbox"]').click();
            });*/
        })
    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>