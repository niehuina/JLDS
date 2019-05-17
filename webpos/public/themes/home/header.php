<?php if(!is_ajax()){ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit" >
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo __('webpos首页')?></title>
    
    <link rel="stylesheet" href="<?php echo base_url().'/misc/jqueryui/jquery-ui.min.css' ?>">
    <link href="<?php echo theme_url(); ?>/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo theme_url(); ?>/css/base.css">
<?php }?>

    <link rel="stylesheet" href="<?php echo theme_url(); ?>/css/common.css">
    <link rel="stylesheet" href="<?php echo theme_url(); ?>/css/index.css">
    <link rel="stylesheet" href="<?php echo theme_url(); ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo theme_url().'/swiper/css/swiper.min.css';?>">

<?php if(!is_ajax()){ ?>
    <link rel="stylesheet" href="<?php echo theme_url(); ?>/css/login.css">
    <link rel="stylesheet" href="<?php echo theme_url(); ?>/css/order.css">
    <link rel="stylesheet" href="<?php echo theme_url(); ?>/css/softkeys-0.0.1.css" >
    <link rel="stylesheet" href="<?php echo base_url().'/misc/AdminLTE-2.3.11/plugins/datepicker/datepicker3.css'; ?> ">
    <link rel="stylesheet" href="<?php echo base_url().'/misc/sweetalert/dist/sweetalert.css'; ?> ">
    <link rel="stylesheet" href="http://at.alicdn.com/t/font_372409_cbjz08lbrcnmi.css">
    <link rel="stylesheet" href="<?php echo base_url().'/misc/toastr.min.css'; ?> ">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/misc/comm.css" >

    <script type="text/javascript">
        var base_url = "<?php echo base_url(); ?>"; 
    </script>


    <link href="<?php echo base_url(); ?>/misc/animate.css" rel="stylesheet">
    <style>
        .show{
            display:none
        }
        
    </style>
</head>

<body id="body">

<?php }?>
    