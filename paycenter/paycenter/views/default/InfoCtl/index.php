<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

<div class="pc_user_about wrap">
	<h4><?=_('财富概况')?></h4>
	<div class="pc_user_mes clearfix">
		<div class="pc_user_mes_lf fl clearfix">
			<p class="pc_user_mes_lf_img fl"><img src="<?=_($user_info['user_avatar'])?>"></p>
			<div class="pc_user_mes_lf_text fr">
				<dl class="clearfix">
					<dt><i class="iconfont icon-yonghuming"></i><?=_('用户名称')?></dt>
					<dd><?=$user_info['user_nickname']?></dd>
				</dl>
				<?php if(!empty($user_info['user_mobile'])){?>
				<dl class="clearfix">
					<dt><i class="iconfont icon-shoujihao"></i><?=_('手机号码')?></dt>
					<dd><?=$user_info['user_mobile']?></dd>
				</dl>
				<?php }?>
				<?php if(!empty($user_info['user_email'])){?>
				<dl class="clearfix">
					<dt><i class="iconfont icon-youxiang"></i><?=_('绑定邮箱')?></dt>
					<dd><?=$user_info['user_email']?></dd>
				</dl>
				<?php }?>
				<dl class="clearfix">
					<dt><i class="iconfont icon-shangcidenglushijian"></i><?=_('上次登录时间')?></dt>
					<dd><?=$user_base['user_login_time']?></dd>
				</dl>
			</div>
		</div>
		<div class="pc_user_mes_rt fr clearfix">
			<div class="pc_user_mes_rt_percent fl">
				<img src="<?= $this->view->img ?>/percent.png">
				<p class="pc_account"><?=_('账户总财产：')?><span><?=(format_money($user_resource['user_money'] + $user_resource['user_money_frozen'] + $user_resource['user_shares'] + $user_resource['user_stocks']))?></span></p>
			</div>
			<div class="pc_user_mes_rt_text fl">
				<dl class="clearfix dl-public">
					<dt><span class="pc_col_reprens bgb"></span><?=_('账户余额：')?></dt>
					<dd><?=(format_money($user_resource['user_money']))?></dd>
				</dl>
				<dl class="clearfix dl-public">
					<dt><span class="pc_col_reprens bgr"></span><?=_('冻结资金：')?></dt>
					<dd><?=(format_money($user_resource['user_money_frozen']))?></dd>
				</dl>
                <dl class="clearfix dl-public">
                    <dt class="dt_pad"><span class="pc_col_reprens bgy"></span><?=_('股金')?><i>：</i></dt>
                    <dd><?=(format_money($user_resource['user_shares']))?></dd>
                </dl>
                <dl class="clearfix dl-public">
                    <dt class="dt_pad"><span class="pc_col_reprens bgo"></span><?=_('备货金')?><i>：</i></dt>
                    <dd><?=(format_money($user_resource['user_stocks']))?></dd>
                </dl>
				<dl class="clearfix pc_a_btn dl-public">
                    <dd><a target="_blank" onclick="get_user_identity(event, this)" href="<?=Yf_Registry::get('url')?>?ctl=Info&met=deposit" class="pc_btn"><?=_('充值')?></a></dd>
                    <dd><a target="_blank" onclick="get_user_identity(event, this)" href="<?=Yf_Registry::get('url').'?ctl=Info&met=withdraw&typ=e'?>" class="pc_btn btn_active"><?=_('提现')?></a></dd>
					<dd><a target="_blank" onclick="get_user_identity(event, this)" href="<?= Yf_Registry::get('url') ?>?ctl=Info&met=transfer&typ=e" class="pc_btn"><?=_('转账')?></a></dd>
				</dl>
			</div>
		</div>
	</div>
</div>

<div class="pc_transaction wrap">
	<h4><?=_('最近交易')?><!--<span class="trade_types"><a target="_blank" href="" ><?/*=_('充值记录')*/?></a>&nbsp;|&nbsp;--><a target="_blank" href="" ><?=_('提现记录')?></a>&nbsp;|&nbsp;<a target="_blank" href="" ><?=_('退款记录')?></a></span></h4>
	
	<div class="pc_table_head clearfix">
		<p class="pc_trans_time"><span><?=_('创建时间')?></span></p>
		<p class="pc_trans_other">
			<span class="pc_table_num"><?=_('名称')?>&nbsp;|&nbsp;<?=_('对方')?>&nbsp;|&nbsp;<?=_('交易号')?></span><span class="wp20"><?=_('金额')?></span><span class="wp20"><?=_('状态')?></span><span class="wp20"><?=_('操作')?></span>
		</p>
	</div>
	<?php foreach($consume_record_list['items'] as $conkey => $conval){?>
	<div class="pc_trans_lists clearfix">
		<div class="pc_trans_time pc_trans_det_time"><?=($conval['record_time'])?></div>
		<div class="pc_trans_det pc_trans_other">
			<p class="pc_table_num"><span><?=($conval['record_title'])?></span><?php if($conval['order_id']){?><span class="jyh"><?=_('交易号:')?><?=($conval['order_id'])?></span><?php }?></p>
			<p class="wp20">
				<span class="textcolor">
						<?=money_format($conval['record_money'])?>
				</span>
			</p>
			<p class="wp20"><span><?=($conval['record_status_con'])?></span></p>
			<p class="wp20"><a href="" class="cb"><?=_('详情')?></a></p>
		</div>
	</div>
	<?php }?>
	<div class="pc_trans_btn"><a href="<?=Yf_Registry::get('url')?>?ctl=Info&met=recordlist" class="btn_big btn_active"><?=_('查看更多账单')?></a></div>
</div>
<script type="text/javascript">
//提现
function get_user_identity(){
    var ajax_url = '<?=Yf_Registry::get('url').'/index.php?ctl=Info&met=getUserInfo&typ=json'?>';
    var user_id = '<?=$user_info['user_id']?>';
    $.ajax({
        url: ajax_url,
        success:function(result){
            if(result.status == 200)
            {
                if(result.data[user_id].user_identity_statu == 2){
                    window.location.href = '<?=Yf_Registry::get('url').'/index.php?ctl=Info&met=withdraw&typ=e'?>';
                    return ;
                }else if(result.data[user_id].user_identity_statu == 0){
                    var notice = '<?=__('您还未实名认证')?>';
                }else{
                    var notice = '<?=__('您还未实名认证成功')?>';
                }
            } else {
                var notice = '<?=__('网络错误')?>';
            }
            $.dialog({
                title: '<?=__('提示')?>',
                content: notice,
                height: 100,
                width: 410,
                lock: true,
                drag: false,
                ok: function () {
                    window.location.href = '<?=Yf_Registry::get('url').'/index.php?ctl=Info&met=account&typ=e'?>';
                }
            })
        }
    });
}
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>