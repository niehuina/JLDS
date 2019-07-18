<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<style>
    .form-style dl dt, .form-style dl dd {
        padding: 0;
    }
    .goods .form-style dl dt, .goods .form-style dl dd {
        padding: 0;
    }
    .goods .form-style dl dd{
        padding: 0 12px;
    }
    a.ncbtn-mini {
        line-height: 16px;
        height: 16px;
        padding: 3px 7px;
        border-radius: 2px;
    }
</style>
<link href="<?= $this->view->css ?>/ui.min.css?ver=20140430" rel="stylesheet">
<script src="<?=$this->view->js_com?>/plugins/jquery.jqgrid.js" ></script>
<div class="goods-category">
    <ol class="step_order fn-clear add-goods-step clearfix">
        <li>
            <i class="icon iconfont icon-icoordermsg"></i>
            <h6><?=__('STEP 1')?></h6>

            <h2><?=__('选择高级合伙人')?></h2>
            <i class="arrow iconfont icon-btnrightarrow"></i>
        </li>
        <li class="cur">
            <i class="icon iconfont icon-shangjiaruzhushenqing bbc_seller_color"></i>
            <h6 class="bbc_seller_color"><?=__('STEP 2')?></h6>

            <h2 class="bbc_seller_color"><?=__('选择商品')?></h2>
            <i class="arrow iconfont icon-btnrightarrow"></i>
        </li>
        <li>
            <i class="icon iconfont icon-icoduigou"></i>
            <h6><?=__('STEP 3')?></h6>

            <h2><?=__('完成')?></h2>
        </li>
    </ol>
    <div class="dataLoading" id="dataLoading"><p><?=__('加载中')?>...</p></div>
    <div class="goods">
        <div class="form-style">
            <form method="post" id="form">
                <h3><i class="iconfont icon-edit"></i><?=__('高级合伙人信息')?></h3>
                <input type="hidden" name="order_id" value="<?php if ( !empty($order_data) ) { echo $order_data['stock_order_id']; } ?>"/>
                <input type="hidden" name="action" value="<?php if ( !empty($order_data) ) { echo 'edit'; } ?>"/>
                <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id ?>" />
                <input type="hidden" id="shop_id" name="shop_id" value="<?php echo $shop_id ?>" />
                <input type="hidden" id="user_stocks" value="<?php echo $user_resouce['user_stocks'] ?>" />
                <dl>
                    <dt><?=__('合伙人姓名')?>：</dt>
                    <dd>
                        <?php echo $g_partner_info['user_realname'] ?>
                    </dd>
                </dl>
                <dl>
                    <dt><?=__('合伙人备货金')?>：</dt>
                    <dd>
                        <?php echo $user_resouce['user_stocks'] ?>
                    </dd>
                </dl>
                <h3><i class="iconfont icon-edit"></i><?=__('选择收货人地址')?></h3>
                <dl>
                    <dt><?=__('收货人信息')?>：</dt>
                    <dd>
                        <a href="javascript:void(0);" dialog_id="edit_send_address" data-user_id="<?= $user_id; ?>"
                           class="ncbtn-mini fl bbc_seller_btns"><?=__('选择')?></a>
                    </dd>
                </dl>
                <dl>
                    <dt><i>*</i><?=__('联系人')?>：</dt>
                    <dd>
                        <input type="text"  class="text w150" id="order_address_name" name="order_address_name"
                               value="<?=$user_address['user_address_contact']?>" />
                    </dd>
                </dl>
                <dl>
                    <dt><i>*</i><?=__('所在地区')?>：</dt>
                    <dd>
                        <input type="hidden" name="user_address_area" id="t" value="<?=$user_address['user_address_area']?>" />
                        <input type="hidden" name="user_province_id" id="id_1" value="<?=$user_address['user_address_province_id']?>" />
                        <input type="hidden" name="user_city_id" id="id_2" value="<?=$user_address['user_address_city_id']?>" />
                        <input type="hidden" name="user_area_id" id="id_3" value="<?=$user_address['user_address_area_id']?>" />
                        <div id="d_1"><?php if($user_address['user_address_area']){ ?>
                            <?=$user_address['user_address_area'] ?>&nbsp;&nbsp;<a href="javascript:sd();"><?=__('编辑')?></a>
                        <?php } ?>
                        </div>

                        <div id="d_2"  class="<?php if($user_address['user_address_area']) echo 'hidden';?>">
                            <select id="select_1" name="select_1" onChange="district(this);">
                                <option value=""><?=__('--请选择--')?></option>
                                <?php foreach($district['items'] as $key=>$val){ ?>
                                    <option value="<?=$val['district_id']?>|1"><?=$val['district_name']?></option>
                                <?php } ?>
                            </select>
                            <select id="select_2" name="select_2" onChange="district(this);" class="hidden"></select>
                            <select id="select_3" name="select_3" onChange="district(this);" class="hidden"></select>
                        </div>
                    </dd>
                </dl>
                <dl>
                    <dt><i>*</i><?=__('街道地址')?>：</dt>
                    <dd>
                        <input type="text"  class="text w450" id="user_address_address" name="order_address_address"
                               value="<?=$user_address['user_address_address']?>" />
                        <p class="hint"><?=__('不必重复填写地区')?></p>
                    </dd>
                </dl>
                <dl>
                    <dt><i>*</i><?=__('电话')?>：</dt>
                    <dd>
                        <input type="text" class="text w200" id="order_address_phone" name="order_address_phone"
                               maxlength="11" value="<?= $user_address['user_address_phone']?>">
                    </dd>
                </dl>
                <h3>
                    <i class="iconfont icon-edit"></i><?=__('选择商品')?>(<?=__('商品总金额:')?>
                    <em id="total_amount_show" class="bbc_seller_color">￥0.00</em>
                    <?=__('配送费:')?>
                    <em id="shipping_fee_show" class="bbc_seller_color">￥0.00</em>)
                    <input type="hidden" id="total_amount" name="total_amount" value="0">
                    <input type="hidden" id="shipping_fee" name="shipping_fee" value="0">
                </h3>
                <div class="goods-category-list fn-clear clearfix">
                    <div>
                        <div class="grid-wrap">
                            <table id="goods_grid">
                            </table>
                            <div id="goods_page"></div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="select_goods_list" name="select_goods_list" />
                <input type="button" class="button bbc_seller_submit_btns" id="button_get_shipping_fee" value="<?=__('计算配送费')?>">
                <input type="button" class="button bbc_sellerGray_submit_btns" id="button_next_step" value="<?=__('提交')?>">
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/district.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/order_add_step2.js" charset="utf-8"></script>


<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>
