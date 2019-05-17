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
    <div class="alert">
        <ul>
            <li><?=__('认证状态：').$re['status']?></li>
        </ul>
    </div>

    <form method="post" id="form">

        <div id="img_table" class="handle_pic fn-clear clearfix">
            <?php foreach ($array as $key => $value) {
            ?>
        <table width="20%" style="display: <?=$key<$re['arryCount']?"block":"none"?>" id="<?=$key?>">
            <tr>
                <td>
                    <div class="picture">
                        <img id="app_image<?=$key?>" src="<?php if(!empty($re['certification'][$key])){echo $re['certification'][$key]; }?>" />
                    </div>
                    <input type="hidden" value="<?php if(!empty($re['certification'][$key])){echo $re['certification'][$key]; }?>" name="certification[]" id="app_input<?=$key?>" class="text w145">
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
            <dd><input type="submit" value="<?=__('提交')?>" class="button bbc_seller_submit_btns">
                <input id="btnAdd" type="button" value="<?=__('添加照片')?>" class="button bbc_seller_submit_btns">
            </dd>
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
        app_upload0 = new UploadImage({
                            thumbnailWidth: 1200,
                            thumbnailHeight: 360,
                            imageContainer: '#app_image0',
                            uploadButton: '#app_upload0',
                            inputHidden: '#app_input0'
              });



    })


</script>
<script>

    $(document).ready(function(){
        var count=0;
         var ajax_url = './index.php?ctl=Seller_Shop_Setshop&met=editShopCertification&typ=json';
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
        $("#btnAdd").click(function(){
            if(count>=5){
                alert("最多只能添加5张图片");
                return;
            }
            $("table").each(function(i){
                if($(this).css("display") == 'block'){
                    count=parseInt($(this).attr("id"))+1;
                }
                if($(this).attr("id")==count){
                    $(this).css("display","block");
                }
            });
        });

    });
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>