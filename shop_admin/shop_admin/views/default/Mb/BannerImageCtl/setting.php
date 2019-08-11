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
    <link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

    <style>
        .webuploader-pick{ padding:1px; }
        /* */
    </style>
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
                            <li><a <?php if(!array_diff($menus['this_menu'], $val)){?> class="current"<?php }?>
                                        href="<?= Yf_Registry::get('url') ?>?ctl=<?=$val['menu_url_ctl']?>&met=<?=$val['menu_url_met']?><?php if($val['menu_url_parem']){?>&<?=$val['menu_url_parem']?><?php }?>"><span><?=$val['menu_name']?></span></a></li>
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
        <form method="post" enctype="multipart/form-data" id="acquiesce-setting-form" name="form_acquiesce">
            <div class="ncap-form-default">
                <dl class="row">
                    <dt class="tit">
                        <label>展示图片</label>
                    </dt>
                    <dd class="opt">
                        <img id="banner_image" name="" alt="选择图片" src="http://127.0.0.1/yf_shop_admin/shop_admin/static/default/images/default_user_portrait.gif"
                             width="640px" height="305px"/>

                        <div class="image-line upload-image" id="banner_image_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>

                        <input id="banner_image_logo"  name="" value="" class="ui-input w400" type="hidden"/>
                        <p class="notic">展示图片，建议大小640x305像素PNG图片。</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label>图片跳转链接</label>
                    </dt>
                    <dd class="opt">
                        <textarea value="" name="banner_url" id="banner_url" class="ui-input ui-input-ph" /></textarea>
                        <p class="notic">图片点击后的跳转链接</p>
                    </dd>
                </dl>
                <input type="hidden" id="banner_type" value="<?=$data['banner_type']?>">
                <input type="hidden" id="id" value="">
                <div class="bot"> <a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
            </div>
        </form>
    </div>
    <script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js?>/models/upload_image.js" charset="utf-8"></script>
    <script>
        $(function(){
            $.ajax({
                url: SITE_URL + '?ctl=Mb_BannerImage&met=settingList&bannerType=' + $("#banner_type").val() + '&typ=json',
                async:false,
                success: function(data){
                    if (data.status == 200) {
                        var rData = data.data;
                        $('#id').val(rData.mb_banner_image_id);
                        $('#banner_type').val(rData.banner_type);
                        $('#banner_url').val(rData.banner_url);
                        $('#banner_image').prop('src', rData.banner_image);
                        $('#banner_image_logo').val(rData.banner_image);

                        return true;
                    } else {
                        Public.tips({type: 1, content: data.msg});
                    }
                }
            });

            //图片上传
            new UploadImage({
                thumbnailWidth: 640,
                thumbnailHeight: 305,
                imageContainer: '#banner_image',
                uploadButton: '#banner_image_upload',
                inputHidden: '#banner_image_logo'
            });

            $(".submit-btn").bind("click", function () {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        var param = {
                            mb_banner_image_id: $('#id').val(),
                            banner_image: $('#banner_image_logo').val(),
                            banner_url: $("#banner_url").val(),
                        };

                        Public.ajaxPost(SITE_URL + '?ctl=Mb_BannerImage&met=editBannerImage&typ=json', {
                            param: param
                        }, function (data) {
                            if (data.status == 200) {
                                typeof callback == 'function' && callback(data.data, oper, window);
                                return true;
                            } else {
                                Public.tips({type: 1, content: data.msg});
                            }
                        })
                    },
                    function ()
                    {
                    });
            });
        })

    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>