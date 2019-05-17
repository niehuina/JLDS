<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';

?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/shop_table.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<style>

.ui-jqgrid tr.jqgrow .img_flied{padding: 1px; line-height: 0px;}
.img_flied img{width: 100px; height: 30px;}

</style>
<div style="   overflow: hidden;
    padding: 10px 3% 0;
    text-align: left;" >
	<form id="shop_verify-form">
	<table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
		<thead>
			<tr><th colspan="20">认证信息审核</th></tr>
		</thead>
        <tr>
            <th>认证信息审核：</th>
			<td>
				<div class="onoff">
                    <?php if($data['shop_certification_status']=="2"){?>
                        <label for="verify_enabled" class="cb-enable selected" title="通过">通过</label>
                        <label for="verify_disabled" class="cb-disable " title="拒绝">拒绝</label>
                    <input id="verify_enabled" name="shop_verify" checked="checked" value="2" type="radio">
                    <input id="verify_disabled" name="shop_verify"  value="3" type="radio">
                    <?php }else{ ?>
                        <label for="verify_enabled" class="cb-enable " title="通过">通过</label>
                        <label for="verify_disabled" class="cb-disable selected" title="拒绝">拒绝</label>
                        <input id="verify_enabled" name="shop_verify"  value="2" type="radio">
                        <input id="verify_disabled" name="shop_verify"  checked="checked" value="3" type="radio">
                <?php }?>
				</div>
			</td>
        </tr>
		<tr>
			<th>审核信息：</th>
			<td>
				<textarea rows="2" class="ui-input w600"  name="shop_certification_reason" id="shop_certification_reason"><?=$data['shop_certification_reason']?></textarea>
			</td>
		</tr>
		<tr>
			<th>操作：</th>
			<input type="hidden" name="shop_id" id="shop_id" value="<?=$data['shop_id'] ?>" />
			<td><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></td>
		</tr>
	</table>
	</form>
</div>
<script>
	$(function ()
	{
		if ($('#shop_verify-form').length > 0)
		{
			$('#shop_verify-form').validator({
				ignore: ':hidden',
				theme: 'yellow_bottom',
				timely: 1,
				stopOnError: true,
				fields: {

				},
				valid: function (form)
				{
					parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
						{
							Public.ajaxPost(SITE_URL + '?ctl=Shop_Manage&met=verifyCertification&typ=json', $('#shop_verify-form').serialize(), function (data)
							{
								if (data.status == 200)
								{
									parent.Public.tips({content: '审核成功！'});
									window.location.reload();
								}
								else
								{
									parent.Public.tips({type: 1, content: data.msg || '审核失败！'});
									window.location.reload();
								}
							});
						},
						function ()
						{

						});
				}
			}).on("click", "a.submit-btn", function (e)
			{
				$(e.delegateTarget).trigger("validate");
			});
		}
	});
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>