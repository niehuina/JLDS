<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet">
<script src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js" charset="utf-8"></script>
<script src="<?= $this->view->js ?>/seller_order.js" charset="utf-8"></script>
<script type='text/jade' id='thrid_opt'>
    <a class="bbc_seller_btns button add button_blue" href="<?= Yf_Registry::get('index_page') ?>?ctl=Seller_Stock_Order&met=add&typ=e">
        <i class="iconfont icon-jia"></i><?=__('添加新备货订单')?>
    </a>
</script>
<style>
    .dis_flag{display:inline-block;width:40px;background:red;color:#FFF;font-size:12px;text-align:center;}
</style>
<script type="text/javascript">
    $(function ()
    {
        $('.tabmenu').append($('#thrid_opt').html());
    });
</script>
</head>
<body>
<form>
    <table class="search-form">
        <tbody>
        <tr>
            <td>&nbsp;</td>
            <td><input type="checkbox" id="skip_off" value="1" <?php if (!empty($condition['order_status:<>'])) {
                    echo 'checked';
                } ?> name="skip_off"> <label class="relative_left" for="skip_off"><?=__('不显示已关闭的订单')?></label>
            </td>
            <th><?=__('下单时间')?></th>
            <td class="w240">
                <input type="text" class="text w70 hasDatepicker heigh" placeholder="<?=__('起始时间')?>" name="query_start_date" id="query_start_date" value="<?php if (!empty($condition['order_create_time:>='])) {
                    echo $condition['order_create_time:>='];
                } ?>" readonly="readonly"><label class="add-on"><i class="iconfont icon-rili"></i></label><span class="rili_ge">–</span>
                <input id="query_end_date" class="text w70 hasDatepicker heigh" placeholder="<?=__('结束时间')?>" type="text" name="query_end_date" value="<?php if (!empty($condition['order_create_time:<='])) {
                    $condition['order_create_time:<='];
                } ?>" readonly="readonly"><label class="add-on"><i class="iconfont icon-rili"></i></label>
            </td>
            <th><?=__('买家')?></th>
            <td class="w100">
                <input type="text" class="text w80" placeholder="<?=__('买家昵称')?>" id="query_buyer_name" name="query_buyer_name" value="<?php if (!empty($condition['buyer_user_name:LIKE'])) {
                    echo str_replace('%', '', $condition['buyer_user_name:LIKE']);
                } ?>"></td>
            <th><?=__('订单编号')?></th>
            <td class="w160">
                <input type="text" class="text w150 heigh" placeholder="<?=__('请输入订单编号')?>" id="query_order_sn" name="query_order_sn" value="<?php if (!empty($condition['order_id'])) {
                    echo $condition['query_order_sn'];
                } ?>"></td>
            <td class="w70 tc"><a onclick="formSub()" class="button btn_search_goods" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
                <input name="ctl" value="Seller_Trade_Order" type="hidden" /><input name="met" value="physical" type="hidden" />
            </td>
            <td class="mar"><a class="button refresh" onclick="location.reload()"><i class="iconfont icon-huanyipi"></i></a><td>
        </tr>
        </tbody>
    </table>
</form>

<table class="ncsc-default-table order ncsc-default-table2">
    <thead>
    <tr>
        <th class="w100"><?=__('下单时间')?></th>
        <th class="w100"><?=__('备货订单号')?></th>
        <th class="w100"><?=__('高级合伙人')?></th>
        <th class="w100"><?=__('订单金额')?></th>
        <th class="w90"><?=__('交易状态')?></th>
        <th class="w100"><?=__('发货时间')?></th>
        <th class="w120"><?=__('操作')?></th>
    </tr>
    </thead>

    <?php if ( !empty($data['items']) ) { ?>
        <?php foreach ( $data['items'] as $key => $val ) { ?>
            <tbody>
            <tr>
                <td>
                    <em><?= $val['order_create_time']; ?></em>
                </td>
                <td>
                    <em><?= $val['stock_order_id']; ?></em>
                </td>
                <td>
                    <em><?= $val['shop_user_name']; ?></em>
                </td>
                <td>
                    <em><?= $val['order_payment_amount']; ?></em>
                </td>
                <td>
                    <em><?= $val['order_status_html']; ?></em>
                    <p><a href="<?= $val['info_url']; ?>" target="_blank"><?=__('订单详情')?></a></p>
                </td>
                <td>
                    <em><?= $val['order_shipping_time']; ?></em>
                </td>
                <td>
                    <em><?= $val['set_html']; ?></em>
                </td>
            </tr>
            </tbody>
        <?php } ?>
    <?php } ?>
</table>

<?php if ( empty($data['items']) ) { ?>
    <div class="no_account">
        <img src="<?=$this->view->img?>/ico_none.png">
        <p><?=__('暂无符合条件的数据记录')?></p>
    </div>
<?php } ?>
<div class="page">
    <?= $data['page_nav']; ?>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

<script>

    $('.tabmenu > ul').find('li:gt(2)').remove();

    $(function () {

        //时间
        $('#query_start_date').datetimepicker({
            format: 'Y-m-d',
            timepicker: false,
            onShow: function (ct) {
                this.setOptions({
                    maxDate: $('#query_end_date').val() ? $('#query_end_date').val() : false
                })
            }
        });
        $('#query_end_date').datetimepicker({
            format: 'Y-m-d',
            timepicker: false,
            onShow: function (ct) {
                this.setOptions({
                    minDate: $('#query_start_date').val() ? $('#query_start_date').val() : false
                })
            },
        });

        //搜索

        var URL;

        $('input[type="submit"]').on('click', function (e) {

            e.preventDefault();

            URL = createQuery();
            window.location = URL;
        });

        function createQuery () {

            var url = SITE_URL + '?' + location.href.match(/ctl=\w+&met=\w+/) + '&';

            $('#query_start_date').val() && (url += 'query_start_date=' + $('#query_start_date').val() + '&');
            $('#query_end_date').val() && (url += 'query_end_date=' + $('#query_end_date').val() + '&');
            $('#buyer_name').val() && (url += 'query_buyer_name=' + $('#buyer_name').val() + '&');
            $('#order_sn').val() && (url += 'query_order_sn=' + $('#order_sn').val() + '&');

            return url;
        }

        //取消订单
        $('a[dialog_id="seller_order_cancel_order"]').on('click', function () {

            var order_id = $(this).data('order_id'),
                url = SITE_URL + '?ctl=Seller_Stock_Order&met=cancelOrder&typ=';

            $.dialog({
                title: '<?=__('取消订单')?>',
                content: 'url: ' + url + 'e',
                data: { order_id: order_id },
                height: 250,
                width: 400,
                lock: true,
                drag: false,
                ok: function () {

                    var form_ser = $(this.content.order_cancel_form).serialize();

                    $.post(url + 'json', form_ser, function (data) {
                        if ( data.status == 200 ) {
                            parent.Public.tips({
                                content: '<?=__('修改成功')?>',
                                type: 3
                            }), window.location.reload();
                            return true;
                        } else {
                            parent.Public.tips({
                                content: '<?=__('修改失败')?>',
                                type: 1
                            });
                            return false;
                        }
                    })
                }
            })
        });
    });

    function formSub(){
        $('.search-form').parents('form').submit();
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
