<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';

// 当前管理员权限
$admin_rights = $this->getAdminRights();
$ctl = rtrim($this->ctl,'Ctl');
$met = $this->met;
$Menu_Base = new Menu_Base();
// 当前页面所在菜单
$this_menu = $Menu_Base->getOneByWhere(array('menu_url_ctl'=>$ctl,'menu_url_met'=>$met));
// 当前页面所在的父级菜单 
$father_menu = $Menu_Base->getOneByWhere(array('menu_id'=>$this_menu['menu_parent_id']));
// 当前页面的全部同级菜单
$brother_menu = $Menu_Base->getByWhere(array('menu_parent_id'=>$father_menu['menu_id']));
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<style>
    .webuploader-pick{ padding:1px; }
    .n-valid{setGrade
        width: 100px;
        height: 25px;
    }
    /*.ncap-form-default_reset dl.row{*/
    /*    border: unset;*/
    /*}*/
    .ncap-form-default_reset dl.row.bd{
        border-top: dashed #555 1px !important;
    }
</style>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?=$father_menu['menu_name']?></h3>
                <h5><?=$father_menu['menu_url_note']?></h5>
            </div>
			<ul class="tab-base nc-row">
                <?php
                foreach($brother_menu as $key=>$val){
                    if(in_array($val['rights_id'],$admin_rights)||$val['rights_id']==0){
                ?>
                <li><a <?php if(!array_diff($this_menu, $val)){?> class="current"<?php }?> href="<?= Yf_Registry::get('url') ?>?ctl=<?=$val['menu_url_ctl']?>&met=<?=$val['menu_url_met']?><?php if($val['menu_url_parem']){?>&<?=$val['menu_url_parem']?><?php }?>"><span><?=$val['menu_name']?></span></a></li>
                <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>
     <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <?=$this_menu['menu_url_note']?>
        </ul>
    </div>

    <form method="post" enctype="multipart/form-data" id="grade-edit-form" name="form">
        <div class="ncap-form-default ncap-form-default_reset">
			<div class="title">
				<h3>会员级别设置：</h3>
			</div>
			<?php foreach($data as $key=>$val){ ?>
                <?php if($val['id'] == 1){ ?>
                <dl class="row">
                    <dt class="tit"><span>会员等级</span></dt>
                    <dd class="opt">
                      <input type="text" class="n-valid" value="<?=$val['user_grade_name'];?>" name="gr[<?=$key;?>][user_grade_name]">
                        &nbsp;注册即可; 无折扣;
                      <input type="hidden" value="<?=$val['user_grade_id'];?>" name="gr[<?=$key;?>][user_grade_id]">
                    </dd>
                 </dl>
                 <dl class="row">
                    <dt class="tit"><strong>等级图片</strong></dt>
                    <dd class="opt">
                      <img id="grade_logo_image<?=$key?>" name="gr[<?=$key?>][user_grade_logo]" alt="选择图片" src="<?=$val['user_grade_logo'];?>" width="100px" height="100px"/>

                        <div class="image-line upload-image"  id="grade_logo_upload<?=$key?>">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>

                        <input id="grade_logo<?=$key?>"  name="gr[<?=$key?>][user_grade_logo]" value="<?=$val['user_grade_logo'];?>" class="ui-input w400" type="hidden"/>
                        <div class="notic">默认LOGO，最佳显示尺寸为100*100像素</div>
                    </dd>
                 </dl>
                <?php }elseif($val['id'] == 2){?>
                    <dl class="row bd">
                        <dt class="tit"><span>会员等级</span></dt>
                        <dd class="opt">
                            <input type="text" class="n-valid" value="<?=$val['user_grade_name'];?>" name="gr[<?=$key;?>][user_grade_name]">
                            &nbsp;晋级需消费或者储值 <input type="text" class="n-valid" value="<?=$val['user_grade_trade'];?>" name="gr[<?=$key;?>][user_grade_trade]"> 元;
                            <input type="hidden" value="<?=$val['user_grade_id'];?>" name="gr[<?=$key;?>][user_grade_id]">
                        </dd>
                    </dl>
                    <dl class="row">
                        <dt class="tit"><span>会员福利</span></dt>
                        <dd class="opt">
                            1. 消费享受会员专属价；<br/>
                            2. 享受所有下线消费者的每笔订单价与会员价的差额返利；
                        </dd>
                    </dl>
                    <dl  class="row">
                        <dt class="tit"><strong>等级图片</strong></dt>
                        <dd class="opt">
                            <img id="grade_logo_image<?=$key?>" name="gr[<?=$key?>][user_grade_logo]" alt="选择图片" src="<?=$val['user_grade_logo'];?>" width="100px" height="100px"/>

                            <div class="image-line upload-image"  id="grade_logo_upload<?=$key?>">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>

                            <input id="grade_logo<?=$key?>"  name="gr[<?=$key?>][user_grade_logo]" value="<?=$val['user_grade_logo'];?>" class="ui-input w400" type="hidden"/>
                            <div class="notic">默认LOGO，最佳显示尺寸为100*100像素</div>
                        </dd>
                    </dl>
                <?php }elseif($val['id'] == 3){?>
                    <dl class="row bd">
                        <dt class="tit"><span>会员等级</span></dt>
                        <dd class="opt">
                            <input type="text" class="n-valid" value="<?=$val['user_grade_name'];?>" name="gr[<?=$key;?>][user_grade_name]">
                            &nbsp;晋级需交纳股金 <input type="text" class="n-valid" value="<?=$val['user_grade_shares'];?>" name="gr[<?=$key;?>][user_grade_shares]"> 元，
                                并前 <input type="text" class="n-valid"  value="<?=$val['user_grade_year_num'];?>" name="gr[<?=$key;?>][user_grade_year_num]"> 年每年发展
                            <input type="text" class="n-valid" value="<?=$val['user_grade_per_year'];?>" name="gr[<?=$key;?>][user_grade_per_year]"> 个及以上会员;
                            <input type="hidden" value="<?=$val['user_grade_id'];?>" name="gr[<?=$key;?>][user_grade_id]">
                        </dd>
                    </dl>
                    <dl class="row">
                        <dt class="tit"><span>会员福利</span></dt>
                        <dd class="opt">
                            1. 消费享受股东价；<br/>
                            2. 享受所有下线会员及消费者的每笔订单价与股东价的差额返利；<br/>
                            3. 享受缴纳股金的年度分红；<br/>
                            4. 所有下线的订单总额的指标金额：<input type="text" class="n-valid" value="<?=$val['order_amount'];?>" name="gr[<?=$key;?>][order_amount]">，<br/>
                            &nbsp;&nbsp;&nbsp;未超过指标金额部分，提成比例为<input type="text" class="n-valid" value="<?=$val['order_rebate1'];?>" name="gr[<?=$key;?>][order_rebate1]">%，<br/>
                            &nbsp;&nbsp;&nbsp;超过指标金额部分，提成比例再加<input type="text" class="n-valid" value="<?=$val['order_rebate2'];?>" name="gr[<?=$key;?>][order_rebate2]">%。
                        </dd>
                    </dl>
                    <dl  class="row">
                        <dt class="tit"><strong>等级图片</strong></dt>
                        <dd class="opt">
                            <img id="grade_logo_image<?=$key?>" name="gr[<?=$key?>][user_grade_logo]" alt="选择图片" src="<?=$val['user_grade_logo'];?>" width="100px" height="100px"/>

                            <div class="image-line upload-image"  id="grade_logo_upload<?=$key?>">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>

                            <input id="grade_logo<?=$key?>"  name="gr[<?=$key?>][user_grade_logo]" value="<?=$val['user_grade_logo'];?>" class="ui-input w400" type="hidden"/>
                            <div class="notic">默认LOGO，最佳显示尺寸为100*100像素</div>
                        </dd>
                    </dl>
                <?php }elseif($val['id'] == 4){?>
                <dl class="row bd">
                    <dt class="tit"><span>会员等级</span></dt>
                    <dd class="opt">
                        <input type="text" class="n-valid" value="<?=$val['user_grade_name'];?>" name="gr[<?=$key;?>][user_grade_name]">
                        &nbsp;晋级需交纳股金 <input type="text" class="n-valid" value="<?=$val['user_grade_shares'];?>" name="gr[<?=$key;?>][user_grade_shares]"> 元，
                        以会员价备货 <input type="text" class="n-valid" value="<?=$val['user_grade_stocks'];?>" name="gr[<?=$key;?>][user_grade_stocks]"> 元;
                        <input type="hidden" value="<?=$val['user_grade_id'];?>" name="gr[<?=$key;?>][user_grade_id]">
                    </dd>
                </dl>
                    <dl class="row">
                        <dt class="tit"><span>会员福利</span></dt>
                        <dd class="opt">
                            1. 消费享受股东价；<br/>
                            2. 享受所有下线会员及消费者的每笔订单价与股东价的差额返利；<br/>
                            3. 享受缴纳股金的年度分红；<br/>
                            4. 所有下线的订单提成规则：基础提成比例<input type="text" class="n-valid" value="<?=$val['order_rebate1'];?>" name="gr[<?=$key;?>][order_rebate1]">%<br/>
                            &nbsp;&nbsp;&nbsp;当前年度每新发展一个合伙人，提成比例增加<input type="text" class="n-valid" value="<?=$val['order_rebate2'];?>" name="gr[<?=$key;?>][order_rebate2]">%,
                            <input type="text" class="n-valid" value="<?=$val['order_rebate_top'];?>" name="gr[<?=$key;?>][order_rebate_top]">%封顶
                        </dd>
                    </dl>
                <dl  class="row">
                    <dt class="tit"><strong>等级图片</strong></dt>
                    <dd class="opt">
                        <img id="grade_logo_image<?=$key?>" name="gr[<?=$key?>][user_grade_logo]" alt="选择图片" src="<?=$val['user_grade_logo'];?>" width="100px" height="100px"/>

                        <div class="image-line upload-image"  id="grade_logo_upload<?=$key?>">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>

                        <input id="grade_logo<?=$key?>"  name="gr[<?=$key?>][user_grade_logo]" value="<?=$val['user_grade_logo'];?>" class="ui-input w400" type="hidden"/>
                        <div class="notic">默认LOGO，最佳显示尺寸为100*100像素</div>
                    </dd>
                </dl>
                <?php }?>
            <?php }?>
          <div class="bot"> <a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/models/upload_image.js" charset="utf-8"></script>
<script>
  //图片上传
    $(function(){
		<?php foreach($data as $key=>$val){ ?>
		$('#grade_logo_upload<?=$key?>').on('click', function () {
		 $.dialog({
					title: '图片裁剪',
					content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
					data: { SHOP_URL: SHOP_URL, width: 100, height: 100, callback: callback<?=$key?> },    // 需要截取图片的宽高比例
					width: '800px',
                    height:$(window).height()*0.9,
					lock: true
				})
			});

		function callback<?=$key?> ( respone , api ) {
				$('#grade_logo_image<?=$key?>').attr('src', respone.url);
				$('#grade_logo<?=$key?>').attr('value', respone.url);
				api.close();
			}
		<?php }?>

    })
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/user/grade/log.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>