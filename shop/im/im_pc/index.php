<?php

class chat
{
	public static function getChatHtml($user_name,$ucenter_url)
	{
		
		$web_html = '';
		$web_html = <<<EOT

		<script src="./im/im_pc/script/jquery-1.4.4.min.js" type="text/javascript"></script>
		<script src="./im/im_pc/script/base.js" type="text/javascript"></script>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<link href="./im/im_pc/css/emoji.css" rel="stylesheet">
		<link href="./im/im_pc/templates/default/css/chat.css" rel="stylesheet" type="text/css"> 
		<link href="./im/im_pc/templates/default/css/home_login.css" rel="stylesheet" type="text/css">

		<link href="./im/im_pc/templates/default/css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">

		<script type="text/javascript" src="http://app.cloopen.com/im50/ytx-web-im.min.5-1-3.js"></script>

	<script type="text/javascript" src="./im/im_pc/script/jquery.ui.js"></script>
	<script type="text/javascript" src="./im/im_pc/script/perfect-scrollbar.min.js"></script>
	<script type="text/javascript" src="./im/im_pc/script/jquery.mousewheel.js"></script>
	<script type="text/javascript" src="./im/im_pc/script/jquery.charCount.js" charset="utf-8"></script>
	<script type="text/javascript" src="./im/im_pc/script/chat.js" charset="utf-8"></script>

	<script type="text/javascript" src="./im/im_pc/js/emoji.js" charset="utf-8"></script>
	<script type="text/javascript" src="./im/im_pc/script/chat/user.js" charset="utf-8"></script>

	<style>
		#emoji_div{
			display: block;
			margin-left: -10px;
			top:211px;
			border: 1px solid #d5e5f5;
			height: 94px;
			padding: 6px;
			position: absolute;
			width: 224px;
			z-index: 999999; 
			width: 229px;
			height: 96px;
			background: #fff;
		} 
	</style>

	<div id="navbar" class="navbar navbar-inverse navbar-fixed-top" style="display:none;">
        <div class="navbar-inner">
            <div class="container">
                    <span style="float: left;display: block;font-size: 20px;font-weight: 200;
                    padding-top: 10px;padding-right: 0px;padding-bottom: 10px;padding-left: 0px;text-shadow: 0px 0px 0px;color:#eee"><!--云通讯 IM--></span>
                <div id="navbar_login" class="nav-collapse in collapse" style="height: auto;" align="right">      
                    <div name="loginType" class="navbar-form pull-right" id="1">
	                   <input id='navbar_user_account' style="width:140px;margin-right: 5px;" type="text" value="{$user_name}">
                       <input type="password" id='navbar_user_password' style="width:95px;margin-right: 5px;" type="text">
	                   <input class="btns" type="button"  value="登录" style="line-height:20px;" />
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	<div style="clear: both;"></div>
	<div id="web_chat_dialog" style="display: none;float:right;">
	</div>
	<a id="chat_login" href="javascript:void(0)" style="display: none;"></a>
	<input type="hidden" name="ucenter_url" class="ucenter_url" value="{$ucenter_url}">

	<script type="text/javascript">
		var APP_SITE_URL  = '';
		var CHAT_SITE_URL = '';
		var SHOP_SITE_URL = '';
		var connect_url   = "";

		var layout     = "";
		var act_op     = "";
		var user       = {};

		user['u_id']   = "1";
		user['u_name'] = "";
		user['s_id']   = "";
		user['s_name'] = "";
		user['avatar'] = "image/default/avatar.png";

		window.domain_root = "http://www.im-builder.com/demo1/";

		//var ucenter_url = 'http://ucenter.yuanfeng021.com/index.php';
		//var imbuilder_url = 'http://api.im-builder.com/';

		//var ucenter_url = 'http://localhost/pcenter/index.php';
		//var imbuilder_url = 'http://localhost/imbuilder/';

		var ucenter_url = $('.ucenter_url').val();
   	
   		var user_name=$("#navbar_user_account").val();
   		$(function (){
   			$.post(ucenter_url+"?ctl=Login&met=getUserByName&typ=json",{'user_name':user_name},function(data){
   				console.info(data);
   				if(data.status == 200)
   				{
   					$("#navbar_user_password").val(data.data.password);
   					DO_login();
   				}
   			});
   		});
   	
</script>
EOT;

			return $web_html;
	}
}
