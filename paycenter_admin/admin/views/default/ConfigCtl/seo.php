<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>SEO设置&nbsp;</h3>
                <h5>商城各级页面搜索引擎优化设置选项</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=site&config_type%5B%5D=site"><span>基础设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=settings&typ=e&config_type%5B%5D=site"><span>站点Logo</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=msgAccount&config_type%5B%5D=email&config_type%5B%5D=sms">邮件设置</a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=mobileAccount&config_type%5B%5D=mobile&config_type%5B%5D=sms">短信设置</a></li>
                <li><a class="current" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=seo&typ=e&config_type%5B%5D=seo"><span>SEO配置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=set&typ=e&config_type%5B%5D=set"><span>一般配置</span></a></li>
            </ul>
        </div>
    </div>
    <p class="warn_xiaoma"><span></span><em></em></p>
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
          <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
          <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em> </div>
        <ul>
          <li>SEO设置</li>
        </ul>
    </div>
    <form style="" method="post" name="form_index" id="seo-setting-form">
        <input type="hidden" name="config_type[]" value="seo"/>
        <input name="form_submit" value="ok" type="hidden">
        <span style="display:none" nctype="hide_tag"><a style="padding-left: 5px;">{sitename}</a></span>
        <div class="ncap-form-default">
          <dl class="row">
            <dt class="tit">
                <label for="title"> title</label>
            </dt>
            <dd class="opt">
              <input id="title" name="seo[title]" value="<?=($data['title']['config_value'])?>" class="ui-input w400" type="text" />
              <span id="theme"></span>
              <p class="notic">标题显示</p>
            </dd>
          </dl>
          <dl class="row">
            <dt class="tit">
                <label for="keyword"> keyword</label>
            </dt>
            <dd class="opt">
              <input id="keyword" name="seo[keyword]" value="<?=($data['keyword']['config_value'])?>" class="ui-input w400" type="text" />
              <span id="theme"></span>
              <p class="notic">关键词搜索</p>
            </dd>
          </dl>
          <dl class="row">
            <dt class="tit">
                <label for="description"> description</label>
            </dt>
            <dd class="opt">
              <input id="description" name="seo[description]" value="<?=($data['description']['config_value'])?>" class="ui-input w400" type="text" />
              <span id="theme"></span>
              <p class="notic">相关内容描述</p>
            </dd>
          </dl>
          <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>