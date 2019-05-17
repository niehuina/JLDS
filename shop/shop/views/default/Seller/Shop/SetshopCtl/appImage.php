<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
    <style>
        .webuploader-pick{background:none !important;color:#fff !important;}
    </style>
 <div class="alert">
        <ul>
            <li><?=__('1、最多可上传5张图片。')?></li>
            <li><?=__('2、支持jpg、jpeg、gif、png格式上传，建议图片宽度1200px、高度在300px到500px之间、大小1.00M以内的图片。提交2~5张图片')?></li>
            <li><?=__('3、按"提交"按钮，操作完成')?></li>
        </ul>
    </div>
    <form method="post" id="form">
       
        <div class="handle_pic fn-clear clearfix">
       <?php foreach ($array as $key => $value) {
           $column='app_img'.$key;
                    ?>
        <table width="20%">
            <tr>
                <td>
                    <div class="picture">
                        <img id="app_image<?=$key?>" src="<?php if(!empty($re[$column])){echo $re[$column]; }?>" />
                    </div>
                    <input type="hidden" id="app_input<?=$key?>" name="shop[app_img<?=$key?>]" value="<?=$re[$column]?>" />
                </td>
            </tr>
            <tr>
                <td>
                <a class="del button" href="javascript:del(<?=$value?>);"><?=__('删除')?></a>
                <a  id="app_upload<?=$key?>" class="lblock bbc_img_btns"><i class="iconfont icon-tupianshangchuan"></i><?=__('图片上传')?></a>
                </td>
            </tr>
        </table>
       <?php  } ?>
        </div>
        <dl class="handle_pic_foot">
            <dd><input type="submit" value="<?=__('提交')?>" class="button bbc_seller_submit_btns"></dd>
        </dl>
    </form>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
		function del(id)
		{
			$("#app_image"+id).attr("src","");
			$("#app_input"+id).val("");
		}
    </script>
     <script>
    //图片上传
    $(function(){
        app_upload1 = new UploadImage({
                            thumbnailWidth: 1200,
                            thumbnailHeight: 360,
                            imageContainer: '#app_image1',
                            uploadButton: '#app_upload1',
                            inputHidden: '#app_input1'
              });
        app_upload2 = new UploadImage({
                            thumbnailWidth: 1200,
                            thumbnailHeight: 360,
                            imageContainer: '#app_image2',
                            uploadButton: '#app_upload2',
                            inputHidden: '#app_input2'
              });
        app_upload3 = new UploadImage({
                            thumbnailWidth: 1200,
                            thumbnailHeight: 360,
                            imageContainer: '#app_image3',
                            uploadButton: '#app_upload3',
                            inputHidden: '#app_input3'
              });
        app_upload4 = new UploadImage({
                            thumbnailWidth: 1200,
                            thumbnailHeight: 360,
                            imageContainer: '#app_image4',
                            uploadButton: '#app_upload4',
                            inputHidden: '#app_input4'
              });
        app_upload5 = new UploadImage({
                            thumbnailWidth: 1200,
                            thumbnailHeight: 360,
                            imageContainer: '#app_image5',
                            uploadButton: '#app_upload5',
                            inputHidden: '#app_input5'
              });



    })


</script>
<script>
    $(document).ready(function(){
         var ajax_url = './index.php?ctl=Seller_Shop_Setshop&met=editAppImage&typ=json';
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
           valid:function(form){
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
                           Public.tips.success("<?=__('操作成功！')?>");
                         //  setTimeout('location.href="./index.php?ctl=Seller_Shop_Setshop&met=slide&typ=e"',3000); //成功后跳转
                        }
                        else
                        {
                            Public.tips.error("<?=__('操作失败！')?>");
                        }
                    }
                });
            }

        });
    });




</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>