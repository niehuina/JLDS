<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';

// 当前管理员权限
$admin_rights = $this->getAdminRights();
// 当前页父级菜单 同级菜单 当前菜单
$menus = $this->getThisMenus();

?>
    <link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
    </head>
    <body>
    <div class="wrapper page">
        <div class="fixed-bar">
            <div class="item-title">
                <div class="subject">
                    <h3><?=$menus['father_menu']['menu_name']?></h3>
                    <h5><?=$menus['father_menu']['menu_url_note']?></h5>
                </div>
                <ul class="tab-base nc-row">
                    <?php
                    foreach($menus['brother_menu'] as $key=>$val){
                        if(in_array($val['rights_id'],$admin_rights)||$val['rights_id']==0){
                            ?>
                            <li><a <?php if(!array_diff($menus['this_menu'], $val)){?> class="current"<?php }?> href="<?= Yf_Registry::get('url') ?>?ctl=<?=$val['menu_url_ctl']?>&met=<?=$val['menu_url_met']?><?php if($val['menu_url_parem']){?>&<?=$val['menu_url_parem']?><?php }?>"><span><?=$val['menu_name']?></span></a></li>
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
                <?=$menus['this_menu']['menu_url_note']?>
            </ul>
        </div>

        <form id="mb-setting-form" method="post" enctype="multipart/form-data" name="settingForm">
            <input type="hidden" name="config_type[]" value="mb"/>
            <div class="ncap-form-default">

                <dl class="row">
                    <dt class="tit">
                        <label for="app_version">APP版本号</label>
                    </dt>
                    <dd class="opt">
                        <input id="app_version" name="mb[app_version]" value="<?=(@$data['app_version']['config_value'])?>" class="ui-input w400" type="text"/>
                        <p class="notic"></p>
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