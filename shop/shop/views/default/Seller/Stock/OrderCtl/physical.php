<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet">
<script src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js" charset="utf-8"></script>
<script type='text/jade' id='thrid_opt'>
    <?php if(self::$is_partner){ ?>
    <?php } else{ ?>
    <a class="bbc_seller_btns button add button_blue" href="<?= Yf_Registry::get('index_page') ?>?ctl=Seller_Stock_Order&met=add&typ=e">
        <i class="iconfont icon-jia"></i><?=__('创建备货订单')?>
    </a>
    <?php } ?>
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
            <td>
                <input type="checkbox" id="skip_off" value="1" <?php if (!empty($condition['order_status:<>'])) {
                    echo 'checked';
                } ?> name="skip_off"> <label class="relative_left" for="skip_off"><?=__('不显示已关闭的订单')?></label>
            </td>
            <th><?=__('下单时间')?></th>
            <td class="w240">
                <input type="text" class="text w70 hasDatepicker heigh" placeholder="<?=__('起始时间')?>" name="query_start_date" id="query_start_date" value="<?=$_GET['query_start_date']?>" readonly="readonly"><label class="add-on"><i class="iconfont icon-rili"></i></label><span class="rili_ge">–</span>
                <input id="query_end_date" class="text w70 hasDatepicker heigh" placeholder="<?=__('结束时间')?>" type="text" name="query_end_date" value="<?=$_GET['query_end_date']?>" readonly="readonly"><label class="add-on"><i class="iconfont icon-rili"></i></label>
            </td>
            <th><?=__('订单编号')?></th>
            <td class="w160">
                <input type="text" class="text w150 heigh" placeholder="<?=__('请输入订单编号')?>" id="query_order_sn" name="query_order_sn" value="<?=$_GET['query_order_sn']?>">
            </td>
            <td class="w70 tc">
                <a onclick="formSub()" class="button btn_search_goods" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
                <input name="ctl" value="Seller_Stock_Order" type="hidden" /><input name="met" value="physical" type="hidden" />
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
        <th class="w200"><?=__('备货订单号')?></th>
        <th class="w100"><?=__('高级合伙人')?></th>
        <th class="w100"><?=__('订单金额')?></th>
        <th class="w90"><?=__('交易状态')?></th>
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
                    <em><?= $val['order_payment_amount_vip']; ?></em>
                </td>
                <td>
                    <em><?= $val['order_status_html']; ?></em>
                    <p><a href="<?= $val['info_url']; ?>" target="_blank"><?=__('订单详情')?></a></p>
                </td>
                <td>
                    <em><?= $val['set_html']; ?></em>
                    <!-- 订单删除 -->
                    <?php if(($val['order_status'] == Order_StateModel::ORDER_CANCEL || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $val['order_shop_hidden'] == 0):?>

                        <em><a onclick="hideOrder('<?=$val['stock_order_id']?>')"><i class="iconfont icon-lajitong icon_size22"></i><?=__('删除订单')?></a></em>

                    <?php endif; ?>
                    <?php if(($val['order_status'] == Order_StateModel::ORDER_CANCEL || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $val['order_shop_hidden'] == 1):?>

                        <em><a onclick="restoreOrder('<?=$val['stock_order_id']?>')"><i class="iconfont icon-huanyuan icon_size22"></i><?=__('还原订单')?></a></em>

                    <?php endif; ?>
                    <?php if(($val['order_status'] == Order_StateModel::ORDER_CANCEL || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $val['order_shop_hidden'] == 1):?>

                        <em><a onclick="delOrder('<?=$val['stock_order_id']?>')"><i class="iconfont icon-lajitong icon_size22"></i><?=__('彻底删除')?></a></em>

                    <?php endif; ?>
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
    <?= $page_nav; ?>
</div>

<script>
    $('.tabmenu > ul').find('li:gt(2)').remove();
</script>
<script type="text/javascript" src="<?=$this->view->js?>/stock_physical.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>
