<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
    <div class="main_cont wrap clearfix">
        <form id="form" name="form" action="" method="post" >
            <input name="from"  type="hidden" id='page_from' value="<?=$from?>" />
            <div class="account_left fl">
                <div class="account_mes">
                    <h4><?=_('低保认证')?></h4>
                    <table class="account_table">
                        <tbody>
                        <tr>
                            <td class="check_name"><?=_('用户名称：')?></td>
                            <td><?=$data['user_nickname']?></td>
                        </tr>
                        <tr>
                            <td class="check_name"><?=_('真实姓名：')?></td>
                            <td><?=$data['user_realname']?></td>
                        </tr>
                        <tr>
                            <td class="check_name"><?=_('证件类型：')?></td>
                            <td>
                                <select name="user_identity_type" id="user_identity_type" disabled>
                                    <option value="1"  <?php if($data['user_identity_type']==1){?>selected<?php }?>><?=_('身份证')?></option>
                                    <option value="2"  <?php if($data['user_identity_type']==2){?>selected<?php }?>><?=_('护照')?></option>
                                    <option value="3" <?php if($data['user_identity_type']==3){?>selected<?php }?>><?=_('军官证')?></option>
                                </select>
                                <div class="form-error"></div></td>
                        </tr>
                        <tr>
                            <td class="check_name"><?=_('证件号码：')?></td>
                            <td><?=$data['user_identity_card']?></td>
                        </tr>
                        <tr>
                            <td><?=_('证件有效期：')?></td>
                            <td><input readonly="readonly"  id="start_time"  name="user_identity_start_time"  class="w90 hasDatepicker" type="text" value="<?php echo $data['user_identity_start_time']>0 ? $data['user_identity_start_time'] : '';?>" />
                                <span></span>-
                                <input readonly="readonly" id="end_time" name="user_identity_end_time"  class="w90 hasDatepicker" type="text" value="<?php echo $data['user_identity_end_time']>0 ? $data['user_identity_end_time'] : '';?>" />
                            </td>
                        </tr>
                        <tr>
                            <td class="check_name"><?=_('低保证预览：')?></td>
                            <td>
                                <div class="user-avatar">
	                    <span>
	                 		   <img  id="image_img"  src="<?=$data['user_minimum_living_img']?$data['user_minimum_living_img']:'holder.js/120x120'; ?>" width="" height="120" nc_type="avatar">
	                    </span>
                                </div>
                                <p class="hint mt5"><span style="color:orange;"><?=_('低保证尺寸为')?><span class="phosize">240x151</span><?=_('像素，请根据系统操作提示进行裁剪并生效。')?></span></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="check_name"><?=_('低保正面照：')?></td>
                            <td>
                                <div >
                                    <a href="javascript:void(0);">
                                        <span>
                                            <input name="user_minimum_living_img" id="user_minimum_living_img" type="hidden" value="<?=$data['user_minimum_living_img']?>" />
                                        </span>
                                        <p id='user_upload' style="float:left;" class="bbc_btns"><i class="iconfont icon-upload-alt"></i><?=_('图片上传')?></p>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" value="<?=_('提交')?>" class="submit btn_active"></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="account_right fr">
                <div class="account_right_con">
                    <ul class="cert_instructions">
                        <li>
                            <h5><?=_('为什么要低保认证')?></h5>
                            <p><?=_('只有通过低保认证的用户，才可享受低保价购买商品')?></p>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
    <link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$this->view->js?>/webuploader.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js?>/upload/upload_image.js" charset="utf-8"></script>
    <link href="<?= $this->view->css ?>/webuploader.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.datetimepicker.js"></script>
    <script>
        var uploadw = 240;
        var uploadh = 151;

        //图片上传
        $(function(){
            $('#user_upload').on('click', function () {
                $.dialog({
                    title: '图片裁剪',
                    content: "url: <?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
                    data: { width: uploadw, height: uploadh, callback: callback },    // 需要截取图片的宽高比例
                    width: '800px',
                    /*height: '310px',*/
                    lock: true
                })
            });

            function callback ( respone , api ) {
                $('#image_img').attr('src', respone.url);
                $('#user_minimum_living_img').attr('value', respone.url);
                api.close();
            }

        })
        $(document).ready(function(){
            var ajax_url = '<?= Yf_Registry::get('url');?>?ctl=Info&met=editminLivingCertification&typ=json';
            $('#form').validator({
                ignore: ':hidden',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,
                fields : {
                    'user_minimum_living_img':'required;',
                },
                valid:function(form){
                    //表单验证通过，提交表单
                    $.ajax({
                        url: ajax_url,
                        data:$("#form").serialize(),
                        success:function(a){
                            if(a.status == 200)
                            {
                                Public.tips.success("<?=_('操作成功')?>");
                            }
                            else
                            {
                                if(typeof(a.msg) != 'undefined'){
                                    Public.tips.error(a.msg);
                                }else{
                                    Public.tips.error("<?=_('操作失败')?>");
                                }
                                return false;
                            }
                        }
                    });
                }
            });
        });
    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>