<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('门店角色配置');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('门店角色配置'); ?>
        <small>
            <a href="<?php echo $this->action('index');?>" class="btn bg-blue margin head-btns"><i class="iconfont icon-liebiao"></i>
                <?php echo __('列表'); ?>
            </a>
        </small>
    </h1>

</section>

<!-- Main content -->
<section class="content">
    <h2 class="module-title"><?php echo __('门店角色配置'); ?></h2>
    <div class='box box-primary box-absolute'>
        <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('save')]); ?>
        <div class="box-body">
            <div class="form-group">
                <label ><?php echo __('添加门店角色'); ?></label>
                <input type="text" class="form-control" id="title" value="<?php echo $info->title;?>" name='title'>
                <?php if(get_data('id')){?>
                    <input type="hidden" name="id" value="<?php echo get_data('id');?>">
                <?php }?>
            </div>
            <div class="form-group">
                <label ><?php echo __('角色标识'); ?></label>
                <input type="text" class="form-control" id="slug" value="<?php echo $info->slug;?>" name='slug' <?php if($_GET['id']){?>readonly <?php }?>>
            </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <button type="submit" class="btn btn-primary"><?php echo __('保存');?></button>
        </div>
        </form>
    </div>
</section>



<?php $this->end(); ?>

