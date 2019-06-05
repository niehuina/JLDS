<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<style>
    .dis_flag{display:inline-block;width:40px;background:red;color:#FFF;font-size:12px;text-align:center;}
</style>
<div class="search fn-clear">
    <a class="button btn_stock_check" style="float: left;" href="index.php?ctl=Seller_Stock_Order&met=stock_check&typ=e"><i class="iconfont icon-querendingdan"></i><?= __('库存盘点') ?></a>
    <form id="search_form" class="search_form_reset" method="get" action="<?= Yf_Registry::get('url') ?>">
        <input class="text w150" type="text" name="goods_key" value="<?=($goods_key?$goods_key:'');?>" placeholder="<?=__('请输入商品名称')?>"/>
        <input type="hidden" name="ctl" value="Seller_Stock_Order">
        <input type="hidden" name="met" value="user_stock">
        <a class="button refresh" href="index.php?ctl=Seller_Stock_Order&met=user_stock&typ=e"><i
                class="iconfont icon-huanyipi"></i></a>
        <a class="button btn_search_goods" href="javascript:void(0);"><i
                class="iconfont icon-btnsearch"></i><?= __('搜索') ?></a>
    </form>
</div>
<script type="text/javascript">
    $(".search").on("click", "a.button", function ()
    {
        $("#search_form").submit();
    });
</script>
<?php
if (!empty($goods)){

    if($this->shopBase['shop_type'] == 2)
    {
        $ctl = 'Supplier_Goods';
    }else{
        $ctl = 'Goods_Goods';
    }

    ?>
    <form id="form" method="post" action="index.php?ctl=Seller_Stock_Order&met=user_stock&typ=json">
        <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <th class="tl">
                    <label class="checkbox"><input class="checkall" type="checkbox"/></label><?=__('商品名称')?>
                </th>
                <th width="60"><?=__('价格')?></th>
                <th width="60"><?=__('VIP价格')?></th>
                <th width="60"><?=__('股东价格')?></th>
                <th width="80"><?=__('库存')?></th>
                <th width="100"><?=__('操作')?></th>
            </tr>
            <?php
            foreach ($goods['items'] as $stock_info){
                $item = $stock_info['common_info'];
                ?>
                <tr id="tr_common_id_<?= $item['common_id']; ?>">
                    <td class="tl th" colspan="99">
                        <label class="checkbox">
                            <input <?php if(isset($item['disabled_up'])&&$item['disabled_up']) echo 'disabled'; ?>
                                    class="checkitem" type="checkbox" name="chk[]" value="<?= $item['common_id'] ?>"
                                    is_virtual="<?= $item['common_is_virtual'] ?>"
                                    common_virtual_date="<?= $item['common_virtual_date'] ?>" />
                        </label><?=__('平台货号')?>:<?= $item['common_id']; ?>
                        <?php if(isset($item['disabled_up'])&&$item['disabled_up']) echo '<span style="color:red;">（'.__('供应商下架商品').'）</span>'; ?>
                    </td>
                </tr>
                <tr>
                    <td class="tl">
                        <dl class="fn-clear fn_dl">
                            <dt>
                                <i date-type="ajax_goods_list" data-id="237" class="iconfont icon-jia disb"></i>
                                <a href="index.php?ctl=<?=$ctl?>&met=goods&gid=<?= $stock_info['goods_id'] ?>"
                                   target="_blank"><img width="60" src="<?= $item['common_image'] ?>"></a>
                            </dt>
                            <dd>
                                <h3>
                                    <?php if($item['common_parent_id']){ ?>
                                        <span class="dis_flag"><?=__('分销')?></span>
                                    <?php } ?>
                                    <a href="index.php?ctl=<?=$ctl?>&met=goods&gid=<?= $stock_info['goods_id'] ?>"
                                       target="_blank"><?= $item['common_name'] ?></a>
                                </h3>

                                <p><?= $item['cat_name'] ?></p>

                                <p><?= ($item['common_code'] ? sprintf(__('商品条码').'：%s', $item['common_code']) : '') ?></p>
                            </dd>
                        </dl>
                    </td>
                    <td><?= format_money($item['common_price']); ?></td>
                    <td><?= format_money($item['common_price_vip']); ?></td>
                    <td><?= format_money($item['common_price_partner']); ?></td>
                    <td  <?php if($stock_info['goods_stock'] < $stock_info['alarm_stock']){?> class="colred" <?php }?>><?= $stock_info['goods_stock'] ?> <?=__('件')?></td>
                    <td>
                        <span class="edit">
                            <a href="javascript:setAlarm(<?= $stock_info['goods_id'] ?>)">
                                <i class="iconfont icon-zhifutijiao"></i><?=__('设置库存预警')?>
                            </a>
                        </span>
                    </td>
                </tr>
                <tr class="tr-goods-list" style="display: none;">
                    <td colspan="5" class="tl">
                        <ul class="fn-clear">
                            <?php if (!empty($goods_detail_rows[$item['common_id']])):
                                foreach ($goods_detail_rows[$item['common_id']] as $g_k => $g_v):
                                    ?>
                                    <li>
                                        <div class="goods-image">
                                            <a herf="" target="_blank">
                                                <img width="100" src="<?= $g_v['goods_image']; ?>">
                                            </a>
                                        </div>
                                        <?php if (!empty($g_v['spec']))
                                        {
                                            foreach ($g_v['spec'] as $ks => $vs):?>
                                                <div class="goods_spec"><?= $ks; ?>：<span><?= $vs ?></span></div>
                                            <?php endforeach;
                                        } ?>
                                        <div class="goods-price">
                                            <?=__('价格')?>：<span><?= format_money($g_v['goods_price']); ?></span></div>
                                        <div class="goods-stock"><?=__('库存')?>：<span><?= $g_v['goods_stock'] ?> <?=__('件')?></span></div>
                                        <a href="index.php?ctl=<?=$ctl?>&met=goods&gid=<?= $g_v['goods_id'] ?>"
                                           target="_blank"><?=__('查看商品详情')?></a>
                                    </li>
                                <?php
                                endforeach;
                            endif;
                            ?>

                        </ul>
                    </td>
                </tr>

            <?php } ?>
            <tr>
                <td colspan="99">
                    <div class="page">
                        <?= $page_nav ?>
                    </div>
                </td>
            </tr>
        </table>
    </form>
<?php }else{ ?>
    <form id="form" method="post" action="index.php?ctl=Seller_Goods&met=editGoodsCommon&typ=json">
        <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <th class="tl">
                    <label class="checkbox"><input class="checkall" type="checkbox"/></label><?=__('商品名称')?>
                </th>
                <th width="80"><?=__('价格')?></th>
                <th width="80"><?=__('库存')?></th>
                <th width="80"><?=__('发布时间')?></th>
                <th width="120"><?=__('操作')?></th>
            </tr>
        </table>
    </form>
    <div class="no_account">
        <img src="<?=$this->view->img?>/ico_none.png">
        <p><?= __('暂无符合条件的数据记录'); ?></p>
    </div>
<?php } ?>

<script type="text/javascript">
    var offt = true;
    $(document).ready(function ()
    {
        $(".table-list-style .disb").click(function ()
        {
            if (offt)
            {
                $(this).parent().parent().parent().parent().next().css("display", "table-row");
                $(this).removeClass("icon-jia");
                $(this).addClass("icon-jian");
                offt = false;
            }
            else
            {
                $(this).parent().parent().parent().parent().next().css("display", "none");
                $(this).removeClass("icon-jian");
                $(this).addClass("icon-jia");
                offt = true;
            }

        })
    })


    //
    window.setAlarm = function (goods_id) {
        var url = SITE_URL + '?ctl=Seller_Stock_Order&met=setAlarm&typ=';

        $.dialog({
            title: '设置库存预警值',
            content: 'url: ' + url + 'e' + "&goods_id="+goods_id,
            data: {goods_id: goods_id},
            height: 200,
            width: 600,
            lock: true,
            drag: false,
            ok: function () {

                var form_ser = $(this.content.set_alarm_form).serialize();

                $.post(url + 'json', form_ser, function (data) {
                    if (data.status == 200) {
                        Public.tips.success('设置成功！');
                        window.location.reload();
                        return true;
                    } else {
                        Public.tips.error('设置失败！');
                        return false;
                    }
                })
            }
        })
    }
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>