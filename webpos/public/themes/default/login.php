<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo __('WebPos | 登录');?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo base_url();?>/misc/AdminLTE-2.3.11/bootstrap/css/bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url();?>/misc/AdminLTE-2.3.11/bootstrap/css/login/AdminLTE.min.css">

    <link rel="stylesheet" href="<?php echo base_url().'/misc/toastr.min.css'; ?> ">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/misc/comm.css" >
     

    <!-- iCheck -->
    <?php
    echo links([
                   base_url().'/misc/AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js',
                   base_url().'/misc/AdminLTE-2.3.11/plugins/jQueryUI/jquery-ui.min.js',
                   base_url().'/misc/raphael.min.js',
                   base_url().'/misc/moment.js',

                   base_url().'/misc/AdminLTE-2.3.11/bootstrap/js/bootstrap.min.js',

                   base_url().'/misc/AdminLTE-2.3.11/plugins/daterangepicker/daterangepicker.js',

                   base_url().'/misc/AdminLTE-2.3.11/plugins/slimScroll/jquery.slimscroll.min.js',

                   base_url().'/misc/AdminLTE-2.3.11/dist/js/app.js',

                   base_url().'/misc/AdminLTE-2.3.11/dist/js/demo.js',

                   base_url().'/misc/jquery.form.js',
                   base_url().'/misc/notify.js',
                   theme_url().'/js.js',

                   base_url().'/misc/jquery.ajaxfileupload.js',
                   base_url().'/misc/iphone-style-checkboxes/iphone-style-checkboxes.js',

               ]);
    ?>

</head>
<body class="hold-transition login-page ">
  <div class="login-area tc">
    <div class="login-box">
      <div class="login-logo">
          <a href="#">Webpos</a>
      </div>
      <!-- /.login-logo -->
      <div class="login-box-body">
          <p class="login-box-msg"></p>

          <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('index')]); ?>
          <div class="form-group has-feedback">
              <input type="text" class="form-control" placeholder="<?php echo __('账号');?>" name="user" id="user">
          </div>
          <div class="form-group has-feedback">
              <input type="password" class="form-control" placeholder="<?php echo __('密码');?>" name="pwd" id="pwd">
          </div>
          <div class="">
              <!-- /.col -->
              <div class="">
                  <button type="submit" class="btn btn-primary btn-block btn-flat"><?php echo __('登录');?></button>
              </div>
              <!-- /.col -->
          </div>
          </form>

      </div>
    </div>
  </div>
   <script src="<?php echo base_url().'/misc/toastr.min.js';?>"></script> 
   <script src="<?php echo base_url(); ?>/misc/comm.js"></script>

</body>
</html>
