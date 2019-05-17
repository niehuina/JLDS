<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" >
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title;?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <?php hook::listen('view_header');?>
    <link rel="stylesheet" href="http://at.alicdn.com/t/font_420529_wivo5aaxo7fx0f6r.css">
    <?php
    echo links([
                   base_url().'/misc/sweetalert/dist/sweetalert.css',
                   base_url().'/misc/ionicons-2.0.1/css/ionicons.min.css',
                   base_url().'/misc/AdminLTE-2.3.11/bootstrap/css/bootstrap.min.css',
                   // base_url().'/misc/iconfont/iconfont.css',
                   base_url().'/misc/common.css',
                   base_url().'/misc/jqueryui/jquery-ui.min.css',
                   base_url().'/misc/font-awesome-4.7.0/css/font-awesome.min.css',
                   base_url().'/misc/AdminLTE-2.3.11/dist/css/AdminLTE.min.css',
                   base_url().'/misc/AdminLTE-2.3.11/dist/css/skins/_all-skins.min.css',
                   base_url().'/misc/AdminLTE-2.3.11/plugins/iCheck/flat/blue.css',
                   base_url().'/misc/AdminLTE-2.3.11/plugins/select2/select2.min.css',
                   base_url().'/misc/AdminLTE-2.3.11/plugins/datepicker/datepicker3.css',
                   base_url().'/misc/AdminLTE-2.3.11/plugins/iCheck/all.css',
                   base_url().'/misc/iphone-style-checkboxes/iphone-style-checkboxes.css',
                   theme_url().'/css.css',
                   base_url().'/misc/nprogress.css',
                   base_url().'/misc/AdminLTE-2.3.11/ztree/css/demo.css',
                   base_url().'/misc/AdminLTE-2.3.11/ztree/css/zTreeStyle.css',
                   base_url().'/misc/AdminLTE-2.3.11/ztree/css/myStyle.css',
               ]);
    ?>

    <?php
    echo links([
                   base_url().'/misc/AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js',
                   base_url().'/misc/AdminLTE-2.3.11/plugins/jQueryUI/jquery-ui.min.js',
                   base_url().'/misc/raphael.min.js',
                   base_url().'/misc/moment.js',
                   base_url().'/misc/AdminLTE-2.3.11/bootstrap/js/bootstrap.min.js',
                   base_url().'/misc/AdminLTE-2.3.11/plugins/daterangepicker/daterangepicker.js',
                   base_url().'/misc/AdminLTE-2.3.11/plugins/select2/select2.full.min.js',
                   base_url().'/misc/AdminLTE-2.3.11/plugins/slimScroll/jquery.slimscroll.min.js',
                   base_url().'/misc/AdminLTE-2.3.11/dist/js/app.js',
                   base_url().'/misc/AdminLTE-2.3.11/dist/js/demo.js',
                   base_url().'/misc/jquery.form.js',
                   base_url().'/misc/jquery.ajaxfileupload.js',
                   base_url().'/misc/AdminLTE-2.3.11/plugins/iCheck/icheck.min.js',
                   base_url().'/misc/iphone-style-checkboxes/iphone-style-checkboxes.js',
                   base_url().'/misc/notify.js',
                   base_url().'/misc/sweetalert/dist/sweetalert.dev.js',
                   base_url().'/misc/barba.min.js',
                   theme_url().'/js.js',
                   base_url().'/misc/nprogress.js',
                   base_url().'/misc/main.js',
                   base_url().'/misc/AdminLTE-2.3.11/ztree/js/jquery.ztree.core.js',
                   base_url().'/misc/AdminLTE-2.3.11/ztree/js/jquery.ztree.exhide-3.5.min.js'

               ]);
    ?>


    <link rel="stylesheet" href="<?php echo base_url().'/misc/toastr.min.css'; ?> ">
</head>
<body class="hold-transition skin-blue sidebar-mini " >
<div class="wrapper" >

    <header class="main-header">
        <!-- Logo -->
        <a href="javascript:;" class="logo" style="position: fixed;width: 85%;">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>D</b>OC</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" style="position: fixed;width: 100%;">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Control Sidebar Toggle Button -->
                    <!-- Tasks: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu" style="margin-right: 200px;">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo base_url();?>/misc/AdminLTE-2.3.11/dist/img/user2-160x160.jpg" class="user-image" >
                            <span class="hidden-xs"><?php echo cookie('admin_user');?></span>
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="<?php echo base_url();?>/misc/AdminLTE-2.3.11/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                                <p>
                                    <?php echo cookie('admin_user');?>
                                    <small><?php echo date('Y-m-d');?></small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="">
                                    <a href="#" class=""><i class="iconfont icon-fanhui"></i><?php echo __('返回');?></a>
                                </div>
                                <div class="">
                                    <a rel="<?php echo url('doc/login/loginout');?>" id="loginout" class=""><i class="iconfont icon-tuichu"></i><?php echo __('退出登录');?></a>
                                </div>
                            </li>
                        </ul>
                    </li>


                </ul>
            </div>

        </nav>

    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar" style="position: fixed;">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- search form -->

            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu menu">
                <!--  <li class="header"><?php echo __('名称'); ?></li> -->
                <?php if(cookie('admin_level')==1){?>
                    <li class="active treeview">
                        <a href="#">
                            <i class="iconfont icon-shouquanguanli"></i> <span><?php echo __('授权管理'); ?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                        </a>
                        <ul class="treeview-menu menu">
                            <li><a href="<?php echo url('doc/admin_users/index'); ?>"><?php echo __('超级管理员账号设置'); ?></a></li>
                            <li><a href="<?php echo url('doc/users/index'); ?>"><?php echo __('授权账号设置'); ?></a></li>
                            <li><a href="<?php echo url('doc/yf_shop_base/index'); ?>"><?php echo __('授权门店设置'); ?></a></li>
                            <li><a href="<?php echo url('doc/roles/index'); ?>"><?php echo __('基础数据配置'); ?></a></li>
                            <li><a href="<?php echo url('doc/yf_goods_cat/index'); ?>"><?php echo __('商品分类'); ?></a></li>
                            <li><a href="<?php echo url('doc/configapi/index'); ?>"  data-url="doc/shop_users"><?php echo __('API配置'); ?></a></li>
                            <li><a href="<?php echo url('doc/configapi/config'); ?>" data-url="doc/configapi"><?php echo __('一般设置'); ?></a></li>
                        </ul>
                    </li>
                <?php }?>
                <li class="active treeview">
                    <a href="#">
                        <i class="iconfont icon-mendianguanli"></i> <span><?php echo __('门店管理'); ?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu menu pa">
                        <?php if(cookie('admin_level') == 2 && cookie('admin_ucenter_id') ){?>
                            <li ><a href="<?php echo url('doc/users_bind'); ?>"  data-url="doc/users_bind"><?php echo __('账号绑定'); ?></a></li>
                            <li ><a href="<?php echo url('doc/bind_shop'); ?>"  data-url="doc/shop_users"><?php echo __('同步网店'); ?></a></li>
                        <?php }?>
                        <li ><a href="<?php echo url('doc/shop_users'); ?>"  data-url="doc/shop_users"><?php echo __('员工账号设置'); ?></a></li>
                        <?php if(cookie('admin_level')!=1){?>
                            <li><a href="<?php echo url('doc/acl_roles'); ?>"  data-url="doc/acl_roles"><?php echo __('角色权限设置'); ?></a></li>
                        <?php }?>
                        <li><a href="<?php echo url('doc/yf_goods_shop_common'); ?>"  data-url="doc/yf_goods_shop_common"><?php echo __('本地商品库'); ?></a></li>
                        <li><a href="<?php echo url('doc/wp_order_num'); ?>"  data-url="doc/wp_order_num"><?php echo __('营业状况'); ?></a></li>
                        <li><a href="<?php echo url('doc/wp_order_all'); ?>"  data-url="doc/wp_order_all"><?php echo __('销售单据'); ?></a></li>
                        <li><a href="<?php echo url('doc/record_succession'); ?>"  data-url="doc/record_succession"><?php echo __('交接班记录'); ?></a></li>
                        <li><a href="<?php echo url('doc/wp_users_discount'); ?>"  data-url="doc/wp_users_discount"><?php echo __('会员管理'); ?></a></li>

                        <li><a href="<?php echo url('doc/wp_order'); ?>" data-url="doc/wp_order"><?php echo __('订单信息'); ?></a></li>
                        <li><a href="<?php echo url('doc/yf_payment_way'); ?>" data-url="doc/yf_payment_way"><?php echo __('支付方式设置'); ?></a></li>
                        <li><a href="<?php echo url('doc/logo'); ?>" ><?php echo __('关于我们'); ?></a></li>
                    </ul>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="barba-wrapper">
        <?php
        $qx = new \cs\controller_home();
        $arr = json_encode($qx->operation_authority(1));
        ?>
        <div class="barba-container">

            <?php echo $this->view['content']; ?>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->
    <!-- <footer class="main-footer">
        <div class="hidden-xs">
            <b><?php echo __('远丰集团webpos后台管理');?></b><?php echo __('Version');?> <?php echo config('app.version');?>
        </div>

    </footer> -->
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">

    </aside>

</div>
<!-- ./wrapper -->


<script src="<?php echo base_url().'/misc/toastr.min.js';?>"></script>
<script src="<?php echo base_url(); ?>/misc/comm.js"></script>
<!-- 等一下 -->
<script>
    $(function(){
        var arr = <?php echo $arr;?>;
        if(arr){
            var data = json_array(arr);
            $('a').each(function(){
                var url = $(this).data('url');
                if(url){
                    var result = $.inArray(url,data);
                    if(result == -1){
                        $(this).parent().hide();

                    }
                }

            });
        }

    });



    function json_array(data){
        var len=eval(data).length;
        var arr=[];
        for(var i=0;i<len;i++){
            arr[i] =[];
            arr[i]=data[i];
        }
        return arr;
    }


</script>
<?php echo $this->view['footer'];?>
<?php hook::listen('view_footer');?>
</body>
</html>
