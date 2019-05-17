<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<style>
.ui-jqgrid tr.jqgrow .img_flied{padding: 1px; line-height: 0px;}
.img_flied img{width: 100px; height: 30px;}
</style>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>供应商管理</h3>
                <h5>供应商管理--供应商列表</h5>
            </div>
            <ul class="tab-base nc-row">
                  <li><a class="current"><span>供应商管理</span></a></li>
                  <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Manage&met=join" ><span>审核开店信息</span></a></li>
                  <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Manage&met=pay" ><span>审核店铺付款</span></a></li>
                  <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Manage&met=reopen" ><span>续签申请</span></a></li>
                  <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Manage&met=category" ><span>经营类目申请</span></a></li>
                  <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Manage&met=settlement" ><span>结算周期设置</span></a></li>
            </ul>
        </div>
    </div>
	
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p>
	<div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em>
		</div>
        <ul>
            <li>如果当前时间超过店铺有效期或店铺处于关闭状态，前台将不能继续浏览该店铺，但是店主仍然可以编辑该店铺</li>
        </ul>
    </div>
          
    <div class="mod-toolbar-top cf">
		<div class="left">
            <div id="assisting-category-select" class="ui-tab-select">
                <ul class="ul-inline">
                    <li><span id="source"></span></li>
                    <li><span id="shop_class"></span></li>
                    <li><input type="text" id="searchName" class="ui-input ui-input-ph con" value="请输入相关数据..."></li>
                    <li><a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a></li>
                </ul>
            </div>
		</div>
		<div class="fr">
            <a class="ui-btn" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
        </div>
	</div>

    <div class="grid-wrap">
        <table id="grid"></table>
        <div id="page"></div>
    </div>
</div>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/supplier/index/shop_index_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>