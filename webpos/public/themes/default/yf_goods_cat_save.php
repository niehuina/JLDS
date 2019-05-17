<?php $this->layout('main'); ?>
<?php $this->block('content'); ?>
<?php $title = __('分类管理');?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo __('分类管理'); ?>
        <small>
            <a href="<?php echo $this->action('index'); ?>" class="btn bg-blue margin head-btns"><i class="iconfont icon-liebiao"></i>
                <?php echo __('列表'); ?>
            </a>
        </small>
    </h1>

</section>

<!-- Main content -->
<section class="content">
    <h2 class="module-title"><?php echo __('分类管理'); ?></h2>
    <div class='box box-primary box-absolute'>
        <?php echo form::open('form1',['class'=>'ajax','action'=>$this->action('save')]); ?>
        <div class="box-body">

           
                <?php if(get_data('cat_parent_id')){?>
                <div class="form-group">
                    <label><?php echo __('所属分类'); ?></label>
                    <input type="text" class="form-control"  value="<?php echo $info->cat_name;?>" readonly>
                    <input type="hidden"  value="<?php echo $info->id;?>" name='cat_parent_id' >
                    <input type="hidden"  value="<?php echo $info->id;?>" name='add' >
                </div>
                <?php } ?>

                <?php if(get_data('id') && $info->parent->cat_name){?>
                <div class="form-group">
                        <label><?php echo __('所属分类'); ?></label>
                        <input type="text" class="form-control" value="<?php echo $info->parent->cat_name;?>" readonly>
                        <input type="hidden" value="<?php echo $info->cat_parent_id;?>" name='cat_parent_id'>
                </div>
                <?php }?>
           

            <div class="form-group">
                <label ><?php echo __('分类名称'); ?></label>
                <?php if(get_data('cat_parent_id')){?>
                    <input type="text" class="form-control" id="cat_name" value="" name='cat_name' >
                <?php }elseif(get_data('id')){ ?>
                    <input type="text" class="form-control" id="cat_name" value="<?php echo $info->cat_name;?>" name='cat_name' >
                    <input type="hidden" name="id" value="<?php echo get_data('id');?>">
                <?php }else{?>
                    <input type="text" class="form-control" id="cat_name" value="" name='cat_name' >
                <?php } ?>

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

