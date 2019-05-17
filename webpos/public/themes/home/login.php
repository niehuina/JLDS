<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo __('登录');?></title>
	<link rel="stylesheet" href="<?php echo theme_url(); ?>/css/base.css">
	<link rel="stylesheet" href="<?php echo theme_url(); ?>/css/login.css">
	<link rel="stylesheet" href="http://at.alicdn.com/t/font_372409_n4ava87gpvzsq0k9.css">
	<link rel="stylesheet" href="<?php echo theme_url(); ?>/css/bootstrap.min.css" >
	<link rel="stylesheet" href="<?php echo base_url().'/misc/toastr.min.css'; ?> ">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/misc/comm.css" >

	<style>
		
	</style>

	<script src="<?php echo theme_url(); ?>/js/jquery.min.js"></script>
	<script src="<?php echo base_url();  ?>/misc/jquery.form.js"></script>
	<script src="<?php echo base_url();  ?>/misc/notify.js"></script>
  
 	<script src="<?php echo base_url().'/misc/toastr.min.js';?>"></script> 
    <script src="<?php echo base_url(); ?>/misc/comm.js"></script>
    

</head>
<body>
	<div class="login-area">
		<div class="table tc">
			<div class="table-cell">
				<div class="login-con-pad">
					<div class="login-area-cont">
						<h2 class="tc"><span><?php echo __('欢迎登录');?></span><em><?php echo __('WebPos智能收银');?></em></h2>
						<?php echo form::open('form1',['class'=>'ajax','action'=>url('home/login/index')]); ?>
							<div class="login-text tl"><i class="iconfont icon-user2"></i><input id="user" name="user_name" type="text" placeholder="<?php echo __('账号');?>"></div>
							<div class="login-text tl"><i class="iconfont icon-password"></i><input id="pwd" name="user_pwd" type="password" placeholder="<?php echo __('密码');?>"></div>
						
						<div>
						  <button type="submit" class="btn-login"><?php echo __('登录');?></button>
							
						</div>
						</form>
					</div>
				</div>
				
			</div>
			
		</div>
		
	</div>
	

<script type="text/javascript">

	$('.ajax').ajaxForm({

        dataType:  'json',

        success:   function(data) {

            if(data.status == 1 && data.msg ){
                Public.tips.success(data.msg);
            }else if(data.msg){
                Public.tips.warning(data.msg);
            }

         
            if(data.url){
                setTimeout(function(){
                    window.location.href = data.url;
                },1000);
            }
            if(data.render){
                $("."+data.render).html(data.html);
               
            }
        }
    },'json');

</script>
</body>
</html>