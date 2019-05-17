<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('一般设置');?>

<style>
    .box-absolute .form-group>label{
        width:200px;
    }
    .box-absolute label+input, .box-absolute label+select, .list-module{
        margin-left:200px;
    }
    .box-absolute .form-group>input, .list-module, .box-absolute .form-group>select{
        width:200px;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo $title; ?>

    </h1>
</section>
<!-- Main content -->
<section class="content">
    <h2 class="module-title"><?php echo $title; ?></h2>
    <div class='box box-primary box-absolute'>
        <form class="form-horizontal ajax"  method="post" action="<?php echo $this->action('save'); ?>">
            <input type="hidden" name="type" value="config">
            <?php foreach ($config as $key => $val) { ?>
                <div class="form-group">
                    <label for="inputName" class=""><?php echo $val['label'];?></label>
                    <input type="checkbox" class="" id="<?php echo $key; ?>" <?php if($val['value'] == 'true') echo "checked";?>>
                    <input type="hidden" name="form[<?php echo $key; ?>]" id="input<?php echo $key; ?>">
                </div>
            <?php } ?>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary"><?php echo  __('确认提交') ?></button>
            </div>
        </form>
    </div>
</section>

<script>
    $(function () {
        $("input[type=checkbox]").click(function(){
            var inputId = "input" + this.id;
            if(this.checked){
                $("#"+inputId).val("true");
            }else{
                $("#"+inputId).val("false");
            }
        });
    })
</script>

<?php $this->end(); ?>